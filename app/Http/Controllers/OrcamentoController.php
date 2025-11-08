<?php

namespace App\Http\Controllers;

use App\Models\Cliente;
use App\Models\Material;
use App\Models\Orcamento;
use App\Models\OrcamentoLocal;
use App\Models\OrcamentoPeca;
use App\Models\OrcamentoPecaServico;
use App\Models\PaymentMethod;
use App\Models\Servico;
use App\Services\OrcamentoCalculator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrcamentoController extends Controller
{
    private const STATUSES = ['rascunho', 'enviado', 'aprovado', 'reprovado', 'cancelado'];

    public function __construct(private readonly OrcamentoCalculator $calculator)
    {
    }

    public function index(Request $request)
    {
        $status = $request->string('status')->trim();
        $busca = $request->string('q')->trim();

        $orcamentos = Orcamento::query()
            ->with(['cliente', 'paymentMethod'])
            ->when($status && in_array($status, self::STATUSES, true), function ($query) use ($status) {
                $query->where('status', $status);
            })
            ->when($busca, function ($query, $busca) {
                $query->whereHas('cliente', function ($subQuery) use ($busca) {
                    $subQuery->where('nome', 'like', "%{$busca}%")
                        ->orWhere('documento', 'like', "%{$busca}%");
                })
                ->orWhere('numero', 'like', "%{$busca}%");
            })
            ->orderByDesc('data')
            ->orderByDesc('id')
            ->paginate(15)
            ->withQueryString();

        return view('orcamentos.index', [
            'orcamentos' => $orcamentos,
            'statusAtual' => $status,
            'busca' => $busca,
            'statuses' => self::STATUSES,
        ]);
    }

    public function create()
    {
        return view('orcamentos.create', [
            'clientes' => Cliente::orderBy('nome')->get(),
            'paymentMethods' => PaymentMethod::where('ativo', true)->orderBy('nome')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function store(Request $request)
    {
        $dados = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data' => 'required|date',
            'data_validade' => 'nullable|date|after_or_equal:data',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'status' => 'required|in:' . implode(',', self::STATUSES),
            'desconto_tipo' => 'required|in:nenhum,percentual,valor',
            'desconto_percentual' => 'nullable|numeric|min:0|max:100',
            'desconto_valor' => 'nullable|numeric|min:0',
            'acrescimo_valor' => 'nullable|numeric|min:0',
            'condicoes_pagamento' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'responsavel' => 'nullable|string|max:120',
        ]);

        $this->ajustarValoresDeDesconto($dados);

        $orcamento = Orcamento::create([
            'cliente_id' => $dados['cliente_id'],
            'payment_method_id' => $dados['payment_method_id'] ?? null,
            'data' => $dados['data'],
            'data_validade' => $dados['data_validade'] ?? null,
            'status' => $dados['status'],
            'desconto_tipo' => $dados['desconto_tipo'],
            'desconto_percentual' => $dados['desconto_percentual'] ?? 0,
            'desconto_valor' => $dados['desconto_valor'] ?? 0,
            'acrescimo_valor' => $dados['acrescimo_valor'] ?? 0,
            'condicoes_pagamento' => $dados['condicoes_pagamento'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null,
            'responsavel' => $dados['responsavel'] ?? null,
        ]);

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return redirect()
            ->route('orcamentos.edit', $orcamento)
            ->with('success', 'Orçamento criado. Agora adicione ambientes, peças e serviços.');
    }

    public function show(Orcamento $orcamento)
    {
        $orcamento->load([
            'cliente',
            'paymentMethod',
            'locais.pecas.material',
            'locais.pecas.servicos.servico',
        ]);

        return view('orcamentos.show', [
            'orcamento' => $orcamento,
            'statuses' => self::STATUSES,
        ]);
    }

    public function edit(Orcamento $orcamento)
    {
        $orcamento->load([
            'cliente',
            'paymentMethod',
            'locais.pecas.material',
            'locais.pecas.servicos.servico',
        ]);

        return view('orcamentos.edit', [
            'orcamento' => $orcamento,
            'clientes' => Cliente::orderBy('nome')->get(),
            'paymentMethods' => PaymentMethod::where('ativo', true)->orderBy('nome')->get(),
            'materiais' => Material::where('ativo', true)->orderBy('nome')->get(),
            'servicos' => Servico::where('ativo', true)->orderBy('nome')->get(),
            'statuses' => self::STATUSES,
        ]);
    }

    public function update(Request $request, Orcamento $orcamento)
    {
        $dados = $request->validate([
            'cliente_id' => 'required|exists:clientes,id',
            'data' => 'required|date',
            'data_validade' => 'nullable|date|after_or_equal:data',
            'payment_method_id' => 'nullable|exists:payment_methods,id',
            'status' => 'required|in:' . implode(',', self::STATUSES),
            'desconto_tipo' => 'required|in:nenhum,percentual,valor',
            'desconto_percentual' => 'nullable|numeric|min:0|max:100',
            'desconto_valor' => 'nullable|numeric|min:0',
            'acrescimo_valor' => 'nullable|numeric|min:0',
            'condicoes_pagamento' => 'nullable|string',
            'observacoes' => 'nullable|string',
            'responsavel' => 'nullable|string|max:120',
        ]);

        $this->ajustarValoresDeDesconto($dados);

        $orcamento->update([
            'cliente_id' => $dados['cliente_id'],
            'payment_method_id' => $dados['payment_method_id'] ?? null,
            'data' => $dados['data'],
            'data_validade' => $dados['data_validade'] ?? null,
            'status' => $dados['status'],
            'desconto_tipo' => $dados['desconto_tipo'],
            'desconto_percentual' => $dados['desconto_percentual'] ?? 0,
            'desconto_valor' => $dados['desconto_valor'] ?? 0,
            'acrescimo_valor' => $dados['acrescimo_valor'] ?? 0,
            'condicoes_pagamento' => $dados['condicoes_pagamento'] ?? null,
            'observacoes' => $dados['observacoes'] ?? null,
            'responsavel' => $dados['responsavel'] ?? null,
            'data_base_precos' => $orcamento->data_base_precos ?? $dados['data'],
        ]);

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Orçamento atualizado com sucesso.');
    }

    public function destroy(Orcamento $orcamento)
    {
        $numero = $orcamento->numero;
        $orcamento->delete();

        return redirect()
            ->route('orcamentos.index')
            ->with('success', "Orçamento {$numero} removido com sucesso.");
    }

    public function storeLocal(Request $request, Orcamento $orcamento)
    {
        $dados = $request->validate([
            'nome' => 'required|string|max:120',
            'ordem' => 'nullable|integer|min:1',
            'observacoes' => 'nullable|string',
        ]);

        $ordem = $dados['ordem'] ?? ($orcamento->locais()->max('ordem') + 1);

        $orcamento->locais()->create([
            'nome' => $dados['nome'],
            'ordem' => $ordem,
            'observacoes' => $dados['observacoes'] ?? null,
        ]);

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Ambiente adicionado ao orçamento.');
    }

    public function destroyLocal(Orcamento $orcamento, OrcamentoLocal $local)
    {
        abort_unless($local->orcamento_id === $orcamento->id, 404);

        $local->delete();

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Ambiente removido do orçamento.');
    }

    public function storePiece(Request $request, Orcamento $orcamento, OrcamentoLocal $local)
    {
        abort_unless($local->orcamento_id === $orcamento->id, 404);

        $dados = $request->validate([
            'material_id' => 'required|exists:materials,id',
            'identificador' => 'nullable|string|max:120',
            'largura_mm' => 'required|numeric|min:1',
            'comprimento_mm' => 'required|numeric|min:1',
            'espessura_mm' => 'nullable|numeric|min:0',
            'quantidade' => 'required|integer|min:1',
            'custos_extras' => 'nullable|numeric|min:0',
            'observacoes' => 'nullable|string',
        ]);

        $material = Material::findOrFail($dados['material_id']);
        $precoMaterial = $material->currentPriceForDate($orcamento->data_base_precos ?? $orcamento->data);

        if (! $precoMaterial) {
            return back()
                ->withInput()
                ->withErrors(['material_id' => 'Não há preço vigente para este material na data-base do orçamento.']);
        }

        $larguraM = $dados['largura_mm'] / 1000;
        $comprimentoM = $dados['comprimento_mm'] / 1000;
        $area = round($larguraM * $comprimentoM, 3);
        $perimetro = round(2 * ($larguraM + $comprimentoM), 3);
        $quantidade = $dados['quantidade'];

        $precoMaterialTotal = round($area * $precoMaterial->preco_m2 * $quantidade, 2);
        $custosExtras = $dados['custos_extras'] ?? 0;

        DB::transaction(function () use (
            $local,
            $dados,
            $precoMaterial,
            $area,
            $perimetro,
            $precoMaterialTotal,
            $custosExtras
        ) {
            $local->pecas()->create([
                'material_id' => $dados['material_id'],
                'material_price_id' => $precoMaterial->id,
                'identificador' => $dados['identificador'] ?? null,
                'largura_mm' => $dados['largura_mm'],
                'comprimento_mm' => $dados['comprimento_mm'],
                'espessura_mm' => $dados['espessura_mm'] ?? null,
                'quantidade' => $dados['quantidade'],
                'area_m2' => $area,
                'perimetro_ml' => $perimetro,
                'preco_material_unitario' => $precoMaterial->preco_m2,
                'preco_material_total' => $precoMaterialTotal,
                'preco_servico_total' => 0,
                'custos_extras' => $custosExtras,
                'total' => $precoMaterialTotal + $custosExtras,
                'observacoes' => $dados['observacoes'] ?? null,
            ]);
        });

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Peça adicionada ao ambiente.');
    }

    public function destroyPiece(Orcamento $orcamento, OrcamentoPeca $peca)
    {
        abort_unless($peca->local?->orcamento_id === $orcamento->id, 404);

        $peca->delete();

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Peça removida do orçamento.');
    }

    public function storePieceService(Request $request, Orcamento $orcamento, OrcamentoPeca $peca)
    {
        abort_unless($peca->local?->orcamento_id === $orcamento->id, 404);

        $dados = $request->validate([
            'servico_id' => 'required|exists:servicos,id',
            'quantidade' => 'required|numeric|min:0.01',
            'preco_unitario' => 'nullable|numeric|min:0',
            'descricao' => 'nullable|string|max:255',
            'tipo_cobranca' => 'nullable|string|max:30',
        ]);

        $servico = Servico::findOrFail($dados['servico_id']);
        $precoServico = $servico->currentPriceForDate($orcamento->data_base_precos ?? $orcamento->data);

        if (! $precoServico && empty($dados['preco_unitario'])) {
            return back()
                ->withInput()
                ->withErrors(['servico_id' => 'Não há preço vigente para este serviço e nenhum valor foi informado.']);
        }

        $precoUnitario = $dados['preco_unitario'] ?? $precoServico->preco;
        $total = round($precoUnitario * $dados['quantidade'], 2);

        DB::transaction(function () use ($peca, $servico, $precoServico, $dados, $precoUnitario, $total) {
            $peca->servicos()->create([
                'servico_id' => $servico->id,
                'servico_price_id' => $precoServico?->id,
                'descricao' => $dados['descricao'] ?? $servico->nome,
                'tipo_cobranca' => $dados['tipo_cobranca'] ?? $servico->tipo_cobranca,
                'quantidade' => $dados['quantidade'],
                'preco_unitario' => $precoUnitario,
                'total' => $total,
            ]);
        });

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Serviço vinculado à peça.');
    }

    public function destroyPieceService(Orcamento $orcamento, OrcamentoPecaServico $servico)
    {
        abort_unless($servico->peca?->local?->orcamento_id === $orcamento->id, 404);

        $servico->delete();

        $this->calculator->recalcular($orcamento->fresh('locais.pecas.servicos'));

        return back()->with('success', 'Serviço removido da peça.');
    }

    public function relatorioForm()
    {
        return view('relatorios.orcamentos.form', [
            'statuses' => self::STATUSES,
        ]);
    }

    public function buscarRelatorio(Request $request)
    {
        $dados = $request->validate([
            'numero' => 'nullable|string|max:25',
            'nome_cliente' => 'nullable|string|max:255',
            'status' => 'nullable|in:' . implode(',', self::STATUSES),
            'data_inicio' => 'nullable|date',
            'data_fim' => 'nullable|date|after_or_equal:data_inicio',
        ]);

        $query = Orcamento::with('cliente');

        if (! empty($dados['numero'])) {
            $query->where('numero', 'like', '%' . $dados['numero'] . '%');
        }

        if (! empty($dados['nome_cliente'])) {
            $query->whereHas('cliente', function ($subQuery) use ($dados) {
                $subQuery->where('nome', 'like', '%' . $dados['nome_cliente'] . '%');
            });
        }

        if (! empty($dados['status'])) {
            $query->where('status', $dados['status']);
        }

        if (! empty($dados['data_inicio'])) {
            $query->whereDate('data', '>=', $dados['data_inicio']);
        }

        if (! empty($dados['data_fim'])) {
            $query->whereDate('data', '<=', $dados['data_fim']);
        }

        $orcamentos = $query
            ->orderByDesc('data')
            ->orderByDesc('id')
            ->paginate(20)
            ->withQueryString();

        return view('relatorios.orcamentos.form', [
            'statuses' => self::STATUSES,
            'orcamentos' => $orcamentos,
            'input' => $dados,
        ]);
    }

    public function gerarPdf(Orcamento $orcamento)
    {
        $orcamento->load([
            'cliente',
            'paymentMethod',
            'locais.pecas.material',
            'locais.pecas.servicos.servico',
        ]);

        $html = view('orcamentos.pdf', compact('orcamento'))->render();

        $htmlFilePath = tempnam(sys_get_temp_dir(), 'orcamento_html_') . '.html';
        $pdfFilePath = tempnam(sys_get_temp_dir(), 'orcamento_pdf_') . '.pdf';

        file_put_contents($htmlFilePath, $html);

        $command = sprintf(
            'weasyprint %s %s',
            escapeshellarg($htmlFilePath),
            escapeshellarg($pdfFilePath)
        );

        $output = null;
        $status = null;
        exec($command, $output, $status);

        unlink($htmlFilePath);

        if ($status === 0 && file_exists($pdfFilePath)) {
            return response()
                ->download($pdfFilePath, "orcamento_{$orcamento->numero}.pdf")
                ->deleteFileAfterSend(true);
        }

        if (file_exists($pdfFilePath)) {
            unlink($pdfFilePath);
        }

        return back()->with('error', 'Erro ao gerar o PDF do orçamento.');
    }

    private function ajustarValoresDeDesconto(array &$dados): void
    {
        $tipo = $dados['desconto_tipo'];

        if ($tipo === 'percentual') {
            $dados['desconto_valor'] = 0;
        }

        if ($tipo === 'valor') {
            $dados['desconto_percentual'] = 0;
        }

        if ($tipo === 'nenhum') {
            $dados['desconto_percentual'] = 0;
            $dados['desconto_valor'] = 0;
        }
    }
}
