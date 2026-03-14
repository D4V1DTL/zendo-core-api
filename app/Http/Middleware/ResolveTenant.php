<?php

namespace App\Http\Middleware;

use App\Models\Tenant;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ResolveTenant
{
    public function handle(Request $request, Closure $next): Response
    {
        $tenantId = null;

        // 1. From route parameter: /api/tenants/{tenant}/...
        if ($request->route('tenant')) {
            $tenant = $request->route('tenant');
            if ($tenant instanceof Tenant) {
                $tenantId = $tenant->id;
            } elseif (is_numeric($tenant)) {
                $tenantId = (int) $tenant;
            }
        }

        // 2. From header: X-Tenant-ID
        if (! $tenantId && $request->hasHeader('X-Tenant-ID')) {
            $tenantId = (int) $request->header('X-Tenant-ID');
        }

        // 3. From query param: ?tenant_id=1
        if (! $tenantId && $request->query('tenant_id')) {
            $tenantId = (int) $request->query('tenant_id');
        }

        if (! $tenantId) {
            return response()->json(['message' => 'Tenant not specified.'], 400);
        }

        // Verify user has access to this tenant
        $user = $request->user();
        if ($user) {
            $hasAccess = $user->tenants()->where('tenant_id', $tenantId)->exists()
                || $user->ownedTenants()->where('id', $tenantId)->exists();

            if (! $hasAccess) {
                return response()->json(['message' => 'Access denied to this business.'], 403);
            }
        }

        // Verify tenant exists and is active
        $tenant = Tenant::find($tenantId);
        if (! $tenant || $tenant->status !== 'active') {
            return response()->json(['message' => 'Business not found or inactive.'], 404);
        }

        // Bind tenant to the container (used by BelongsToTenant trait)
        app()->instance('current_tenant_id', $tenantId);
        app()->instance('current_tenant', $tenant);

        // Make it available in the request
        $request->merge(['_tenant_id' => $tenantId]);

        return $next($request);
    }
}
