<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ClearsRelatedCache;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;
use App\Models\Tna;
use App\Models\User;
use App\Models\Registration;
use App\Enums\RealizationStatus;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\StoreTnaRequest;
use App\Http\Requests\Admin\UpdateTnaRequest;

class TnaController extends Controller
{
    use AuthorizesRequests, ClearsRelatedCache;

    public function index()
    {
        $page = request()->get('page', 1);
        $tnas = Cache::remember("admin_tnas_list_page_{$page}", 60, function () {
            return Tna::with('user')
                ->latest()
                ->paginate(10);
        });
            
        return view('admin.tnas.index', compact('tnas'));
    }

    public function create()
    {
        return view('admin.tnas.form');
    }

    public function store(StoreTnaRequest $request)
    {
        $data = $request->validated();

        // Auto-generate Batch & Code
        $batchCount = Tna::where('name', $data['name'])->count();
        $data['batch'] = $batchCount + 1;
        
        $yearSequence = Tna::where('period', $data['period'])->count() + 1;
        $data['tna_code'] = 'TNA.' . $data['period'] . '.' . $yearSequence;
        
        $data['user_id'] = Auth::id();
        
        if ($request->hasFile('spt_file_path')) {
            $data['spt_file_path'] = $request->file('spt_file_path')->store('spt_files', 'public');
        }
        
        $tna = Tna::create($data);
        
        // Bersih-bersih (Hanya level global karena TNA baru belum punya peserta)
        $this->flushTnaCache($tna);
        
        return redirect()->route('admin.tnas.index')
            ->with('success', 'Data TNA berhasil ditambahkan.');
    }

    public function show(Tna $tna)
    {
        $tna->load([
            'user',
            'registrations.user'
        ]);
        
        return view('admin.tnas.show', compact('tna'));
    }

    public function edit(Tna $tna)
    {
        $tna->load('registrations.user.unit', 'registrations.user.position');
        
        $registeredUserIds = $tna->registrations->pluck('user_id')->toArray();
        
        $availableUsers = User::where('role', 'trainee')
            ->whereNotIn('id', $registeredUserIds)
            ->orderBy('name')
            ->get();
        
        return view('admin.tnas.form', compact('tna', 'availableUsers'));
    }

    public function update(UpdateTnaRequest $request, Tna $tna)
    {
        $this->authorize('update', $tna);
        
        $validated = $request->validated();
        
        if ($request->hasFile('spt_file_path')) {
            if ($tna->spt_file_path && Storage::disk('public')->exists($tna->spt_file_path)) {
                Storage::disk('public')->delete($tna->spt_file_path);
            }
            $validated['spt_file_path'] = $request->file('spt_file_path')->store('spt_files', 'public');
        }
        
        $tna->update($validated);
        
        // Bersih-bersih: Global + Laporan TNA + SEMUA Peserta yg terdaftar
        // Kita ambil ID peserta untuk membersihkan cache dashboard mereka
        $participantIds = $tna->registrations->pluck('user_id')->toArray();
        $this->flushTnaCache($tna, $participantIds);
        
        return redirect()->route('admin.tnas.index')
            ->with('success', 'Data TNA berhasil diperbarui.');
    }

    public function destroy(Tna $tna)
    {
        $this->authorize('delete', $tna);
        
        // Simpan ID peserta dulu sebelum dihapus datanya
        $participantIds = $tna->registrations->pluck('user_id')->toArray();
        
        $tna->delete();
        
        // Bersih-bersih (Pass object TNA meski sudah didelete, ID-nya masih ada di memory)
        $this->flushTnaCache($tna, $participantIds);
        
        return redirect()->route('admin.tnas.index')
            ->with('success', 'Data TNA berhasil dihapus.');
    }

    public function cancel(Tna $tna)
    {
        $this->authorize('update', $tna);
        
        $tna->update([
            'realization_status' => RealizationStatus::CANCELED
        ]);
        
        // Bersih-bersih standar
        $this->flushTnaCache($tna);
        
        return redirect()->route('admin.tnas.show', $tna)
            ->with('success', 'TNA berhasil dibatalkan.');
    }

    // ==========================================
    // LOGIC REGISTRATION (Merged here)
    // ==========================================

    public function storeRegistration(Request $request, Tna $tna)
    {
        $this->authorize('manageParticipants', $tna);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:users,id',
                Rule::unique('registrations')->where('tna_id', $tna->id)
            ]
        ], [
            'user_id.unique' => 'User ini sudah terdaftar dalam TNA ini.'
        ]);
        
        $validated['regist_date'] = now();
        $validated['status'] = \App\Enums\RegistrationStatus::TERDAFTAR;
        
        $tna->registrations()->create($validated);
        
        // Bersih-bersih: Global + TNA + User Spesifik yg baru masuk
        $this->flushTnaCache($tna, $validated['user_id']);
        
        return redirect()->route('admin.tnas.edit', $tna)
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function destroyRegistration(Registration $registration)
    {
        $tna = $registration->tna;
        $userId = $registration->user_id;

        $this->authorize('manageParticipants', $tna);
        
        $registration->delete();
        
        // Bersih-bersih: Global + TNA + User Spesifik yg dihapus
        $this->flushTnaCache($tna, $userId);
        
        return redirect()->route('admin.tnas.edit', $tna)
            ->with('success', 'Peserta berhasil dihapus.');
    }

    /**
     * PRIVATE HELPER: Centralized Cache Clearing Logic
     * Satu pintu untuk membersihkan segala kekacauan cache.
     * * @param Tna $tna Object TNA yang sedang diproses
     * @param mixed $userIds Array ID user atau Single ID user (optional)
     */
    private function flushTnaCache(Tna $tna, $userIds = [])
    {
        // 1. Bersihkan Cache Global Admin (Index & Dashboard)
        $this->clearRelatedCaches([
            'admin_tnas_list_page_*',
            'admin_dashboard_stats',
            'admin_feedback_results_index',
            'admin_quiz_results_index',
            'admin_quiz_questions_index',
        ]);

        // 2. Bersihkan Cache Laporan Spesifik TNA
        // Ini memastikan Admin tidak melihat laporan 'hantu'
        $this->clearRelatedCaches([
            "admin_feedback_report_tna_{$tna->id}",
            "admin_quiz_pretest_tna_{$tna->id}",
            "admin_quiz_posttest_tna_{$tna->id}",
            "admin_quiz_questions_tna_{$tna->id}",
            "admin_participants_tna_{$tna->id}",
        ]);

        // 3. Bersihkan Cache User/Peserta
        // Supaya dashboard mereka update (muncul/hilang jadwal)
        if (!is_array($userIds)) {
            $userIds = [$userIds]; // Convert single ID to array
        }

        foreach ($userIds as $id) {
            // Panggil method dari Trait untuk hapus cache peserta
            // Kita pass $tna->id juga biar method trait bisa handle logic lanjutan
            $this->clearUserCaches($id, $tna->id);
        }
    }
}