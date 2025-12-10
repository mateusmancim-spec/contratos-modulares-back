<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Clause;
use App\Models\ContractType;

class ClauseSeeder extends Seeder
{
    public function run(): void
    {
        // Em ambiente de desenvolvimento, podemos limpar as cláusulas
        Clause::truncate();

        $serviceContract = ContractType::where('name', 'Prestação de Serviços')->firstOrFail();

        // Cabeçalho
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CONTRATO DE PRESTAÇÃO DE SERVIÇOS',
            'text_template' => 'Pelo presente instrumento particular, de um lado {{CONTRATANTE_NOME}}, {{CONTRATANTE_QUALIFICACAO}}, doravante denominado CONTRATANTE, e de outro {{CONTRATADO_NOME}}, {{CONTRATADO_QUALIFICACAO}}, doravante denominado CONTRATADO, têm entre si justo e contratado o que segue.',
            'is_mandatory' => true,
            'order' => 1,
            'key' => 'CABECALHO',
        ]);

        // Partes
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CLÁUSULA 1ª - DAS PARTES',
            'text_template' => 'As partes acima qualificadas declaram ser capazes e assumem as obrigações deste contrato.',
            'is_mandatory' => true,
            'order' => 2,
            'key' => 'PARTES',
        ]);

        // Objeto
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CLÁUSULA 2ª - DO OBJETO',
            'text_template' => 'O presente contrato tem por objeto {{DESCRICAO_OBJETO}}, a ser prestado pelo CONTRATADO ao CONTRATANTE.',
            'is_mandatory' => true,
            'order' => 3,
            'key' => 'OBJETO',
        ]);

        // Remuneração
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CLÁUSULA 3ª - DA REMUNERAÇÃO',
            'text_template' => 'Pelos serviços descritos na cláusula anterior, o CONTRATANTE pagará ao CONTRATADO o valor de {{VALOR_CONTRATO}}, na forma de pagamento: {{FORMA_PAGAMENTO}}.',
            'is_mandatory' => true,
            'order' => 4,
            'key' => 'REMUNERACAO',
        ]);

        // 🔹 OPCIONAL 1 – Multa rescisória
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CLÁUSULA 4ª - DA MULTA RESCISÓRIA',
            'text_template' => 'Em caso de rescisão imotivada por qualquer das partes, deverá a parte que der causa pagar à outra multa correspondente a {{VALOR_MULTA}}, sem prejuízo de indenização por eventuais perdas e danos.',
            'is_mandatory' => false,
            'order' => 5,
            'key' => 'MULTA_RESCISORIA',
        ]);

        // 🔹 OPCIONAL 2 – Confidencialidade (nova cláusula opcional)
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CLÁUSULA 5ª - DA CONFIDENCIALIDADE',
            'text_template' => 'As partes obrigam-se a manter sigilo absoluto sobre todas as informações, dados, documentos e materiais aos quais tiverem acesso em razão deste contrato, não podendo divulgá-los a terceiros sem autorização expressa da outra parte, salvo por força de lei ou ordem judicial.',
            'is_mandatory' => false,
            'order' => 6,
            'key' => 'CONFIDENCIALIDADE',
        ]);

        // Foro
        Clause::create([
            'contract_type_id' => $serviceContract->id,
            'title' => 'CLÁUSULA 6ª - DO FORO',
            'text_template' => 'Fica eleito o foro da comarca de {{CIDADE_FORO}}, com renúncia de qualquer outro, por mais privilegiado que seja, para dirimir quaisquer dúvidas oriundas deste contrato.',
            'is_mandatory' => true,
            'order' => 7,
            'key' => 'FORO',
        ]);
    }
}
