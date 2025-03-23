<?php

namespace Database\Seeders;

use Carbon\Carbon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CidadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $timestamp = Carbon::now();

        $cidades = [
            // Cidades de São Paulo (SP)
            ['cid_nome' => 'São Paulo', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Campinas', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Santos', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Sorocaba', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Ribeirão Preto', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Bauru', 'cid_uf' => 'SP'],
            ['cid_nome' => 'São José dos Campos', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Guarulhos', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Osasco', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Jundiaí', 'cid_uf' => 'SP'],
            ['cid_nome' => 'São Bernardo do Campo', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Mogi das Cruzes', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Barretos', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Marília', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Franca', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Caraguatatuba', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Piracicaba', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Limeira', 'cid_uf' => 'SP'],
            ['cid_nome' => 'Itapevi', 'cid_uf' => 'SP'],
            ['cid_nome' => 'São Vicente', 'cid_uf' => 'SP'],

            // Cidades de Minas Gerais (MG)
            ['cid_nome' => 'Belo Horizonte', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Uberlândia', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Contagem', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Juiz de Fora', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Betim', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Montes Claros', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Ribeirão das Neves', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Uberaba', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Governador Valadares', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Ipatinga', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Sete Lagoas', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Divinópolis', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Santa Luzia', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Poços de Caldas', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Patos de Minas', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Pouso Alegre', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Barbacena', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Teófilo Otoni', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Sabará', 'cid_uf' => 'MG'],
            ['cid_nome' => 'Varginha', 'cid_uf' => 'MG'],

            // Cidades do Rio de Janeiro (RJ)
            ['cid_nome' => 'Rio de Janeiro', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Niterói', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Nova Iguaçu', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Duque de Caxias', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Petrópolis', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Campos dos Goytacazes', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Volta Redonda', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Teresópolis', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Cabo Frio', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Angra dos Reis', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Macaé', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Resende', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Barra Mansa', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Maricá', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Queimados', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'São Gonçalo', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Itaboraí', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Rio das Ostras', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Guapimirim', 'cid_uf' => 'RJ'],
            ['cid_nome' => 'Magé', 'cid_uf' => 'RJ'],

            // Cidades de Mato Grosso (MT)
            ['cid_nome' => 'Cuiabá', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Várzea Grande', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Rondonópolis', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Sinop', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Tangará da Serra', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Sorriso', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Barra do Garças', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Alta Floresta', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Pontes e Lacerda', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Lucas do Rio Verde', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Primavera do Leste', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Campo Verde', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Cáceres', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Nova Mutum', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Juína', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Colíder', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Peixoto de Azevedo', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Jaciara', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Chapada dos Guimarães', 'cid_uf' => 'MT'],
            ['cid_nome' => 'Mirassol d\'Oeste', 'cid_uf' => 'MT'],

            // Cidades do Distrito Federal (DF)
            ['cid_nome' => 'Brasília', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Ceilândia', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Taguatinga', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Gama', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Samambaia', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Recanto das Emas', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Santa Maria', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Sobradinho', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Planaltina', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Riacho Fundo', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Lago Sul', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Lago Norte', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Núcleo Bandeirante', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Guará', 'cid_uf' => 'DF'],
            ['cid_nome' => 'São Sebastião', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Paranoá', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Candangolândia', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Vicente Pires', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Cruzeiro', 'cid_uf' => 'DF'],
            ['cid_nome' => 'Varjão', 'cid_uf' => 'DF'],

            // Cidades do Tocantins (TO)
            ['cid_nome' => 'Palmas', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Araguaína', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Gurupi', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Porto Nacional', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Paraíso do Tocantins', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Colinas do Tocantins', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Guaraí', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Dianópolis', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Tocantinópolis', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Miracema do Tocantins', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Augustinópolis', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Taguatinga', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Alvorada', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Formoso do Araguaia', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Pedro Afonso', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Arraias', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Xambioá', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Peixe', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Goiatins', 'cid_uf' => 'TO'],
            ['cid_nome' => 'Natividade', 'cid_uf' => 'TO'],
        ];

         // Adiciona os timestamps em cada cidade
         foreach ($cidades as &$cidade) {
            $cidade['created_at'] = $timestamp;
            $cidade['updated_at'] = $timestamp;
        }

        DB::table('cidades')->insert($cidades);
    }
}
