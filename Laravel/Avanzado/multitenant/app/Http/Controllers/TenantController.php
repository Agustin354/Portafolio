<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function store(Request $request)
    {
        $request->validate([
            'nombre'     => 'required|max:100',
            'subdominio' => 'required|alpha_dash|unique:tenants|max:50',
            'plan'       => 'required|in:free,pro,enterprise',
        ]);

        $tenant = Tenant::create($request->only('nombre', 'subdominio', 'plan'));
        return response()->json($tenant, 201);
    }

    public function show()
    {
        return response()->json(Tenant::actual());
    }
}
