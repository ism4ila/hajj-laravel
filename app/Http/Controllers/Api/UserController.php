<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;

class UserController extends Controller
{
    public function index(Request $request): JsonResponse
    {
        $query = User::with('role:id,name,display_name');

        if ($request->has('role_id') && $request->role_id) {
            $query->where('role_id', $request->role_id);
        }

        if ($request->has('search') && $request->search) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $users = $query->orderBy('created_at', 'desc')
                       ->paginate($request->per_page ?? 15);

        $users->getCollection()->transform(function ($user) {
            return [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ] : null,
                'last_login' => null, // Peut être ajouté plus tard
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $users->items(),
            'meta' => [
                'current_page' => $users->currentPage(),
                'last_page' => $users->lastPage(),
                'per_page' => $users->perPage(),
                'total' => $users->total(),
            ]
        ]);
    }

    public function show($id): JsonResponse
    {
        $user = User::with(['role', 'payments:id,amount,created_by,created_at'])
                    ->find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                    'description' => $user->role->description,
                    'permissions' => $user->role->permissions,
                ] : null,
                'statistics' => [
                    'payments_created' => $user->payments->count(),
                    'total_amount_handled' => $user->payments->sum('amount'),
                ],
                'recent_payments' => $user->payments->take(5)->map(function ($payment) {
                    return [
                        'id' => $payment->id,
                        'amount' => $payment->amount,
                        'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                    ];
                }),
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        // Vérifier les permissions
        if (!Auth::user()->hasPermission('user_create')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => ['required', Password::min(8)->mixedCase()->numbers()],
            'role_id' => 'required|exists:user_roles,id',
        ]);

        $validated['password'] = Hash::make($validated['password']);

        $user = User::create($validated);
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur créé avec succès',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ] : null,
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]
        ], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->hasPermission('user_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        // Empêcher la modification de son propre rôle
        if ($user->id === Auth::id() && $request->has('role_id')) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas modifier votre propre rôle'
            ], 400);
        }

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $id,
            'role_id' => 'sometimes|required|exists:user_roles,id',
        ]);

        $user->update($validated);
        $user->load('role');

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur mis à jour avec succès',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ] : null,
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function destroy($id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->hasPermission('user_delete')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        // Empêcher la suppression de son propre compte
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas supprimer votre propre compte'
            ], 400);
        }

        // Vérifier s'il y a des paiements liés
        if ($user->payments()->count() > 0) {
            return response()->json([
                'success' => false,
                'message' => 'Impossible de supprimer un utilisateur avec des paiements enregistrés'
            ], 400);
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'Utilisateur supprimé avec succès'
        ]);
    }

    public function resetPassword(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->hasPermission('user_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        $validated = $request->validate([
            'new_password' => ['required', Password::min(8)->mixedCase()->numbers()],
        ]);

        $user->update([
            'password' => Hash::make($validated['new_password'])
        ]);

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé avec succès'
        ]);
    }

    public function getRoles(): JsonResponse
    {
        $roles = UserRole::select('id', 'name', 'display_name', 'description')
                        ->orderBy('name')
                        ->get();

        return response()->json([
            'success' => true,
            'data' => $roles->map(function ($role) {
                return [
                    'id' => $role->id,
                    'name' => $role->name,
                    'display_name' => $role->display_name,
                    'description' => $role->description,
                    'permissions' => $role->permissions,
                    'users_count' => $role->users()->count(),
                ];
            })
        ]);
    }

    public function updateRole(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        // Vérifier les permissions
        if (!Auth::user()->hasPermission('user_update')) {
            return response()->json([
                'success' => false,
                'message' => 'Permission refusée'
            ], 403);
        }

        // Empêcher la modification de son propre rôle
        if ($user->id === Auth::id()) {
            return response()->json([
                'success' => false,
                'message' => 'Vous ne pouvez pas modifier votre propre rôle'
            ], 400);
        }

        $validated = $request->validate([
            'role_id' => 'required|exists:user_roles,id',
        ]);

        $oldRole = $user->role?->display_name;
        $user->update($validated);
        $user->load('role');
        $newRole = $user->role?->display_name;

        return response()->json([
            'success' => true,
            'message' => "Rôle mis à jour: {$oldRole} → {$newRole}",
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'role' => [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                ],
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function profile(): JsonResponse
    {
        $user = Auth::user();
        $user->load('role');

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'role' => $user->role ? [
                    'id' => $user->role->id,
                    'name' => $user->role->name,
                    'display_name' => $user->role->display_name,
                    'permissions' => $user->role->permissions,
                ] : null,
                'statistics' => [
                    'payments_created' => $user->payments()->count(),
                    'total_amount_handled' => $user->payments()->sum('amount'),
                ],
                'created_at' => $user->created_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function updateProfile(Request $request): JsonResponse
    {
        $user = Auth::user();

        $validated = $request->validate([
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|email|unique:users,email,' . $user->id,
        ]);

        $user->update($validated);

        return response()->json([
            'success' => true,
            'message' => 'Profil mis à jour avec succès',
            'data' => [
                'id' => $user->id,
                'name' => $user->name,
                'email' => $user->email,
                'updated_at' => $user->updated_at->format('Y-m-d H:i:s'),
            ]
        ]);
    }

    public function activities(Request $request, $id): JsonResponse
    {
        $user = User::find($id);

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Utilisateur non trouvé'
            ], 404);
        }

        $limit = $request->input('limit', 20);

        // Récupérer les activités récentes (paiements créés)
        $recentPayments = $user->payments()
                              ->with(['pilgrim:id,firstname,lastname'])
                              ->orderBy('created_at', 'desc')
                              ->limit($limit)
                              ->get()
                              ->map(function ($payment) {
                                  return [
                                      'type' => 'payment_created',
                                      'description' => "Paiement de {$payment->amount}€ pour {$payment->pilgrim->full_name}",
                                      'amount' => $payment->amount,
                                      'pilgrim' => $payment->pilgrim->full_name,
                                      'receipt_number' => $payment->receipt_number,
                                      'created_at' => $payment->created_at->format('Y-m-d H:i:s'),
                                  ];
                              });

        return response()->json([
            'success' => true,
            'data' => [
                'user' => [
                    'id' => $user->id,
                    'name' => $user->name,
                ],
                'recent_activities' => $recentPayments,
                'summary' => [
                    'total_payments' => $user->payments()->count(),
                    'total_amount_handled' => $user->payments()->sum('amount'),
                    'last_activity' => $recentPayments->first()['created_at'] ?? null,
                ]
            ]
        ]);
    }
}