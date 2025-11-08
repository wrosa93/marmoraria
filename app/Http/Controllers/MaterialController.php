<?php

namespace App\Http\Controllers;

use App\Models\Material;
use Illuminate\Http\Request;

class MaterialController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busca = $request->string('q')->trim();

        $materiais = Material::query()
            ->with(['prices' => function ($query) {
                $query->orderByDesc('data_inicio');
            }])
            ->when($busca, function ($query, $busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('codigo', 'like', "%{$busca}%")
                    ->orWhere('tipo', 'like', "%{$busca}%");
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('materiais.index', compact('materiais', 'busca'));
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
        $dados = $request->validate([
            'codigo' => 'required|string|max:30|unique:materials,codigo',
            'nome' => 'required|string|max:120',
            'tipo' => 'nullable|string|max:120',
            'acabamento' => 'nullable|string|max:120',
            'cor' => 'nullable|string|max:120',
            'espessura_padrao_mm' => 'nullable|numeric|min:0',
            'ativo' => 'nullable|boolean',
            'descricao' => 'nullable|string',
            'preco_m2' => 'required|numeric|min:0',
            'data_inicio_preco' => 'required|date',
            'observacao_preco' => 'nullable|string|max:255',
        ]);

        $material = Material::create([
            'codigo' => $dados['codigo'],
            'nome' => $dados['nome'],
            'tipo' => $dados['tipo'] ?? null,
            'acabamento' => $dados['acabamento'] ?? null,
            'cor' => $dados['cor'] ?? null,
            'espessura_padrao_mm' => $dados['espessura_padrao_mm'] ?? null,
            'ativo' => $dados['ativo'] ?? true,
            'descricao' => $dados['descricao'] ?? null,
        ]);

        $material->prices()->create([
            'data_inicio' => $dados['data_inicio_preco'],
            'preco_m2' => $dados['preco_m2'],
            'observacao' => $dados['observacao_preco'] ?? null,
            'moeda' => 'BRL',
            'ativo' => true,
        ]);

        return redirect()
            ->route('materiais.show', $material)
            ->with('success', 'Material criado com sucesso com tabela de preço vigente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Material $material)
    {
        $material->load(['prices' => function ($query) {
            $query->orderByDesc('data_inicio');
        }]);

        return view('materiais.show', compact('material'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Material $material)
    {
        $material->load(['prices' => function ($query) {
            $query->orderByDesc('data_inicio');
        }]);

        return view('materiais.edit', compact('material'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Material $material)
    {
        $dados = $request->validate([
            'codigo' => 'required|string|max:30|unique:materials,codigo,' . $material->id,
            'nome' => 'required|string|max:120',
            'tipo' => 'nullable|string|max:120',
            'acabamento' => 'nullable|string|max:120',
            'cor' => 'nullable|string|max:120',
            'espessura_padrao_mm' => 'nullable|numeric|min:0',
            'ativo' => 'nullable|boolean',
            'descricao' => 'nullable|string',
            'preco_m2' => 'nullable|numeric|min:0|required_with:data_inicio_preco',
            'data_inicio_preco' => 'nullable|date|required_with:preco_m2',
            'observacao_preco' => 'nullable|string|max:255',
        ]);

        $material->update([
            'codigo' => $dados['codigo'],
            'nome' => $dados['nome'],
            'tipo' => $dados['tipo'] ?? null,
            'acabamento' => $dados['acabamento'] ?? null,
            'cor' => $dados['cor'] ?? null,
            'espessura_padrao_mm' => $dados['espessura_padrao_mm'] ?? null,
            'ativo' => $dados['ativo'] ?? false,
            'descricao' => $dados['descricao'] ?? null,
        ]);

        if (! empty($dados['preco_m2']) && ! empty($dados['data_inicio_preco'])) {
            $existePreco = $material->prices()
                ->whereDate('data_inicio', $dados['data_inicio_preco'])
                ->exists();

            if ($existePreco) {
                return back()
                    ->withInput()
                    ->withErrors(['data_inicio_preco' => 'Já existe um preço cadastrado para esta data de início.']);
            }

            $material->prices()->create([
                'data_inicio' => $dados['data_inicio_preco'],
                'preco_m2' => $dados['preco_m2'],
                'observacao' => $dados['observacao_preco'] ?? null,
                'moeda' => 'BRL',
                'ativo' => true,
            ]);
        }

        return redirect()
            ->route('materiais.show', $material)
            ->with('success', "Material {$material->nome} atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Material $material)
    {
        try {
            if ($material->pecas()->exists()) {
                return redirect()
                    ->route('materiais.index')
                    ->with('error', "Não é possível remover o material {$material->nome} pois ele está vinculado a peças de orçamento.");
            }

            $nomeMaterial = $material->nome;
            $material->prices()->delete();
            $material->delete();

            return redirect()
                ->route('materiais.index')
                ->with('success', "Material {$nomeMaterial} removido com sucesso.");
        } catch (\Exception $e) {
            return redirect()
                ->route('materiais.index')
                ->with('error', 'Erro ao remover o material. Tente novamente.');
        }
    }
}

