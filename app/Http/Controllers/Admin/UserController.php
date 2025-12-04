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
        // Cache users list for 60 seconds
        $users = Cache::remember('admin_users_list', 60, function () {
            return User::with(['position', 'unit'])->get();
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
        
        User::create($validated);
        
        // Clear related caches
        $this->clearRelatedCaches([
            'admin_users_list',
            'admin_dashboard_stats',
        ]);

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
        
        // Clear related caches
        $this->clearRelatedCaches([
            'admin_users_list',
            'admin_dashboard_stats',
        ]);
        
        // Clear user-specific caches if trainee
        if ($user->role->value === 'trainee') {
            $this->clearUserCaches($user->id);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil diperbarui.');
    }

    public function destroy(User $user)
    {
        $userId = $user->id;
        $isTrainee = $user->role->value === 'trainee';
        
        $user->delete();
        
        // Clear related caches
        $this->clearRelatedCaches([
            'admin_users_list',
            'admin_dashboard_stats',
        ]);
        
        // Clear user-specific caches if trainee
        if ($isTrainee) {
            $this->clearUserCaches($userId);
        }

        return redirect()->route('admin.users.index')
            ->with('success', 'User berhasil dihapus.');
    }
}
