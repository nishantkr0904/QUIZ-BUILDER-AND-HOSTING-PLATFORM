<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class UserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['results as completed_quizzes' => function($query) {
            $query->where('completed', true);
        }])
        ->orderBy('created_at', 'desc')
        ->paginate(20);

        return view('admin.users.index', compact('users'));
    }

    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(Request $request, $id)
    {
        $user = User::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        if (isset($validated['password'])) {
            $validated['password'] = Hash::make($validated['password']);
        } else {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users')
            ->with('success', 'User updated successfully');
    }

    public function toggleAdmin($id)
    {
        $user = User::findOrFail($id);
        
        // Prevent removing admin role from the last admin
        if ($user->is_admin && User::where('is_admin', true)->count() <= 1) {
            return redirect()->route('admin.users')
                ->with('error', 'Cannot remove the last admin user');
        }

        $user->update(['is_admin' => !$user->is_admin]);

        return redirect()->route('admin.users')
            ->with('success', $user->is_admin ? 'Admin privileges granted' : 'Admin privileges revoked');
    }

    public function show($id)
    {
        $user = User::with([
            'results' => function($query) {
                $query->with('quiz')
                    ->where('completed', true)
                    ->orderBy('completed_at', 'desc');
            }
        ])->findOrFail($id);

        return view('admin.users.show', compact('user'));
    }
}
