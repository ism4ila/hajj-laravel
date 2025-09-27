<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserProfileController extends Controller
{
    /**
     * Display the user's profile.
     */
    public function show(): View
    {
        $user = Auth::user();

        // Get user's recent activity
        $recentPayments = $user->payments()
            ->with(['pilgrim.campaign'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        // Get statistics
        $stats = [
            'total_payments_created' => $user->payments()->count(),
            'total_amount_processed' => $user->payments()->where('status', 'completed')->sum('amount'),
            'this_month_payments' => $user->payments()
                ->whereMonth('created_at', now()->month)
                ->whereYear('created_at', now()->year)
                ->count(),
        ];

        return view('profile.show', compact('user', 'recentPayments', 'stats'));
    }

    /**
     * Show the form for editing the user's profile.
     */
    public function edit(): View
    {
        return view('profile.edit', ['user' => Auth::user()]);
    }

    /**
     * Update the user's profile information.
     */
    public function update(Request $request): RedirectResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users,email,' . $user->id,
        ], [
            'name.required' => 'Le nom est obligatoire.',
            'email.required' => 'L\'email est obligatoire.',
            'email.email' => 'L\'email doit être une adresse valide.',
            'email.unique' => 'Cet email est déjà utilisé par un autre utilisateur.',
        ]);

        $user->update($validated);

        return redirect()->route('profile.show')
            ->with('success', 'Profil mis à jour avec succès.');
    }

    /**
     * Show the change password form.
     */
    public function showChangePasswordForm(): View
    {
        return view('profile.change-password');
    }

    /**
     * Update the user's password.
     */
    public function changePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required',
            'password' => ['required', 'confirmed', Password::defaults()],
        ], [
            'current_password.required' => 'Le mot de passe actuel est obligatoire.',
            'password.required' => 'Le nouveau mot de passe est obligatoire.',
            'password.confirmed' => 'La confirmation du mot de passe ne correspond pas.',
        ]);

        // Verify current password
        if (!Hash::check($request->current_password, Auth::user()->password)) {
            return back()->withErrors([
                'current_password' => 'Le mot de passe actuel est incorrect.'
            ]);
        }

        // Update password
        Auth::user()->update([
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('profile.show')
            ->with('success', 'Mot de passe modifié avec succès.');
    }

    /**
     * Delete the user's account (if allowed).
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validate([
            'password' => 'required',
        ], [
            'password.required' => 'Le mot de passe est obligatoire pour confirmer la suppression.',
        ]);

        $user = Auth::user();

        if (!Hash::check($request->password, $user->password)) {
            return back()->withErrors([
                'password' => 'Le mot de passe est incorrect.'
            ]);
        }

        // Check if user has created payments (prevent deletion)
        if ($user->payments()->count() > 0) {
            return back()->withErrors([
                'account' => 'Impossible de supprimer ce compte car il a créé des paiements. Contactez l\'administrateur.'
            ]);
        }

        Auth::logout();
        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('home')
            ->with('success', 'Compte supprimé avec succès.');
    }
}