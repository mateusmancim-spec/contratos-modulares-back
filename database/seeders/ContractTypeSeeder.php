<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\ContractType;

class ContractTypeSeeder extends Seeder
{
    public function run(): void
    {
        ContractType::create([
            'name' => 'Prestação de Serviços',
            'description' => 'Contrato padrão de prestação de serviços.',
        ]);
    }
}
