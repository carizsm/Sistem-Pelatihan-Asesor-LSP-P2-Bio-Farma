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
                    'id',
                    'nik',
                    'name',
                    'position',
                    'unit',
                    'role'
                )
                ->with([
                    'position:id,position_name',
                    'unit:id,unit_name'
                ])
                ->paginate(10);
        });
        
        return view('admin.users.index', compact('users'));
    }

    public function create()
    {
        return view('admin.users.form');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'nik' => 'required|numeric|digits:8|unique:users,nik',
            'email' => 'required|email:dns|unique:users,email',    
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'trainee'])],
            'position' => 'required|string|max:255',
            'unit'     => 'required|string|max:255',
        ], [
            'required' => ':attribute wajib diisi.',
            'numeric' => ':attribute harus berupa angka.',
            'digits' => ':attribute harus berjumlah tepat :digits digit.',
            'unique' => ':attribute sudah terdaftar, gunakan yang lain.',
            'min' => ':attribute minimal harus :min karakter.',
            'confirmed' => 'Konfirmasi password tidak cocok.',
            'email' => 'Format email tidak valid.',
            'in' => 'Pilihan :attribute tidak valid.',
        ], [
            'nik' => 'NPK',
            'password' => 'Kata Sandi',
            'role' => 'Peran Pengguna'
        ]);

        $validated['password'] = Hash::make($validated['password']);
        
        $user = User::create($validated);
        
        $this->flushUserCache($user); 

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil ditambahkan.');
    }

    public function edit(User $user)
    {
        return view('admin.users.form', compact('user'));
    }

    public function update(Request $request, User $user)
    {
        $rules = [
            'name' => 'required|string|max:255',
            'email' => 'required|email:dns|unique:users,email,' . $user->id,
            'nik' => 'required|numeric|digits:8|unique:users,nik,' . $user->id,
            'role' => 'required',
        ];

        if ($request->filled('password')) {
            $rules['password'] = 'min:8';
        }

        $validated = $request->validate($rules);

        if ($request->filled('password')) {
            $validated['password'] = bcrypt($request->password);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')->with('success', 'Data User berhasil diperbarui.');
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