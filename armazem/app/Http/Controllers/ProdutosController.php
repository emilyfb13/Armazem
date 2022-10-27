<?php

namespace App\Http\Controllers;

//use Illuminate\Pagination\Paginator;
use Illuminate\Http\Request;
//use Illuminate\Support\Facades\Http;
use GuzzleHttp\Exception\GuzzleException;
//use GuzzleHttp\Client as GuzzleClient;
use GuzzleHttp\Client;
//use Illuminate\Support\Facades\App;
use App\Helpers\Helper;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Pagination\Paginator;
use App\Repositorios\Produtos;
use App\Support\Collection;
use Illuminate\Support\Collection as CoreCollection;

class ProdutosController extends Controller
{

  protected $produtos;

  public function __construct(Produtos $produtos)
  {
    $this->produtos = $produtos;
  }
  public function index()
  {
    if (session()->has('Matricula')) {

      if (session()->has('list_prod')) {
        $itens0 = session()->get('list_prod');
        $itens = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $itens0[0]));
      } else {
        try{
          $this->produtos->allProdutos();

        }catch (\Exception $e){
          session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
          return  redirect()->route('saArmazem'); 

        }

        $itens0 = session()->get('list_prod');
        $itens = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $itens0[0]));
      }

      $aProdutos = (new Collection($itens))->paginate(10);
      $aProdutos->withPath('apiprodutos');

      return view('armazem.produtos', compact(['aProdutos']));
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function filtroProd(Request $request)
  {
    $codproduto = $request->codproduto;
    $produto = $request->produto;
    $tipo =     $request->tipo;
    $grupo =     $request->grupo;
    $parametros = 0;
    $aParametros = $request->except('_token'); 

    if (empty($codproduto)) {$codproduto= " ";}
    if (empty($produto)) {$produto= " ";}
    if (empty($grupo)) {$grupo= " ";}

    if (session()->has('Matricula')) {

        try{
          $itens = $this->produtos->filtroProd($codproduto, $produto, $tipo, $grupo);
        }catch (\Exception $e){
          session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
          return  redirect()->route('apiprodutos'); 
        }
      }
      
      if (isset($itens)) {
      $aProdutos = (new Collection($itens))->paginate(10);
      
      $aProdutos->withPath('filtroProd?filtroavancado');

      $parametros = 1;
      
      return view('armazem.produtos', compact(['aProdutos', 'parametros', 'aParametros']));
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function buscaPalavraChave(Request $request)
  {
    $aParametros = $request->except('_token'); //retornar para view preciso do array "palavrachave" => "xxxxxx"
    $parametros = $request->input('palavrachave'); // filtrar com a função filter preciso apenas da string

    if (session()->has('list_prod')) {
      $itens0 = session()->get('list_prod');
      $itens = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $itens0[0]));
    } else {
      $this->produtos->allProdutos();
      $itens0 = session()->get('list_prod');
      $itens = json_decode(preg_replace('/[\x00-\x1F\x80-\xFF]/', '', $itens0[0]));
    }

    $collection = (new Collection($itens));

    $aProdutos =  $collection->filter(function ($value) use ($parametros) {
      return (stristr($value->DESCRICAO, $parametros) ||
        stristr($value->ID, $parametros) ||
        stristr($value->GRUPO, $parametros));
    })->paginate(10);
    
    $aProdutos->withPath('filtrarpalavra?palavrachave=' . $parametros);

    return view('armazem.produtos', compact(['aProdutos', 'aParametros']));
  }
}
