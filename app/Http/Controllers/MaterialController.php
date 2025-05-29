<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $materiais = Material::orderBy("nome")->get();
        return view("materiais.index", compact("materiais"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("materiais.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nome" => "required|string|max:255|unique:materials,nome",
            "tipo" => "required|string|max:255",
            "preco_m2" => "required|numeric|min:0",
            "espessura_mm" => "required|numeric|min:0",
        ]);

        $material = Material::create($request->all());

        return redirect()->route("materiais.index")->with("success", "Material ".$material->nome." criado com sucesso.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        return view("materiais.show", compact("material"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        return view("materiais.edit", compact("material"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $request->validate([
            "nome" => "required|string|max:255|unique:materials,nome," . $material->id,
            "tipo" => "required|string|max:255",
            "preco_m2" => "required|numeric|min:0",
            "espessura_mm" => "required|numeric|min:0",
        ]);

        $material->update($request->all());

        return redirect()->route("materiais.index")->with("success", "Material ".$material->nome." atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        try {
            // Verifica se o material está sendo usado em algum item de orçamento
            if ($material->orcamentoItems()->exists()) {
                return redirect()->route("materiais.index")->with("error", "Não é possível remover o material ".$material->nome." pois ele está sendo utilizado em orçamentos.");
            }

            $nomeMaterial = $material->nome;
            $material->delete();
            return redirect()->route("materiais.index")->with("success", "Material ".$nomeMaterial." removido com sucesso.");
        } catch (\Exception $e) {
            // Log::error("Erro ao remover material: " . $e->getMessage());
            return redirect()->route("materiais.index")->with("error", "Erro ao remover o material. Tente novamente.");
        }
    }
}

