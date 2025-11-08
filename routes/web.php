<?php

use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\OrcamentoController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\ServicoController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    if (auth()->check()) {
        return redirect()->route('orcamentos.index');
    }

    return redirect()->route('login');
});

require __DIR__ . '/auth.php';

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    Route::resource('clientes', ClienteController::class);
    Route::resource('materiais', MaterialController::class);
    Route::resource('servicos', ServicoController::class);
    Route::resource('orcamentos', OrcamentoController::class);

    Route::post('orcamentos/{orcamento}/locais', [OrcamentoController::class, 'storeLocal'])
        ->name('orcamentos.locais.store');
    Route::delete('orcamentos/{orcamento}/locais/{local}', [OrcamentoController::class, 'destroyLocal'])
        ->name('orcamentos.locais.destroy');
    Route::post('orcamentos/{orcamento}/locais/{local}/pecas', [OrcamentoController::class, 'storePiece'])
        ->name('orcamentos.locais.pecas.store');
    Route::delete('orcamentos/{orcamento}/pecas/{peca}', [OrcamentoController::class, 'destroyPiece'])
        ->name('orcamentos.pecas.destroy');
    Route::post('orcamentos/{orcamento}/pecas/{peca}/servicos', [OrcamentoController::class, 'storePieceService'])
        ->name('orcamentos.pecas.servicos.store');
    Route::delete('orcamentos/{orcamento}/servicos/{servico}', [OrcamentoController::class, 'destroyPieceService'])
        ->name('orcamentos.pecas.servicos.destroy');
    Route::get('orcamentos/{orcamento}/pdf', [OrcamentoController::class, 'gerarPdf'])
        ->name('orcamentos.pdf');

    Route::get('relatorios/orcamentos', [OrcamentoController::class, 'relatorioForm'])
        ->name('orcamentos.relatorio.form');
    Route::get('relatorios/orcamentos/buscar', [OrcamentoController::class, 'buscarRelatorio'])
        ->name('orcamentos.relatorio.buscar');

    Route::get('/home', function () {
        return redirect()->route('orcamentos.index');
    })->name('home');
});
