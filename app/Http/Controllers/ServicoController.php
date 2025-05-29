<?php

namespace App\Http\Controllers;

use App\Models\Servico;
use Illuminate\Http\Request;

class ServicoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $servicos = Servico::orderBy("descricao")->get();
        return view("servicos.index", compact("servicos"));
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
        $request->validate([
            "descricao" => "required|string|max:255|unique:servicos,descricao",
            "preco_unitario" => "required|numeric|min:0",
        ]);

        $servico = Servico::create($request->all());

        return redirect()->route("servicos.index")->with("success", "Serviço ".$servico->descricao." criado com sucesso.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Servico $servico)
    {
        return view("servicos.show", compact("servico"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Servico $servico)
    {
        return view("servicos.edit", compact("servico"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Servico $servico)
    {
        $request->validate([
            "descricao" => "required|string|max:255|unique:servicos,descricao," . $servico->id,
            "preco_unitario" => "required|numeric|min:0",
        ]);

        $servico->update($request->all());

        return redirect()->route("servicos.index")->with("success", "Serviço ".$servico->descricao." atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Servico $servico)
    {
       try {
            // Verifica se o serviço está sendo usado em algum item de orçamento
            if ($servico->orcamentoItems()->exists()) {
                return redirect()->route("servicos.index")->with("error", "Não é possível remover o serviço ".$servico->descricao." pois ele está sendo utilizado em orçamentos.");
            }

            $nomeServico = $servico->descricao;
            $servico->delete();
            return redirect()->route("servicos.index")->with("success", "Serviço ".$nomeServico." removido com sucesso.");
        } catch (\Exception $e) {
            // Log::error("Erro ao remover serviço: " . $e->getMessage());
            return redirect()->route("servicos.index")->with("error", "Erro ao remover o serviço. Tente novamente.");
        }
    }
}

