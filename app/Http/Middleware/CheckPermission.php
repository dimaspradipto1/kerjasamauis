<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;
use App\Models\RolePermission;

class CheckPermission
{
    /**
     * Mapping route → module name.
     * Key: route prefix atau nama resource, Value: nama modul di role_permissions
     */
    protected array $moduleMap = [
        'user'              => 'user',
        'mitra'             => 'mitra',
        'kerjasama'         => 'kerjasama',
        'kegiatan'          => 'kegiatan',
        'unit-kerja'        => 'unit_kerja',
        'bentuk-kegiatan'   => 'bentuk_kegiatan',
        'sasaran-kinerja'   => 'sasaran_kinerja',
        'kriteria-mitra'    => 'kriteria_mitra',
        'sumber-dana'       => 'sumber_dana',
        'jenis-dokumen'     => 'jenis_dokumen',
        'laporan-kerjasama' => 'laporan',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  Closure(Request): (Response)  $next
     */
    public function handle(Request $request, Closure $next, string $module): Response
    {
        $user = Auth::user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Superadmin selalu punya akses penuh
        if ($user->roles === 'superadmin') {
            return $next($request);
        }

        // Tentukan aksi berdasarkan method HTTP dan nama route
        $action = $this->resolveAction($request);

        if (!$user->hasPermission($module, $action)) {
            if ($request->expectsJson()) {
                return response()->json(['message' => 'Anda tidak memiliki izin untuk melakukan aksi ini.'], 403);
            }

            return redirect()->back()->with('error', 'Anda tidak memiliki izin untuk melakukan aksi ini pada modul ' . $this->getModuleLabel($module) . '.');
        }

        return $next($request);
    }

    /**
     * Tentukan aksi berdasarkan HTTP method dan route action.
     */
    protected function resolveAction(Request $request): string
    {
        $method = $request->method();
        $routeAction = $request->route()?->getActionMethod() ?? '';

        return match (true) {
            in_array($method, ['POST'])                        => 'can_create',
            in_array($method, ['PUT', 'PATCH'])                => 'can_update',
            in_array($method, ['DELETE'])                      => 'can_delete',
            in_array($routeAction, ['create', 'store'])        => 'can_create',
            in_array($routeAction, ['edit', 'update'])         => 'can_update',
            in_array($routeAction, ['destroy'])                => 'can_delete',
            default                                            => 'can_read',
        };
    }

    /**
     * Ambil label modul yang mudah dibaca.
     */
    protected function getModuleLabel(string $module): string
    {
        return RolePermission::MODULES[$module] ?? $module;
    }
}
