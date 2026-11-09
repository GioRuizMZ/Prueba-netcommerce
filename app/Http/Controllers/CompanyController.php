<?php

namespace App\Http\Controllers;

use App\Http\Resources\CompanyResource;
use App\Models\Company;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->query('search');

        $companies = Company::query()
            ->when($search, fn ($query) => $query->where('name', 'like', "%{$search}%"))
            ->with('tasks.user')
            ->get();

        return CompanyResource::collection($companies);
    }
}
