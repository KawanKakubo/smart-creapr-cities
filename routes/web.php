<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PublicFormController;
use App\Http\Controllers\Admin\AdminSubmissionController;
use App\Http\Controllers\Admin\AdminEmailController;
use App\Http\Controllers\Admin\AdminExportController;
use App\Http\Controllers\Admin\AdminEventsController;
use App\Http\Controllers\Admin\QuestionController;
use App\Http\Controllers\Admin\RepositoryController;
use App\Http\Controllers\Auth\ChangePasswordController;
use App\Http\Controllers\Auth\AdminLoginController;
use Illuminate\Support\Facades\Route;

// Página inicial - Landing Page com Vídeo
Route::get('/', function () {
    return view('landing-v2');
})->name('home');

// Rotas Públicas - Formulário de Manifestação de Interesse
// Rate limiting: 60 requisições por minuto
Route::get('/manifestacao-interesse', [PublicFormController::class, 'show'])
    ->middleware('throttle:60,1')
    ->name('manifestacao.show');

// Rate limiting: 60 submissões por hora durante testes (alterar para 5 em produção)
Route::post('/manifestacao-interesse', [PublicFormController::class, 'store'])
    ->middleware('throttle:60,60')
    ->name('manifestacao.store');

// Rate limiting: 10 acessos por minuto para prevenir força bruta no token
Route::get('/inscricao-concluida/{protocolo}/{token}', [PublicFormController::class, 'success'])
    ->middleware('throttle:10,1')
    ->name('inscricao.sucesso');

// Rotas de Login Administrativo (guest only)
Route::middleware('guest')->prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AdminLoginController::class, 'create'])->name('login');
    Route::post('/login', [AdminLoginController::class, 'store'])->name('login.store');
});

// Rotas Administrativas (requerem autenticação e role admin)
Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    // Logout
    Route::post('/logout', [AdminLoginController::class, 'destroy'])->name('logout');
    
    // Dashboard
    Route::get('/dashboard', [AdminSubmissionController::class, 'dashboard'])->name('dashboard');
    
    // Gerenciamento de Submissões
    Route::get('/submissoes', [AdminSubmissionController::class, 'index'])->name('submissoes.index');
    Route::get('/submissoes/exportar', [AdminSubmissionController::class, 'export'])->name('submissoes.export');
    Route::patch('/submissoes/{submission}/toggle-active', [AdminSubmissionController::class, 'toggleActive'])->name('submissoes.toggleActive');
    Route::get('/submissoes/{submission}/exportar-respostas-xlsx', [AdminExportController::class, 'exportAnswersXlsx'])->name('submissoes.exportAnswersXlsx');
    Route::get('/submissoes/{submission}/exportar-respostas-pdf', [AdminExportController::class, 'exportAnswersPdf'])->name('submissoes.exportAnswersPdf');
    
    // Consolidated exports for all active municipalities answers
    Route::get('/consolidado/exportar-xlsx', [AdminExportController::class, 'exportConsolidatedAnswersXlsx'])->name('consolidado.exportXlsx');
    Route::get('/consolidado/exportar-pdf', [AdminExportController::class, 'exportConsolidatedAnswersPdf'])->name('consolidado.exportPdf');
    
    Route::get('/submissoes/{submission}', [AdminSubmissionController::class, 'show'])->name('submissoes.show');
    Route::patch('/submissoes/{submission}/status', [AdminSubmissionController::class, 'updateStatus'])->name('submissoes.updateStatus');
    Route::patch('/submissoes/{submission}/mais-engenharia', [AdminSubmissionController::class, 'updateMaisEngenharia'])->name('submissoes.updateMaisEngenharia');
    
    // Custom Emailing and Communications Campaigns
    Route::get('/comunicados', [AdminEmailController::class, 'create'])->name('emails.create');
    Route::post('/comunicados/enviar', [AdminEmailController::class, 'send'])->name('emails.send');
    
    // Configurações do Sistema
    Route::post('/settings/toggle-registration', [AdminSubmissionController::class, 'toggleRegistration'])->name('settings.toggle-registration');
    
    // Gerenciamento de Questões Diagnósticas
    Route::resource('questions', QuestionController::class)->except(['show']);
    Route::post('/questions/reorder', [QuestionController::class, 'reorder'])->name('questions.reorder');
    Route::post('/questions/bulk-toggle', [QuestionController::class, 'bulkToggle'])->name('questions.bulkToggle');
    
    // Gerenciamento de Eventos do Programa
    Route::resource('events', AdminEventsController::class)->except(['show']);
    
    // Gerenciamento de Repositório de Documentos
    Route::resource('repository', RepositoryController::class)->except(['show']);
});

// Rotas do Município (requerem autenticação e role municipality)
Route::middleware(['auth', 'municipality', \App\Http\Middleware\CheckMustChangePassword::class])->prefix('municipality')->name('municipality.')->group(function () {
    // Dashboard
    Route::get('/dashboard', [\App\Http\Controllers\Municipality\DashboardController::class, 'index'])->name('dashboard');
    
    // Gerenciamento de Comitê
    Route::post('/committee', [\App\Http\Controllers\Municipality\CommitteeController::class, 'store'])->name('committee.store');
    Route::delete('/committee/{id}', [\App\Http\Controllers\Municipality\CommitteeController::class, 'destroy'])->name('committee.destroy');
    
    // Diagnósticos
    Route::get('/diagnostic/estimulo', [\App\Http\Controllers\Municipality\DiagnosticController::class, 'estimulo'])->name('diagnostic.estimulo');
    Route::get('/diagnostic/educacao', [\App\Http\Controllers\Municipality\DiagnosticController::class, 'educacao'])->name('diagnostic.educacao');
    Route::get('/diagnostic/estruturas', [\App\Http\Controllers\Municipality\DiagnosticController::class, 'estruturas'])->name('diagnostic.estruturas');
    
    // Salvar diagnósticos
    Route::post('/diagnostic/{category}', [\App\Http\Controllers\Municipality\DiagnosticController::class, 'store'])->name('diagnostic.store');
    
    // Repositório de Documentos
    Route::get('/repository', [\App\Http\Controllers\Municipality\RepositoryController::class, 'index'])->name('repository.index');
    Route::get('/repository/download/{repository}', [\App\Http\Controllers\Municipality\RepositoryController::class, 'download'])->name('repository.download');
});

// Rotas de Mudança de Senha (requerem autenticação)
Route::middleware('auth')->group(function () {
    Route::get('/change-password', [ChangePasswordController::class, 'show'])->name('change-password.show');
    Route::post('/change-password', [ChangePasswordController::class, 'update'])->name('change-password.update');
});

// Rotas de Perfil (Breeze)
Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
