<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Orçamento {{ $orcamento->id }}</title>
    <style>
        /* Basic styling for the PDF */
        body {
            font-family: sans-serif; /* Use a common font */
            font-size: 12px;
            line-height: 1.4;
            margin: 20px;
            color: #333;
        }
        .header {
            text-align: center;
            margin-bottom: 20px;
            border-bottom: 1px solid #ccc;
            padding-bottom: 10px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 5px 0 0;
            font-size: 14px;
            color: #666;
        }
        .client-info {
            margin-bottom: 20px;
            padding: 10px;
            border: 1px solid #eee;
            background-color: #f9f9f9;
            border-radius: 5px;
        }
        .client-info h2 {
            margin-top: 0;
            margin-bottom: 10px;
            font-size: 16px;
            border-bottom: 1px solid #eee;
            padding-bottom: 5px;
        }
        .client-info p {
            margin: 5px 0;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        .total-section {
            text-align: right;
            margin-top: 20px;
            padding-top: 10px;
            border-top: 1px solid #ccc;
        }
        .total-section h3 {
            margin: 0;
            font-size: 18px;
        }
        .text-right {
            text-align: right;
        }
        .text-center {
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>Orçamento #{{ $orcamento->id }}</h1>
        <p>Data: {{ $orcamento->data->format("d/m/Y") }}</p>
        {{-- Add Marmoraria Name/Logo here if desired --}}
        {{-- <p>Nome da Marmoraria</p> --}}
    </div>

    <div class="client-info">
        <h2>Dados do Cliente</h2>
        <p><strong>Nome:</strong> {{ $orcamento->cliente->nome }}</p>
        <p><strong>Email:</strong> {{ $orcamento->cliente->email ?? "-" }}</p>
        <p><strong>Telefone:</strong> {{ $orcamento->cliente->telefone ?? "-" }}</p>
        <p><strong>Endereço:</strong> {{ $orcamento->cliente->endereco ?? "-" }}</p>
    </div>

    <h2>Itens do Orçamento</h2>
    <table>
        <thead>
            <tr>
                <th>Item</th>
                <th>Material</th>
                <th>Serviço</th>
                <th class="text-center">Qtde (m²)</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @php $totalGeral = 0; @endphp
            @forelse ($orcamento->items as $index => $item)
                @php $totalGeral += $item->subtotal; @endphp
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $item->material->nome ?? "N/A" }}</td>
                    <td>{{ $item->servico->descricao ?? "N/A" }}</td>
                    <td class="text-center">{{ number_format($item->quantidade_m2, 2, ",", ".") }}</td>
                    <td class="text-right">R$ {{ number_format($item->subtotal, 2, ",", ".") }}</td>
                </tr>
                @if($item->descricao)
                <tr>
                    <td colspan="5"><em>Obs: {{ $item->descricao }}</em></td>
                </tr>
                @endif
            @empty
                <tr>
                    <td colspan="5" class="text-center">Nenhum item adicionado a este orçamento.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="total-section">
        <h3>Total Geral: R$ {{ number_format($totalGeral, 2, ",", ".") }}</h3>
    </div>

</body>
</html>
