<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositorios\CCusto;

class CarrinhoController extends Controller
{
  protected $cCusto;

  public function __construct(CCusto $cCusto)
  {
    $this->cCusto = $cCusto;
  }

  public function index()
  {
    if (session()->has('Matricula')) {

      $centros = $this->cCusto->allCentroCusto();
      $produtos = "";

      if (session()->get('PRODUTOS') != null) {

        $produtos = session()->get('PRODUTOS');
      }
      return view('armazem.carrinho', compact('centros', 'produtos'));
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function postCarrinho($ID, $DESCRICAO, $UNIDADEMEDIDA)
  {
    if (session()->has('Matricula')) {

      //Produto já existe no carrinho /// Provisóriamente desabilitado ////

      /*

         $carrinho = session()->get('PRODUTOS');

         //buscar produto
         $aux =  collect($carrinho)->where('produto', $ID)->first();

         if(  $aux != null ) 
         {
           session()->flash('erro',  '( '.$ID.' - '.$DESCRICAO.' - ' .$UNIDADEMEDIDA.' ) '. 'já esta no seu carrinho!');
           return redirect()->route('apiprodutos');

         }

         */

      $dados =
        [
          'produto' => $ID,
          'descr' => $DESCRICAO,
          'unidade' => $UNIDADEMEDIDA
        ];

      if (session()->has('PRODUTOS')) {
        session()->push('PRODUTOS', ...[$dados]);
      
        session()->flash('sucesso', '1 ' . $UNIDADEMEDIDA . ' do produto ( ' . $ID . ' - ' . $DESCRICAO . ' ) ' . ' inserido(a) no carrinho com sucesso!');
        return redirect()->back();
      } else {
        session()->put('PRODUTOS'); //criar o "alias"
        session()->push('PRODUTOS', ...[$dados]);
    
        session()->flash('sucesso', '1 ' . $UNIDADEMEDIDA . ' do produto ( ' . $ID . ' - ' . $DESCRICAO . ' ) ' . ' inserido(a) no carrinho com sucesso!');
        return redirect()->back();
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function deleteCarrinho($id)
  {
    if (session()->has('Matricula')) {

      $carrinho = session()->get('PRODUTOS');
      $key = array_search($id, array_column($carrinho, 'produto'));

      array_splice($carrinho, $key, 1);

      session()->forget('PRODUTOS');

      session()->put('PRODUTOS', $carrinho);

      session()->flash('remover', 'O produto ' . $id . ' foi removido do seu carrinho com sucesso!');
      return redirect()->back();
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }
}
