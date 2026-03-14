<?php

namespace App\Http\Controllers;

use App\Enums\BusinessMemberRole;
use App\Enums\BusinessStatus;
use App\Models\Business;
use App\Models\PlatformModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class BusinessController extends Controller
{
    /** Negocios donde el usuario es propietario o miembro */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Propios (como owner)
        $owned = Business::where('owner_id', $user->id)
            ->with('owner:id,name,email')
            ->get()
            ->map(fn ($b) => array_merge($b->toArray(), [
                'member_role' => BusinessMemberRole::Owner->value,
                'is_owner'    => true,
            ]));

        // Invitado (como miembro de otro negocio)
        $memberOf = Business::whereHas('businessMembers', fn ($q) =>
            $q->where('user_id', $user->id)
        )
            ->where('owner_id', '!=', $user->id)
            ->with(['owner:id,name,email', 'businessMembers' => fn ($q) =>
                $q->where('user_id', $user->id)
            ])
            ->get()
            ->map(fn ($b) => array_merge($b->toArray(), [
                'member_role' => $b->businessMembers->first()?->role?->value,
                'is_owner'    => false,
            ]));

        $businesses = $owned->merge($memberOf)->values();

        return response()->json([
            'businesses'       => $businesses,
            'business_count'   => $businesses->count(),
            'business_limit'   => 3,      // futuro: según plan
            'subscription_plan'=> 'free', // futuro: según plan
        ]);
    }

    public function store(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name'    => ['required', 'string', 'max:255'],
            'ruc'     => ['nullable', 'string', 'size:11', 'unique:businesses'],
            'type'    => ['required', 'string'],
            'modules' => ['nullable', 'array'],
        ]);

        $slug = Str::slug($validated['name']);
        $base = $slug;
        $i    = 1;
        while (Business::where('slug', $slug)->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        $business = Business::create([
            'owner_id' => $request->user()->id,
            'name'     => $validated['name'],
            'slug'     => $slug,
            'ruc'      => $validated['ruc'] ?? null,
            'type'     => $validated['type'],
            'status'   => BusinessStatus::Active,
        ]);

        // Crear relación owner en business_members
        $business->businessMembers()->create([
            'user_id'   => $request->user()->id,
            'role'      => BusinessMemberRole::Owner,
            'joined_at' => now(),
        ]);

        // Vincular módulos seleccionados
        if (!empty($validated['modules'])) {
            $moduleMap = PlatformModule::whereIn('slug', $validated['modules'])
                ->pluck('id', 'slug');

            $syncData = [];
            foreach ($validated['modules'] as $i => $modSlug) {
                if ($id = $moduleMap[$modSlug] ?? null) {
                    $syncData[$id] = ['sort_order' => $i];
                }
            }
            $business->modules()->sync($syncData);
        }

        return response()->json([
            'business' => $business,
            'message'  => 'Negocio creado exitosamente.',
        ], 201);
    }

    /** Obtener un negocio por slug con sus módulos instalados */
    public function show(Request $request, string $slug): JsonResponse
    {
        $user = $request->user();

        $business = Business::where('slug', $slug)
            ->where(function ($q) use ($user) {
                $q->where('owner_id', $user->id)
                  ->orWhereHas('businessMembers', fn ($q2) =>
                      $q2->where('user_id', $user->id)
                  );
            })
            ->with('modules:id,slug,name')
            ->firstOrFail();

        return response()->json([
            'business' => array_merge($business->toArray(), [
                'module_slugs' => $business->modules->pluck('slug')->toArray(),
            ]),
        ]);
    }
}
