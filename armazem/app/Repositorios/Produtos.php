<?php

namespace App\Repositorios;
use GuzzleHttp\Client;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\DB;

use App\Support\Collection;

class Produtos 
{
    protected  $client;

    public function __construct(Client $client )
    {
        $this->client = $client;
    }

    public function allProdutos()
    {
        $response = $this->client->request('GET', 'APIPRODUTOS?STATUS=1&TIPO=MC&TIPO=AI'); 
        $list_prod =  $response->getBody()->getContents();

        session()->put('list_prod');
        session()->push('list_prod',$list_prod);
       
    }

    public function filtroProd($codproduto, $produto, $tipo, $grupo)
    {
        if (empty($tipo)) {
            $response = $this->client->request('GET', 'APIPRODUTOS?ID='.$codproduto.'&DESC='.$produto.'&TIPO=AI&TIPO=MC&STATUS=1&GRUPO='.$grupo);
        }
        else{
            $response = $this->client->request('GET', 'APIPRODUTOS?ID='.$codproduto.'&DESC='.$produto.'&TIPO='.$tipo.'&GRUPO='.$grupo.'&STATUS=1');
        }

        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
    }

}
