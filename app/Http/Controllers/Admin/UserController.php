<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Traits\ClearsRelatedCache;
use App\Models\User;
use App\Models\Position;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Cache;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    use ClearsRelatedCache;

    public function index()
    {
        // OPTIMIZED: Cache users list with selective column fetching
        $page = request()->get('page', 1);
        
        $users = Cache::remember('admin_users_list_page_' . $page, 60, function () {
            return User::select(
                    'id',           // Required for route binding (edit/delete)
                    'nik',          // Displayed in table
                    'name',         // Displayed in table
                    'position_id',  // Foreign key for position relationship
                    'unit_id',      // Foreign key for unit relationship
                    'role'          // Required for Role badge logic if needed
                )
                ->with([
                    'position:id,position_name',  // Only fetch id and position_name
                    'unit:id,unit_name'           // Only fetch id and unit_name
                ])
                ->paginate(10);
        });
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        $positions = Position::all();
        $units = Unit::all();
        return view('admin.users.form', compact('positions', 'units'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|string|size:10|unique:users,nik',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'trainee'])],
            'position_id' => 'required|exists:positions,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        // Simpan user ke variabel agar bisa dipassing ke helper cache
        $user = User::create($validated);
        
        // Bersih-bersih Cache
        $this->flushUserCache($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        $positions = Position::all();
        $units = Unit::all();
        return view('admin.users.form', compact('user', 'positions', 'units'));
    }

    public function update(Request $request, User $user)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => ['required', 'string', 'size:10', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'email', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'trainee'])],
            'position_id' => 'required|exists:positions,id',
            'unit_id' => 'required|exists:units,id',
        ]);

        if (!empty($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);
        
        // Bersih-bersih Cache
        $this->flushUserCache($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $user->delete();
        
        // Bersih-bersih Cache (Objek $user masih menyimpan data ID dan Role di memori meski sudah delete DB)
        $this->flushUserCache($user);

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }

    /**
     * PRIVATE HELPER: Centralized User Cache Clearing
     * Menghapus cache global dan cache spesifik user jika dia trainee
     */
    private function flushUserCache(User $user)
    {
        // 1. Clear Global Cache (List User & Dashboard Stats)
        $this->clearRelatedCaches([
            'admin_users_list_page_*',
            'admin_dashboard_stats',
        ]);

        // 2. Clear User Specific Cache (Hanya jika role trainee)
        // Menggunakan logic pengecekan role yang sama dengan kode asli
        if ($user->role->value === 'trainee') {
            $this->clearUserCaches($user->id);
        }
    }
}