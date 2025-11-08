<!DOCTYPE html>
<html lang="pt-BR">
    <head>
        <meta charset="UTF-8">
        <title>Orçamento {{ $orcamento->numero }}</title>
        <style>
            * { font-family: "DejaVu Sans", sans-serif; }
            body { font-size: 12px; color: #111827; }
            h1, h2, h3, h4 { margin: 0; }
            .header { display: flex; justify-content: space-between; align-items: center; border-bottom: 2px solid #111827; padding-bottom: 8px; margin-bottom: 16px; }
            .info-block { margin-bottom: 16px; }
            .info-block h3 { font-size: 14px; margin-bottom: 6px; text-transform: uppercase; color: #1f2937; }
            .grid { display: grid; grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 12px; }
            table { width: 100%; border-collapse: collapse; margin-top: 12px; }
            table thead { background-color: #e5e7eb; }
            table th, table td { border: 1px solid #9ca3af; padding: 6px; text-align: left; }
            .totals { display: flex; justify-content: flex-end; margin-top: 12px; }
            .totals table { width: auto; }
            .muted { color: #6b7280; }
            .badge { display: inline-block; padding: 2px 6px; border-radius: 12px; font-size: 10px; text-transform: uppercase; }
            .badge-r { background-color: #fee2e2; color: #b91c1c; }
            .badge-g { background-color: #dcfce7; color: #166534; }
            .badge-b { background-color: #dbeafe; color: #1d4ed8; }
            .badge-n { background-color: #e5e7eb; color: #374151; }
            .section { margin-bottom: 24px; }
        </style>
    </head>
    <body>
        <div class="header">
            <div>
                <h1>Orçamento {{ $orcamento->numero }}</h1>
                <p class="muted">Emitido em {{ $orcamento->data->format('d/m/Y') }}</p>
            </div>
            <div>
                @php
                    $badgeClass = match($orcamento->status) {
                        'aprovado' => 'badge badge-g',
                        'enviado' => 'badge badge-b',
                        'reprovado', 'cancelado' => 'badge badge-r',
                        default => 'badge badge-n'
                    };
                @endphp
                <span class="{{ $badgeClass }}">{{ strtoupper($orcamento->status) }}</span>
            </div>
        </div>

        <div class="section">
            <div class="info-block">
                <h3>Cliente</h3>
                <p><strong>{{ $orcamento->cliente->nome }}</strong></p>
                <p class="muted">
                    {{ $orcamento->cliente->email ?? 'Sem e-mail' }} • {{ $orcamento->cliente->telefone ?? 'Sem telefone' }}
                </p>
            </div>

            <div class="grid">
                <div class="info-block">
                    <h3>Pagamento</h3>
                    <p>{{ $orcamento->paymentMethod?->nome ?? 'Não definido' }}</p>
                    @if ($orcamento->condicoes_pagamento)
                        <p class="muted">{{ $orcamento->condicoes_pagamento }}</p>
                    @endif
                </div>
                <div class="info-block">
                    <h3>Validade</h3>
                    <p>
                        Válido até
                        {{ $orcamento->data_validade ? $orcamento->data_validade->format('d/m/Y') : 'sem data definida' }}
                    </p>
                    @if ($orcamento->responsavel)
                        <p class="muted">Responsável: {{ $orcamento->responsavel }}</p>
                    @endif
                </div>
            </div>
        </div>

        <div class="section">
            <h3>Totais</h3>
            <table>
                <tbody>
                    <tr>
                        <th>Subtotal materiais</th>
                        <td>R$ {{ number_format($orcamento->total_material, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Subtotal serviços</th>
                        <td>R$ {{ number_format($orcamento->total_servico, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Descontos</th>
                        <td>- R$ {{ number_format($orcamento->total_desconto, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Acréscimos</th>
                        <td>+ R$ {{ number_format($orcamento->total_acrescimo, 2, ',', '.') }}</td>
                    </tr>
                    <tr>
                        <th>Total líquido</th>
                        <td><strong>R$ {{ number_format($orcamento->total_liquido, 2, ',', '.') }}</strong></td>
                    </tr>
                </tbody>
            </table>
        </div>

        @foreach ($orcamento->locais as $local)
            <div class="section">
                <h3>{{ $local->nome }}</h3>
                @if ($local->observacoes)
                    <p class="muted">{{ $local->observacoes }}</p>
                @endif
                <table>
                    <thead>
                        <tr>
                            <th>Peça</th>
                            <th>Material</th>
                            <th>Dimensões</th>
                            <th>Qtd</th>
                            <th>Área (m²)</th>
                            <th>Subtotal</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($local->pecas as $peca)
                            <tr>
                                <td>
                                    {{ $peca->identificador ?? 'Peça #' . $peca->id }}
                                    @if ($peca->observacoes)
                                        <div class="muted">{{ $peca->observacoes }}</div>
                                    @endif
                                </td>
                                <td>{{ $peca->material->nome }}</td>
                                <td>
                                    {{ number_format($peca->largura_mm / 10, 1, ',', '.') }} x {{ number_format($peca->comprimento_mm / 10, 1, ',', '.') }} cm
                                </td>
                                <td>{{ $peca->quantidade }}</td>
                                <td>{{ number_format($peca->area_m2, 3, ',', '.') }}</td>
                                <td>R$ {{ number_format($peca->total, 2, ',', '.') }}</td>
                            </tr>
                            @if ($peca->servicos->isNotEmpty())
                                <tr>
                                    <td colspan="6">
                                        <strong>Serviços:</strong>
                                        <ul>
                                            @foreach ($peca->servicos as $itemServico)
                                                <li>
                                                    {{ $itemServico->descricao }} •
                                                    {{ number_format($itemServico->quantidade, 3, ',', '.') }} {{ $itemServico->tipo_cobranca }}
                                                    — R$ {{ number_format($itemServico->total, 2, ',', '.') }}
                                                </li>
                                            @endforeach
                                        </ul>
                                    </td>
                                </tr>
                            @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endforeach

        @if ($orcamento->observacoes)
            <div class="section">
                <h3>Observações finais</h3>
                <p>{{ $orcamento->observacoes }}</p>
            </div>
        @endif
    </body>
</html>
