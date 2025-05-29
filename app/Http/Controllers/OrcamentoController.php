<?php

namespace App\Http\Controllers;

use App\Models\Orcamento;
use App\Models\OrcamentoItem;
use App\Models\Cliente;
use App\Models\Material;
use App\Models\Servico;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; // Import DB facade for transactions
// If using WeasyPrint via exec, no need for direct use statement
// use WeasyPrint\WeasyPrint;

class OrcamentoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orcamentos = Orcamento::with("cliente", "items")->latest()->paginate(15); // Paginate and order by latest
        return view("orcamentos.index", compact("orcamentos"));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $clientes = Cliente::orderBy("nome")->get();
        return view("orcamentos.create", compact("clientes"));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            "cliente_id" => "required|exists:clientes,id",
            "data" => "required|date",
        ]);

        $orcamento = Orcamento::create($request->all());

        // Redirect to edit page to add items
        return redirect()->route("orcamentos.edit", $orcamento)->with("success", "Orçamento criado com sucesso. Adicione itens abaixo.");
    }

    /**
     * Display the specified resource.
     */
    public function show(Orcamento $orcamento)
    {
        $orcamento->load("cliente", "items.material", "items.servico");
        return view("orcamentos.show", compact("orcamento"));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Orcamento $orcamento)
    {
        $clientes = Cliente::orderBy("nome")->get();
        $materiais = Material::orderBy("nome")->get();
        $servicos = Servico::orderBy("descricao")->get();
        $orcamento->load("items.material", "items.servico");
        return view("orcamentos.edit", compact("orcamento", "clientes", "materiais", "servicos"));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Orcamento $orcamento)
    {
        $request->validate([
            "cliente_id" => "required|exists:clientes,id",
            "data" => "required|date",
        ]);

        $orcamento->update($request->only(["cliente_id", "data"])); // Only update basic info

        return redirect()->route("orcamentos.edit", $orcamento)->with("success", "Orçamento atualizado com sucesso.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Orcamento $orcamento)
    {
        // Itens são deletados em cascata devido à constraint no banco
        $orcamento->delete();

        return redirect()->route("orcamentos.index")->with("success", "Orçamento removido com sucesso.");
    }

    /**
     * Add an item to the specified budget.
     */
    public function addItem(Request $request, Orcamento $orcamento)
    {
        $request->validate([
            "material_id" => "required|exists:materials,id",
            "servico_id" => "required|exists:servicos,id",
            "quantidade_m2" => "required|numeric|min:0.01", // Ensure quantity is positive
            "descricao" => "nullable|string|max:255",
        ]);

        try {
            DB::beginTransaction();

            $material = Material::findOrFail($request->material_id);
            $servico = Servico::findOrFail($request->servico_id);

            // Calcular subtotal
            $subtotalMaterial = $material->preco_m2 * $request->quantidade_m2;
            $subtotalServico = $servico->preco_unitario * $request->quantidade_m2; // Assuming service price is also per m2
            $subtotal = $subtotalMaterial + $subtotalServico;

            $orcamento->items()->create([
                "material_id" => $request->material_id,
                "servico_id" => $request->servico_id,
                "quantidade_m2" => $request->quantidade_m2,
                "descricao" => $request->descricao,
                "subtotal" => $subtotal,
            ]);

            DB::commit();

            return redirect()->route("orcamentos.edit", $orcamento)->with("success", "Item adicionado ao orçamento.");

        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error("Erro ao adicionar item ao orçamento: " . $e->getMessage());
            return redirect()->route("orcamentos.edit", $orcamento)->with("error", "Erro ao adicionar item ao orçamento. Tente novamente.");
        }
    }

    /**
     * Remove an item from the specified budget.
     */
    public function removeItem(Orcamento $orcamento, OrcamentoItem $item)
    {
        // Verifica se o item pertence ao orçamento
        if ($item->orcamento_id !== $orcamento->id) {
             return redirect()->route("orcamentos.edit", $orcamento)->with("error", "Item inválido ou não pertence a este orçamento.");
        }

        try {
            DB::beginTransaction();
            $item->delete();
            DB::commit();
            return redirect()->route("orcamentos.edit", $orcamento)->with("success", "Item removido do orçamento.");
        } catch (\Exception $e) {
            DB::rollBack();
            // Log::error("Erro ao remover item do orçamento: " . $e->getMessage());
            return redirect()->route("orcamentos.edit", $orcamento)->with("error", "Erro ao remover item do orçamento. Tente novamente.");
        }
    }

    // --- Métodos do Relatório e PDF --- //

    /**
     * Show the form for the budget report.
     */
    public function relatorioForm()
    {
        return view("relatorios.orcamentos.form");
    }

    /**
     * Search and display the budget report based on filters.
     */
    public function buscarRelatorio(Request $request)
    {
        $query = Orcamento::with("cliente"); // Eager load cliente

        // Filter by Budget Number (ID)
        if ($request->filled("numero")) {
            $query->where("id", $request->numero);
        }

        // Filter by Client Name
        if ($request->filled("nome_cliente")) {
            $query->whereHas("cliente", function ($q) use ($request) {
                $q->where("nome", "like", "%" . $request->nome_cliente . "%");
            });
        }

        // Filter by Date Range
        if ($request->filled("data_inicio")) {
            $query->whereDate("data", ">=", $request->data_inicio);
        }
        if ($request->filled("data_fim")) {
            $query->whereDate("data", "<=", $request->data_fim);
        }

        $orcamentos = $query->orderBy("data", "desc")->orderBy("id", "desc")->paginate(15); // Paginate results

        // Return the same view with results and input data
        return view("relatorios.orcamentos.form", [
            "orcamentos" => $orcamentos,
            "input" => $request->all() // Pass input back to repopulate form
        ]);
    }

    /**
     * Generate PDF for the specified budget.
     */
    public function gerarPdf(Orcamento $orcamento)
    {
        // Load necessary relationships
        $orcamento->load("cliente", "items.material", "items.servico");

        // Render the Blade view for the PDF content
        $html = view("orcamentos.pdf", compact("orcamento"))->render();

        // Use WeasyPrint to generate the PDF
        try {
            // Create a temporary file for the HTML content
            $htmlFilePath = tempnam(sys_get_temp_dir(), "orcamento_html_") . ".html";
            file_put_contents($htmlFilePath, $html);

            // Create a temporary file for the PDF output
            $pdfFilePath = tempnam(sys_get_temp_dir(), "orcamento_pdf_") . ".pdf";

            // Build the WeasyPrint command
            $command = sprintf(
                "weasyprint %s %s",
                escapeshellarg($htmlFilePath),
                escapeshellarg($pdfFilePath)
            );

            // Execute the command
            $output = null;
            $return_var = null;
            exec($command, $output, $return_var);

            // Clean up the temporary HTML file
            unlink($htmlFilePath);

            // Check if PDF generation was successful
            if ($return_var === 0 && file_exists($pdfFilePath)) {
                // Return the PDF as a download
                return response()->download($pdfFilePath, "orcamento_" . $orcamento->id . ".pdf")->deleteFileAfterSend(true);
            } else {
                // Log error details if available
                // Log::error("WeasyPrint Error: " . implode("\n", $output));
                if (file_exists($pdfFilePath)) {
                    unlink($pdfFilePath);
                }
                return redirect()->back()->with("error", "Erro ao gerar o PDF do orçamento. Detalhes: " . implode(" ", $output));
            }
        } catch (\Exception $e) {
            // Log::error("Erro geral ao gerar PDF: " . $e->getMessage());
            if (isset($htmlFilePath) && file_exists($htmlFilePath)) unlink($htmlFilePath);
            if (isset($pdfFilePath) && file_exists($pdfFilePath)) unlink($pdfFilePath);
            return redirect()->back()->with("error", "Erro inesperado ao gerar o PDF do orçamento.");
        }
    }

} // End of OrcamentoController class
