<?php

namespace App\Repositorios;

use GuzzleHttp\Client;

class Login
{
    protected  $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function LoginGet($user, $senha)
    {
        //http://192.168.101.237:8014/api/
        //$reponse = $this->client->request('GET', 'apicentrocusto?OFFSET=0&LIMIT=3');

        $response = $this->client->request('GET', 'ApiLogin?USER='.$user.'&SENHA='.$senha);

        return json_decode($response->getBody()->getContents());

    }

    public function Verifica($cpf, $matricula, $mae)
    {

        $reponse = $this->client->request('GET', 'ApiFuncionario?CPF='.$cpf.'&MATRICULA='.$matricula.'&MAE='.$mae);

        return json_decode($reponse->getBody()->getContents());

    }

    public function postLogin($json)
    {
    
        $response = $this->client->request('POST', 'ApiLogin', 
        [
           'body' =>     $json 
        ]);

        $status =  $response->getStatusCode();
        
       
        if ($status == 201) {
            return  json_decode($response->getBody()->getContents());
        } else {
            return "Falha de comunicação com o Protheus";
        }
    }

    public function putLogin($json)
    {
    
        $response = $this->client->request('PUT', 'ApiLogin', 
        [
           'body' =>    $json 
        ]);
        
        $status =  $response->getStatusCode();
        
        if( $status >= 200  && $status < 400){
            return  json_decode($response->getBody()->getContents());
        } else {
            return "Falha de comunicação com o Protheus";
        }
    }
}
