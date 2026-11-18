<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

/**
 * Organization administration (routes protected by the org.admin middleware).
 */
class TeamController extends Controller
{
    public function index(Request $request)
    {
        $members = User::where('organization_id', $request->user()->organization_id)->orderBy('name')->get();

        return view('team.index', compact('members'));
    }

    public function updateRole(Request $request, User $user): RedirectResponse
    {
        $data = $request->validate(['role' => ['required', 'in:admin,member']]);

        $user->update(['role' => $data['role']]);

        return back()->with('status', "Role updated for {$user->name}.");
    }
}
