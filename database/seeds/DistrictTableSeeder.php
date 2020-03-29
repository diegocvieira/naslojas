<?php

use Illuminate\Database\Seeder;
use App\District;

class DistrictTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $districts = [
            'Ambrósio Perret',
            'Arco Íris',
            'Areal',
            'Balsa',
            'Balneário dos Prazeres',
            'Bom Jesus',
            'Carpena',
            'Castilho',
            'Centro',
            'Cohab Fragata',
            'Cohab I',
            'Cohab II',
            'Cohab Lindóia',
            'Cohab Pestano',
            'Cohab Tablada',
            'Colina do Sol',
            'Cruzeiro',
            'Dunas',
            'Dunas II',
            'Fragata',
            'Fátima',
            'Getúlio Vargas',
            'Gotuzzo',
            'Guabiroba',
            'Ilha de Páscoa',
            'Jardim das Tradições',
            'Jardim Europa',
            'Lagos de São Gonçalo',
            'Laranjal',
            'Las Acacias',
            'Lindóia',
            'Loteamento Paineiras',
            'Marina Ilha Verde',
            'Navegantes',
            'Obelisco',
            'Padre Réus',
            'PAR Querência',
            'Passo Salso',
            'Pestano',
            'Pontal da Barra',
            'Porto',
            'Recanto de Portugal',
            'Residencial XV de Julho',
            'Santa Terezinha',
            'Santo Antônio de Pádua',
            'Santos Dumont',
            'Simões Lopes',
            'Solar da Figueira',
            'Sítio Floresta',
            'Três Vendas',
            'Umuharama',
            'Vasco Pires',
            'Vila Governaço',
            'Vila Mariana',
            'Vila Peres',
            'Vila Thoussant',
            'Virgílio Costa',
            'Vila Princesa',
            'Sanga Funda',
        ];

        foreach ($districts as $district) {
            District::create(['name' => $district]);
        }
    }
}
