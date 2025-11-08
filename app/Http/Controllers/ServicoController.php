<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $busca = $request->string('q')->trim();

        $servicos = Servico::query()
            ->with(['prices' => function ($query) {
                $query->orderByDesc('data_inicio');
            }])
            ->when($busca, function ($query, $busca) {
                $query->where('nome', 'like', "%{$busca}%")
                    ->orWhere('codigo', 'like', "%{$busca}%")
                    ->orWhere('tipo_cobranca', 'like', "%{$busca}%");
            })
            ->orderBy('nome')
            ->paginate(15)
            ->withQueryString();

        return view('servicos.index', compact('servicos', 'busca'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("servicos.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $dados = $request->validate([
            'codigo' => 'required|string|max:30|unique:servicos,codigo',
            'nome' => 'required|string|max:120',
            'tipo_cobranca' => 'required|string|in:area,perimetro,peca,personalizado',
            'unidade_medida' => 'required|string|max:10',
            'ativo' => 'nullable|boolean',
            'descricao' => 'nullable|string',
            'preco' => 'required|numeric|min:0',
            'data_inicio_preco' => 'required|date',
            'observacao_preco' => 'nullable|string|max:255',
        ]);

        $servico = Servico::create([
            'codigo' => $dados['codigo'],
            'nome' => $dados['nome'],
            'tipo_cobranca' => $dados['tipo_cobranca'],
            'unidade_medida' => $dados['unidade_medida'],
            'ativo' => $dados['ativo'] ?? true,
            'descricao' => $dados['descricao'] ?? null,
        ]);

        $servico->prices()->create([
            'data_inicio' => $dados['data_inicio_preco'],
            'preco' => $dados['preco'],
            'observacao' => $dados['observacao_preco'] ?? null,
            'moeda' => 'BRL',
        ]);

        return redirect()
            ->route('servicos.show', $servico)
            ->with('success', 'Serviço criado com sucesso com preço vigente.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Servico $servico)
    {
        $servico->load(['prices' => function ($query) {
            $query->orderByDesc('data_inicio');
        }]);

        return view('servicos.show', compact('servico'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servico $servico)
    {
        $servico->load(['prices' => function ($query) {
            $query->orderByDesc('data_inicio');
        }]);

        return view('servicos.edit', compact('servico'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servico $servico)
    {
        $dados = $request->validate([
            'codigo' => 'required|string|max:30|unique:servicos,codigo,' . $servico->id,
            'nome' => 'required|string|max:120',
            'tipo_cobranca' => 'required|string|in:area,perimetro,peca,personalizado',
            'unidade_medida' => 'required|string|max:10',
            'ativo' => 'nullable|boolean',
            'descricao' => 'nullable|string',
            'preco' => 'nullable|numeric|min:0|required_with:data_inicio_preco',
            'data_inicio_preco' => 'nullable|date|required_with:preco',
            'observacao_preco' => 'nullable|string|max:255',
        ]);

        $servico->update([
            'codigo' => $dados['codigo'],
            'nome' => $dados['nome'],
            'tipo_cobranca' => $dados['tipo_cobranca'],
            'unidade_medida' => $dados['unidade_medida'],
            'ativo' => $dados['ativo'] ?? false,
            'descricao' => $dados['descricao'] ?? null,
        ]);

        if (! empty($dados['preco']) && ! empty($dados['data_inicio_preco'])) {
            $existe = $servico->prices()
                ->whereDate('data_inicio', $dados['data_inicio_preco'])
                ->exists();

            if ($existe) {
                return back()
                    ->withInput()
                    ->withErrors(['data_inicio_preco' => 'Já existe um preço para esta data.']);
            }

            $servico->prices()->create([
                'data_inicio' => $dados['data_inicio_preco'],
                'preco' => $dados['preco'],
                'observacao' => $dados['observacao_preco'] ?? null,
                'moeda' => 'BRL',
            ]);
        }

        return redirect()
            ->route('servicos.show', $servico)
            ->with('success', "Serviço {$servico->nome} atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servico $servico)
    {
        try {
            if ($servico->pecaServicos()->exists()) {
                return redirect()
                    ->route('servicos.index')
                    ->with('error', "Não é possível remover o serviço {$servico->nome} pois ele está em uso em orçamentos.");
            }

            $nomeServico = $servico->nome;
            $servico->prices()->delete();
            $servico->delete();

            return redirect()
                ->route('servicos.index')
                ->with('success', "Serviço {$nomeServico} removido com sucesso.");
        } catch (\Exception $e) {
            return redirect()
                ->route('servicos.index')
                ->with('error', 'Erro ao remover o serviço. Tente novamente.');
        }
    }
}

