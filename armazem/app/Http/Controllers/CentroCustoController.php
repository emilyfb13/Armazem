<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Exception\GuzzleException;
use GuzzleHttp\Client;
use App\Helpers\Helper;
use App\Repositorios\CCusto;

class CentroCustoController extends Controller
{
  protected $cCusto;

  public function __construct(CCusto $cCusto)
  {
    $this->cCusto = $cCusto;
  }

  public function index()
  {
    $centros = $this->cCusto->allCentroCusto();
    return view('armazem.centroCusto', compact('centros'));
  }
}
