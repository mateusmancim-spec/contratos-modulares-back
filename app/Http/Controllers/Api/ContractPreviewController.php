<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Clause;
use App\Models\ContractType;
use Illuminate\Http\Request;

class ContractPreviewController extends Controller
{
    public function __invoke(Request $request)
    {
        $data = $request->validate([
            'contract_type_id' => ['required', 'integer', 'exists:contract_types,id'],
            'placeholders' => ['required', 'array'],
            'optional_clause_keys' => ['array'],
        ]);

        $contractType = ContractType::findOrFail($data['contract_type_id']);
        $optionalKeys = $data['optional_clause_keys'] ?? [];

        $allClauses = Clause::where('contract_type_id', $contractType->id)->get();

        // OBRIGATÓRIAS
        $mandatory = $allClauses
            ->where('is_mandatory', true)
            ->sortBy('order')
            ->values();

        // Identificar FORO
        $foro = $mandatory->firstWhere('key', 'FORO');

        // Obrigatórias antes do FORO (PARTES, OBJETO, REMUNERAÇÃO, etc.)
        $mandatoryBeforeForo = $mandatory->filter(
            fn ($clause) => !$foro || $clause->id !== $foro->id
        )->values();

        // Index por key
        $byKey = $allClauses->keyBy('key');

        // Opcionais na ordem escolhida pelo usuário (sidebar direita)
        $orderedOptionals = [];
        foreach ($optionalKeys as $key) {
            $clause = $byKey->get($key);
            if ($clause && !$clause->is_mandatory) {
                $orderedOptionals[] = $clause;
            }
        }

        // Ordem final: obrigatórias (sem foro) → opcionais → foro
        $finalClauses = $mandatoryBeforeForo;

        foreach ($orderedOptionals as $clause) {
            $finalClauses->push($clause);
        }

        if ($foro) {
            $finalClauses->push($foro);
        }

        $placeholders = $data['placeholders'];
        $fullText = '';

        // 🔢 Numeração dinâmica:
        // - CABECALHO não é numerado
        // - demais seguem 1ª, 2ª, 3ª... conforme ordem
        $runningNumber = 0;

        foreach ($finalClauses as $clause) {
            // Substitui placeholders no texto
            $text = $clause->text_template;
            foreach ($placeholders as $key => $value) {
                $text = str_replace('{{' . $key . '}}', $value, $text);
            }

            // CABECALHO SEM NUMERAÇÃO
            if ($clause->key === 'CABECALHO') {
                $finalTitle = $clause->title;
            } else {
                // incrementa contador de cláusula numerada
                $runningNumber++;

                // 🔧 Remove qualquer numeração antiga do título,
                // pegando só o pedaço DEPOIS do primeiro "-"
                $baseTitle = $clause->title;

                if (strpos($baseTitle, '-') !== false) {
                    // divide em duas partes, pega só o pedaço depois do "-"
                    [$left, $right] = explode('-', $baseTitle, 2);
                    $baseTitle = trim($right);
                }

                // Se por algum motivo ficar vazio, usa o título como está
                if ($baseTitle === '') {
                    $baseTitle = $clause->title;
                }

                // Monta título novo com numeração dinâmica
                $finalTitle = "CLÁUSULA {$runningNumber}ª - {$baseTitle}";
            }

            $fullText .= "\n\n" . $finalTitle . "\n" . $text;
        }

        return response()->json([
            'contract_type' => $contractType->name,
            'content' => trim($fullText),
        ]);
    }
}
