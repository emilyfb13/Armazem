<?php

namespace App\Repositorios;

use GuzzleHttp\Client;

class SArmazem
{
    protected  $client;

    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    public function allSaUser($mUser)
    {

        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?SOLICITANTE='.$mUser.'&SORT=ID&ORDER=DESC');
        
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
        
    }




    public function saID($ID)
    {
        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?ID='.$ID);
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz

    }



    public function postSA($json)
    {
    
        $response = $this->client->request('POST', 'ApiSolicitacaoArmazem', 
        [
           'body' =>   '{ "ITENS": ' . $json . ' } '
        ]);

        $status =  $response->getStatusCode();

        if ($status == 201) {
            //limpar o carrinho do usuário
            session()->forget('PRODUTOS');
            return  json_decode($response->getBody()->getContents())->ID;
        } else {
            return "Falha de comunicação com o Protheus";
        }
    }

    public function putSA($json, $IDSA)
    {


        $response = $this->client->request('PUT', 'ApiSolicitacaoArmazem', 
        [
            'body' => '{ "ID": ' .json_encode($IDSA). ', "ITENS": '. $json .'}'
        ]);

        $status =  $response->getStatusCode();
        

        if( $status >= 200  && $status < 400)
        {
     
          return  json_decode($response->getBody()->getContents())->ID; 
        } else {
            return "Falha de comunicação com o Protheus";
        }

    }



    public function deleteitemSA($item, $ID)
    {
        $response = $this->client->request('DELETE', 'ApiSolicitacaoArmazem', 
        [
            'body' => '{ "ID": ' .json_encode($ID). ', "ITENS": [{ "ITEM": ' .json_encode($item). '}] }'
        ]);
    
        $status =  $response->getStatusCode();
         if( $status == 200)
         {
           return  json_decode($response->getBody()->getContents())->ID; 
         } else {
             return "Falha de comunicação com o Protheus";
         }
    }


    public function deleteall($IDSA, $json)
    {


        /***************************DELETE***********************************
            Json:s
             { "ID": "000463", "ITENS": [{ "ITEM": "02"}, {"ITEM": "01"}] }
        *********************************************************************/

        //dd('{ "ID": ' .json_encode($IDSA). ', "ITENS": '. $json .'}');

        $response = $this->client->request('DELETE', 'ApiSolicitacaoArmazem', [
            'body' => '{ "ID": ' .json_encode($IDSA). ', "ITENS": '. $json .'}'
        ]);

        $status =  $response->getStatusCode();
      
        if( $status == 200)
        {
          return  json_decode($response->getBody()->getContents())->ID; 
        } else {
            return "Falha de comunicação com o Protheus";
        }

        //{ "ID": "000463", "ITENS": [{ "ITEM": "02"}, {"ITEM": "01"}] }

        dd($response->getBody()->getContents());
    }

    
    public function dashInc($mUser)
    {

        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?SOLICITANTE='.$mUser.'&SORT=ID&ORDER=DESC&DASH=INCLUIDO');
        
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
        
    }

    public function dashFim($mUser)
    {

        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?SOLICITANTE='.$mUser.'&SORT=ID&ORDER=DESC&DASH=FIM');
        
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
        
    }

    public function dashPC($mUser)
    {

        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?SOLICITANTE='.$mUser.'&SORT=ID&ORDER=DESC&DASH=PC');
        
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
        
    }

    public function dashDisp($mUser)
    {

        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?SOLICITANTE='.$mUser.'&SORT=ID&ORDER=DESC&DASH=DISP');
        
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
        
    }

    
    public function filtroSA($mUser, $id, $status, $inicio, $fim, $cod, $produto, $obs)
    {
    
        $response = $this->client->request('GET', 'ApiSolicitacaoArmazem?SOLICITANTE='.$mUser.'&SORT=ID&ORDER=DESC&ID='.$id.'&DASH='.$status.'&CODPROD='.$cod.'&PROD='.$produto.'&OBS='.$obs.'&EMISSAOINI='.$inicio.'&EMISSAOFIM='.$fim);
        
        return  json_decode( preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $response->getBody()->getContents()) ); // se add um segundo parâmetro true os elementos serão convertidos em uma matriz
      
    }
}
