<?php

namespace App\Helpers;
//use Dcrypt\RC4;
//use dcrypt\RC4;
//use mmeyer2k\RC4;

use Illuminate\Support\Facades\App;

class Helper
{
    public static function shout(string $string)
    {
        return strtoupper($string);
    }

    public static function nomeUser($string){
        $nome = ucfirst( strstr( $string,".",true));
        if ( $nome != null)
            return $nome;
        else 
         return ucfirst($string);

    }

   
    /**
     * Verifica se tem uma string ou caractere dentro de uma frase
     *
     * @param  string $string
     * @param  string $search
     * @return void
     */
    public static function str_contains_helper($string, $search)
    {
        if (strpos($string, $search) !== false) {
            return true;
        }
        return false;
    }

    //contador para o carrinho de compras
    public static function countCarrinho( ){
        if(session()->has('PRODUTOS')){
            $produtos = session()->get('PRODUTOS');
            $result = count($produtos);
            return $result ;
        }else {
            return 0 ;
        }
    }
}