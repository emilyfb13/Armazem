<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Repositorios\SArmazem;
use App\Repositorios\CCusto;
use App\Support\Collection;

class PrincipalController extends Controller
{
    public function __construct(SArmazem $armazem)
    {
        $this->armazem = $armazem;
        $this->middleware('guest')->except('logout');
    }

    public function  index()
    {
        if (session()->has('Matricula')) {

            $itens1 = $this->armazem->dashInc(session()->get('Matricula'));
            $itens2 = $this->armazem->dashFim(session()->get('Matricula'));
            $itens3 = $this->armazem->dashPC(session()->get('Matricula'));
            $itens4 = $this->armazem->dashDisp(session()->get('Matricula'));

            $sArmazem1 = count(new Collection($itens1));
            $sArmazem2 = count(new Collection($itens2));
            $sArmazem3 = count(new Collection($itens3));
            $sArmazem4 = count(new Collection($itens4));
    
            return view('index', ['inc' => $sArmazem1, 'fim' => $sArmazem2, 'pc' => $sArmazem3, 'disp' => $sArmazem4]);
              
        } else {
            session()->flash('erro', "Usuário não autenticado");
            return  redirect()->route('login');
        }
    }
}
