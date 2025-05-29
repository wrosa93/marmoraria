<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use Illuminate\Http\Request;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $clientes = Cliente::orderBy("nome")->get();
        return view("clientes.index", compact("clientes"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view("clientes.create");
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "nome" => "required|string|max:255",
            "email" => "nullable|email|max:255|unique:clientes,email",
            "telefone" => "nullable|string|max:20",
        ]);

        $cliente = Cliente::create($request->all());

        return redirect()->route("clientes.index")->with("success", "Cliente ".$cliente->nome." criado com sucesso.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Cliente $cliente)
    {
        $cliente->load("orcamentos"); // Carrega os orçamentos relacionados
        return view("clientes.show", compact("cliente"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Cliente $cliente)
    {
        return view("clientes.edit", compact("cliente"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Cliente $cliente)
    {
        $request->validate([
            "nome" => "required|string|max:255",
            // Validação unique ignorando o próprio cliente
            "email" => "nullable|email|max:255|unique:clientes,email," . $cliente->id,
            "telefone" => "nullable|string|max:20",
        ]);

        $cliente->update($request->all());

        return redirect()->route("clientes.index")->with("success", "Cliente ".$cliente->nome." atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Cliente $cliente)
    {
        try {
            // Tenta deletar o cliente. Se houver orçamentos associados com restrição,
            // a exclusão falhará devido à constraint do banco de dados (onDelete("cascade") na migration de orcamentos)
            // Se a constraint fosse RESTRICT, precisaria tratar a exceção.
            $nomeCliente = $cliente->nome;
            $cliente->delete();
            return redirect()->route("clientes.index")->with("success", "Cliente ".$nomeCliente." removido com sucesso.");
        } catch (\Illuminate\Database\QueryException $e) {
            // Se houver uma restrição de chave estrangeira (caso onDelete não seja cascade)
            // Pode adicionar uma mensagem de erro mais específica
            return redirect()->route("clientes.index")->with("error", "Não foi possível remover o cliente pois ele possui orçamentos associados.");
        }
    }
}

