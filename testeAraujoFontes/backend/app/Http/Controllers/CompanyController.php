<?php

namespace App\Http\Controllers;

use App\Models\Company;
use App\Models\Sector;
use Illuminate\Http\Request;

class CompanyController extends Controller
{
    public function index(Request $request)
    {
        $perPage = $request->input('perPage', 10);
        $search = $request->input('search');

        $companies = Company::query()
            ->with('sectors')
            ->when($search, function ($query) use ($search) {
                $query->where('nome', 'like', "%{$search}%");
            })
            ->orderBy('nome')
            ->paginate($perPage);

        return response()->json($companies);
    }

    public function store(Request $request)
    {
        $request->validate([
            'nome' => 'required',
            'cnpj' => 'required|unique:companies',
            'setores' => 'required|array',
            'setores.*' => 'exists:sectors,id',
        ]);

        $company = Company::create($request->only('nome', 'cnpj'));
        $company->sectors()->attach($request->input('setores'));

        return response()->json(['message' => 'Empresa cadastrada com sucesso.']);
    }

    public function show($id)
    {
        $company = Company::with('sectors')->findOrFail($id);

        return response()->json($company);
    }

    public function update(Request $request, $id)
    {
        $company = Company::findOrFail($id);

        $request->validate([
            'nome' => 'required',
            'cnpj' => 'required|unique:companies,cnpj,' . $id,
            'setores' => 'required|array',
            'setores.*' => 'exists:sectors,id',
        ]);

        $company->update($request->only('nome', 'cnpj'));
        $company->sectors()->sync($request->input('setores'));

        return response()->json(['message' => 'Empresa atualizada com sucesso.']);
    }

    public function destroy($id)
    {
        $company = Company::findOrFail($id);
        $company->delete();

        return response()->json(['message' => 'Empresa excluída com sucesso.']);
    }
}
