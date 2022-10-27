<?php

namespace App\Repositorios;

use GuzzleHttp\Client;

class CCusto
{
    protected  $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function allCentroCusto()
    {
        //http://192.168.101.237:8014/api/
        //$reponse = $this->client->request('GET', 'apicentrocusto?OFFSET=0&LIMIT=3');

        $reponse = $this->client->request('GET', 'apicentrocusto');

        return json_decode($reponse->getBody()->getContents());

        //dd( $centros);
    }
}
