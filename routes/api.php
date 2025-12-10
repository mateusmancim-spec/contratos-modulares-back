<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\ContractPreviewController;
use App\Models\ContractType;
use App\Models\Clause;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
| Essas rotas já ficam sob o prefixo /api
| Ex: Route::get('/contract-types') => /api/contract-types
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

// ✅ Ping só pra testar se as rotas estão carregando
Route::get('/contract-ping', function () {
  return ['ok' => true];
});

// ✅ Listar tipos de contrato
Route::get('/contract-types', function () {
    return ContractType::select('id', 'name', 'description')->get();
});

// ✅ Listar cláusulas de um tipo de contrato
Route::get('/contract-types/{contractType}/clauses', function (ContractType $contractType) {
    $clauses = Clause::where('contract_type_id', $contractType->id)
        ->orderBy('order')
        ->get();

    return [
        'id' => $contractType->id,
        'name' => $contractType->name,
        'description' => $contractType->description,
        'mandatory_clauses' => $clauses->where('is_mandatory', true)->values(),
        'optional_clauses' => $clauses->where('is_mandatory', false)->values(),
    ];
});

// ✅ Gerar prévia do contrato
Route::post('/contracts/preview', ContractPreviewController::class);
