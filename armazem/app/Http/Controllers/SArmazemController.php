<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositorios\SArmazem;
use App\Repositorios\CCusto;
use App\Support\Collection;

class SArmazemController extends Controller 
{

  protected $armazem, $cCusto;

  public function __construct(SArmazem $armazem, CCusto $cCusto)
  {
    $this->armazem = $armazem;
    $this->cCusto = $cCusto;
    $this->middleware('guest')->except('logout');
  }

  public function index()
  {
    if (session()->has('Matricula')) {

      try {
        $items = $this->armazem->allSaUser(session()->get('Matricula'));
      } catch (\Exception $e) {
        session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
        return  redirect()->route('principal');
      }

      if (isset($items)) {
        $sArmazem = (new Collection($items))->paginate(10);
        $sArmazem->withPath('apiarmazem');
        return view('armazem.sarmazem', compact('sArmazem'));
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function dashInc()
  {
    if (session()->has('Matricula')) {

      $parametros = 0;

      try {
        $items = $this->armazem->dashInc(session()->get('Matricula'));
      } catch (\Exception $e) {
        session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
        return  redirect()->route('principal');
      }

      if (isset($items)) {
        $parametros = 1;
        $sArmazem = (new Collection($items))->paginate(10);
        $sArmazem->withPath('apiarmazem');
        return view('armazem.sarmazem',  compact(['sArmazem', 'parametros']));
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function dashPC()
  {
    if (session()->has('Matricula')) {

      $parametros = 0;

      try {
        $items = $this->armazem->dashPC(session()->get('Matricula'));
      } catch (\Exception $e) {
        session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
        return  redirect()->route('principal');
      }

      if (isset($items)) {
        $parametros = 1;
        $sArmazem = (new Collection($items))->paginate(10);
        $sArmazem->withPath('apiarmazem');
        return view('armazem.sarmazem',  compact(['sArmazem', 'parametros']));
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function dashDisp()
  {
    if (session()->has('Matricula')) {

      $parametros = 0;

      try {
        $items = $this->armazem->dashDisp(session()->get('Matricula'));
      } catch (\Exception $e) {
        session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
        return  redirect()->route('principal');
      }

      if (isset($items)) {
        $parametros = 1;
        $sArmazem = (new Collection($items))->paginate(10);
        $sArmazem->withPath('apiarmazem');
        return view('armazem.sarmazem',  compact(['sArmazem', 'parametros']));
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function dashFim()
  {
    if (session()->has('Matricula')) {

      $parametros = 0;

      try {
        $items = $this->armazem->dashFim(session()->get('Matricula'));
      } catch (\Exception $e) {
        session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
        return  redirect()->route('principal');
      }

      if (isset($items)) {
        $parametros = 1;
        $sArmazem = (new Collection($items))->paginate(10);
        $sArmazem->withPath('apiarmazem');
        return view('armazem.sarmazem',  compact(['sArmazem', 'parametros']));
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function filtroSA(Request $request)
  {
    $solicitante = session()->get('Matricula');
    $id = $request->codsa;
    $status = $request->status;
    $emissao = $request->daterange;
    $codproduto = $request->codproduto;
    $produto = $request->produto;
    $obs =     $request->obs;
    $parametros = 0;

    list( $inicio1, $fim1 ) = explode( ' - ', $emissao );

    $inicio = implode("",array_reverse(explode("/",$inicio1)));
    $fim = implode("",array_reverse(explode("/",$fim1)));

    if (empty($id)) {$id= " ";}
    if (empty($status)) {$status= " ";}
    if (empty($codproduto)) {$codproduto= " ";}
    if (empty($produto)) {$produto= " ";}
    if (empty($obs)) {$obs= " ";}

    if (session()->has('Matricula')) {

      try {
        $items =  $this->armazem->filtroSA($solicitante, $id, $status, $inicio, $fim, $codproduto, $produto, $obs);
      } catch (\Exception $e) {
        session()->flash('erro', "Falha de comunicação com o servidor, acione a Equipe Protheus!");
        return  redirect()->route('principal');
      }

      if (isset($items)) {
        $parametros = 1;
        $sArmazem = (new Collection($items))->paginate(10);
        $sArmazem->withPath('apiarmazem');
        return view('armazem.sarmazem', compact(['sArmazem', 'parametros']));
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    } 
  }

  public function edit($ID)
  {
    if (session()->has('Matricula')) 
    {

      $items = $this->armazem->saID($ID);
      $produtos = $items[0]->ITENS;

      if($produtos[0]->STATUS != "Requisicao Incluida" )
      {
        $status = 0;
      }else {
        $status = 1;
      }
  
         $centros = $this->cCusto->allCentroCusto();
         return view('armazem.edite', compact(['centros','produtos','ID' ,'status']));
     
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

  public function postSA(Request $request)
  {

    session()->forget('SA');


    $produtos = session()->get('PRODUTOS');
    $centros = $request->centros;
    $qtd =     $request->qntd;
    $obs =     $request->obs;

    foreach ($produtos as $key => $value) {

      if (empty($obs[$key])) {
        $obs[$key] = " ";
      }

      if (empty($centros[$key])) {
        $centros[$key] = " ";
        session()->flash('erro', 'O campo centro de custos deve ser informado!');
        return  redirect()->route('carrinho');
      }

      $SA = [
        'SOLICITANTE'   =>    strval(session()->get('Matricula')),
        'CENTROCUSTO'   =>    $centros[$key],
        'PRODUTO'       =>    $value['produto'],
        'QUANTIDADE'    =>    intval($qtd[$key]),
        'OBSERVACAO'    =>    $obs[$key]
      ];

      if (session()->has('SA')) {
        session()->push('SA', $SA);
      } else {
        session()->put('SA'); //criar o "alias"
        session()->push('SA', $SA);
      }
    }

    $sArmazem = json_encode(session()->get('SA'));
    session()->forget('SA');
    try {

      $response = $this->armazem->postSA($sArmazem);
      
    } catch (\Exception $e) {
      //dd($e);
      session()->flash('erro', 'Falha ao inserir requisição, acione a Equipe Protheus!');
      return  redirect()->route('carrinho');
    }

    if (isset($response)) {
      session()->flash('sucesso', 'Tudo certo! Solicitação ' . $response . ' criada com sucesso!');
      return  redirect()->route('saArmazem');
    }
  }

  public function putSA(Request $request)
  {
    session()->forget('SAEDIT');

    $ID = $request->IDSA;
    $produtos = $request->codigo;
    $centros = $request->centros;
    $qtd =     $request->qntd;
    $obs =     $request->obs;

    foreach ($produtos as $key => $value) {


      if (empty($obs[$key])) {
        $obs[$key] = " ";
      }

      $SAEDIT = [
        'SOLICITANTE'   =>    strval(session()->get('Matricula')),
        'ITEM'          =>    $key,
        'CENTROCUSTO'   =>    $centros[$key],
        'PRODUTO'       =>    $value,
        'QUANTIDADE'    =>    intval($qtd[$key]),
        'OBSERVACAO'    =>    $obs[$key]
      ];

      if (session()->has('SAEDIT')) {
        session()->push('SAEDIT', $SAEDIT);
      } else {
        session()->put('SAEDIT'); //criar o "alias"
        session()->push('SAEDIT', $SAEDIT);
      }
    }

    $sArmazem = json_encode(session()->get('SAEDIT'));
    session()->forget('SAEDIT');

    try {

      $response = $this->armazem->putSA($sArmazem, $ID);
    } catch (\Exception $e) {
      dd($e);
      session()->flash('erro', 'Falha ao alterar requisição ' . $ID . ', Obs: só é possível alterar um item com status de S.A igual a Incluido(a)!');
      return  redirect()->route('saArmazem');
    }

    if (isset($response)) {
      session()->flash('sucesso', 'Tudo certo! Solicitação ' . $response . ' atualizada com sucesso!');
      return  redirect()->route('saArmazem');
    }
  }

  public function deleteitem($ID, $ITEM)
  {
    if (session()->has('Matricula')) {

      try {
        $response = $this->armazem->deleteitemSA($ITEM, $ID);
      } catch (\Exception $e) {
        session()->flash('erro', 'Falha ao deletar o item  ' . $ITEM . '  da S.A ' . $ID . ' , Obs: só é possível deletar um item da S.A com status de Incluido!');
        return   redirect()->route('saedit', $ID);
      }

      if (isset($response)) {
        session()->flash('sucesso', 'Tudo certo!  item ' . $ITEM . ' da S.A ' . $ID . ' deletado com sucesso!');
        try {
          $rep = $this->armazem->saID($ID);  //direcionar para rota corrta
        } catch (\Exception $e) {
          return   redirect()->route('saArmazem');
        }
        if (isset($rep) && $rep != null) {
          return  redirect()->route('saedit', $ID);
        } else {
          return   redirect()->route('saArmazem');
        }
      }
    } else {
      session()->flash('erro', "Usuário não autenticado");
      return  redirect()->route('login');
    }
  }

    public function deleteall($IDSA)
    {

      $items = $this->armazem->saID($IDSA);
      $produtos =  $items[0]->ITENS;
 

      if($produtos[0]->STATUS == "Requisicao Incluida" )
      {
      
        $aItens = [];

        foreach ( $produtos as $key => $value ) 
        {
          array_push($aItens, [ "ITEM" => $value->ITEM]);
        }


      $json  = json_encode($aItens); //json com todos os itens para deleção

      try {

        $response = $this->armazem->deleteall($IDSA, $json);
      } catch (\Exception $e) {
        session()->flash('erro', 'Falha ao deletar  a S.A ' . $IDSA . ' , Obs: só é possível deletar uma S.A com o status de Incluido(a)!');
        return   redirect()->route('saArmazem');
      }
      if (isset($response)) {
        session()->flash('sucesso', 'Tudo certo! todos os itens da S.A ' . $IDSA . ' foram deletados com sucesso!');
        return  redirect()->route('saArmazem');
      }
    } else {
      session()->flash('erro', "Atenção! Só é possível deletar uma S.A com o status de: Requisição Incluida!");
      return redirect()->route('saArmazem');
    }
  }

}
