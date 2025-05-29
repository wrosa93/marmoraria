<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ClienteController;
use App\Http\Controllers\MaterialController;
use App\Http\Controllers\ServicoController;
use App\Http\Controllers\OrcamentoController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Rota pública inicial (pode ser a de login ou uma landing page)
Route::get('/', function () {
    // Se o usuário já estiver logado, redireciona para o dashboard
    if (auth()->check()) {
        return redirect()->route('orcamentos.index');
    }
    // Caso contrário, mostra a tela de login
    return redirect()->route('login');
});

// Rotas de autenticação (geradas pelo Breeze)
require __DIR__.'/auth.php';

// Grupo de rotas protegidas por autenticação
Route::middleware(['auth', 'verified'])->group(function () {
    // Rota do dashboard principal (após login)
    // Route::get('/dashboard', function () {
    //     return view('dashboard');
    // })->name('dashboard'); // Comentado ou removido se não for usar o dashboard padrão do Breeze

    // Rotas do perfil do usuário (geradas pelo Breeze)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Rotas CRUD da aplicação principal (Marmoraria)
    Route::resource('clientes', ClienteController::class);
    Route::resource('materiais', MaterialController::class);
    Route::resource('servicos', ServicoController::class);
    Route::resource('orcamentos', OrcamentoController::class);

    // Redirecionamento da raiz para orçamentos após login
    Route::get('/home', function(){
        return redirect()->route('orcamentos.index');
    })->name('home'); // Nomear a rota 'home' pode ser útil

     // Adicionar outras rotas protegidas aqui se necessário
});





    // Rota para o relatório de orçamentos
    Route::get(
'/relatorios/orcamentos

', [OrcamentoController::class, 
'relatorioForm

'])->name(
'orcamentos.relatorio.form

');
    Route::get(
'/relatorios/orcamentos/buscar

', [OrcamentoController::class, 
'buscarRelatorio

'])->name(
'orcamentos.relatorio.buscar

');


    // Rota para gerar PDF do orçamento
    Route::get(
'/orcamentos/{orcamento}/pdf

', [OrcamentoController::class, 
'gerarPdf

'])->name(
'orcamentos.pdf

');
