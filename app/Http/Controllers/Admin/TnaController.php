<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Tna;
use App\Models\User;
use App\Models\Registration;
use App\Enums\RealizationStatus;
use Illuminate\Validation\Rule;
use App\Http\Requests\Admin\StoreTnaRequest;
use App\Http\Requests\Admin\UpdateTnaRequest;

class TnaController extends Controller
{
    use AuthorizesRequests;

    public function index()
    {
        $tnas = Tna::with('user')
            ->latest()
            ->paginate(10);
            
        return view('admin.tnas.index', compact('tnas'));
    }

    public function create()
    {
        return view('admin.tnas.form');
    }
    public function store(StoreTnaRequest $request)
    {
        $validated = $request->validated();

        // Calculate batch number for the same training name (across all years)
        $batchCount = Tna::where('name', $validated['name'])->count();
        $validated['batch'] = $batchCount + 1;
        
        // Calculate year sequence for tna_code (count of TNAs in this year)
        $yearSequence = Tna::where('period', $validated['period'])->count() + 1;
        
        // Generate unique tna_code: TNA.{year}.{year_sequence}
        $validated['tna_code'] = 'TNA.' . $validated['period'] . '.' . $yearSequence;
        $validated['user_id'] = Auth::id();
        
        // Handle file upload for spt_file_path
        if ($request->hasFile('spt_file_path')) {
            $validated['spt_file_path'] = $request->file('spt_file_path')->store('spt_files', 'public');
        }
        
        Tna::create($validated);
        
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
        
        // Get registered user IDs
        $registeredUserIds = $tna->registrations->pluck('user_id')->toArray();
        
        // Get trainee users not yet registered
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
        
        // Handle file upload for spt_file_path
        if ($request->hasFile('spt_file_path')) {
            // Delete old file if exists
            if ($tna->spt_file_path && Storage::disk('public')->exists($tna->spt_file_path)) {
                Storage::disk('public')->delete($tna->spt_file_path);
            }
            $validated['spt_file_path'] = $request->file('spt_file_path')->store('spt_files', 'public');
        }
        
        $tna->update($validated);
        
        return redirect()->route('admin.tnas.index')
            ->with('success', 'Data TNA berhasil diperbarui.');
    }

    public function destroy(Tna $tna)
    {
        $this->authorize('delete', $tna);
        
        $tna->delete();
        
        return redirect()->route('admin.tnas.index')
            ->with('success', 'Data TNA berhasil dihapus.');
    }

    public function cancel(Tna $tna)
    {
        $this->authorize('update', $tna);
        
        $tna->update([
            'realization_status' => RealizationStatus::TIDAK_TEREALISASI
        ]);
        
        return redirect()->route('admin.tnas.show', $tna)
            ->with('success', 'TNA berhasil dibatalkan.');
    }

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
        
        return redirect()->route('admin.tnas.edit', $tna)
            ->with('success', 'Peserta berhasil ditambahkan.');
    }

    public function destroyRegistration(Registration $registration)
    {
        $tna = $registration->tna; 

        $this->authorize('manageParticipants', $tna);
        
        $registration->delete();
        
        return redirect()->route('admin.tnas.edit', $tna)
            ->with('success', 'Peserta berhasil dihapus.');
    }
}
