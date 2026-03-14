<?php

namespace App\Http\Controllers;

use App\Models\BusinessPreset;
use App\Models\PlatformModule;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class OnboardingController extends Controller
{
    private const PAGE_SIZE = 16;

    /**
     * GET /api/onboarding/presets
     * Lista las plantillas de negocio para el paso 2 del wizard.
     * El frontend hace match por slug para asignar imagen e icono.
     */
    public function presets(): JsonResponse
    {
        $presets = BusinessPreset::orderBy('id')
            ->get(['id', 'slug', 'name', 'description']);

        return response()->json($presets);
    }

    /**
     * GET /api/onboarding/presets/{preset}/modules
     *
     * Devuelve módulos para el paso 3.
     * Orden: primero los asociados a la plantilla (sort_order ASC),
     * luego los restantes (id ASC).
     *
     * Por defecto retorna solo los primeros 16.
     * Con ?all=true retorna todos (para "ver más").
     */
    public function modules(Request $request, BusinessPreset $preset): JsonResponse
    {
        $loadAll = $request->boolean('all', false);

        // IDs de módulos en la plantilla, ordenados por sort_order
        $presetModuleIds = $preset->modules()
            ->orderByPivot('sort_order')
            ->pluck('platform_modules.id')
            ->toArray();

        // Todos los módulos
        $allModules = PlatformModule::orderBy('id')->get();
        $total      = $allModules->count();

        // Separar: preset primero, luego el resto
        $presetModules  = $allModules->whereIn('id', $presetModuleIds)
            ->sortBy(fn ($m) => array_search($m->id, $presetModuleIds))
            ->values();

        $remainingModules = $allModules->whereNotIn('id', $presetModuleIds)->values();

        $ordered = $presetModules->concat($remainingModules);

        $hasMore = $total > self::PAGE_SIZE;

        if (!$loadAll) {
            $ordered = $ordered->take(self::PAGE_SIZE);
        }

        $modules = $ordered->map(fn ($m) => [
            'id'          => $m->id,
            'slug'        => $m->slug,
            'name'        => $m->name,
            'description' => $m->description,
            'is_free'     => $m->is_free,
            'is_preset'   => in_array($m->id, $presetModuleIds),
        ])->values();

        return response()->json([
            'modules'  => $modules,
            'has_more' => $hasMore && !$loadAll,
            'total'    => $total,
        ]);
    }

    /**
     * GET /api/onboarding/modules
     * Lista todos los módulos (para el caso "Otros" sin plantilla).
     */
    public function allModules(Request $request): JsonResponse
    {
        $loadAll = $request->boolean('all', false);
        $total   = PlatformModule::count();
        $hasMore = $total > self::PAGE_SIZE;

        $query = PlatformModule::orderBy('id');

        if (!$loadAll) {
            $query->limit(self::PAGE_SIZE);
        }

        $modules = $query->get()->map(fn ($m) => [
            'id'          => $m->id,
            'slug'        => $m->slug,
            'name'        => $m->name,
            'description' => $m->description,
            'is_free'     => $m->is_free,
            'is_preset'   => false,
        ])->values();

        return response()->json([
            'modules'  => $modules,
            'has_more' => $hasMore && !$loadAll,
            'total'    => $total,
        ]);
    }
}
