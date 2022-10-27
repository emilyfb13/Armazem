<?php
namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositorios\Login;
use GuzzleHttp\Client as GuzzleClient;

use App\Helpers\Helper;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */


    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';
    protected $login;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(Login $login)
    {
        $this->middleware('guest')->except('logout');
        $this->login = $login;
    }



    public function index()
    {
        if(session()->has('Matricula'))
        { 
            return redirect()->route('principal');  
        }else{
            return view('login.login');
            //return redirect()->route('login'); 
        }
    }

    public function getLogin(Request $request)
    {

        $regras = [
            'user'=>'required',
            'senha'=>'required'
        ];

        $message  = [
            'required' => 'Este campo não pode estar em branco!',
        ];

        $this->validate($request, $regras, $message );

        $user = $request->user;  
        $senha = md5($request->senha);

        $auth = $this->login->LoginGet($user, $senha);

        if (empty($auth)){
            session()->flash('erro', 'Usuário ou senha incorreta.');
            return back()->withInput();
        }else{
            
            $Nome =  $auth[0]->NOME;
            $primeiroNome = explode(" ", $Nome);

            $ajuste =   ucfirst(strtolower($primeiroNome[0]));

            session()->put('NomeUsuario',  $ajuste);
            session()->put('Matricula',  $auth[0]->MATRICULA);
            return redirect()->route('principal');
        } 
    }

    public function postLogin(Request $request)
    {

        $regras = [
            'cpf'=>'required',
            'matricula'=>'required',
            'senha'=>'required'
        ];

        $message  = [
            'required' => 'Este campo não pode estar em branco!',
        ];

        $this->validate($request, $regras, $message );

        $cpf = $request->cpf;  
        $matricula = $request->matricula;
        $mae = $request->mae;
        $senha = $request->senha;
        $exist = $this->login->LoginGet($cpf, $senha);
        $auth = $this->login->Verifica($cpf, $matricula, $mae);
        
        if (!empty($exist)){
            session()->flash('erro', 'Usuário já existente!');
            session()->put('NomeUsuario',  $exist[0]->NOME);
            session()->put('Matricula',  $exist[0]->MATRICULA);
            return redirect()->route('principal');
        }
        elseif (empty($auth)){
            session()->flash('erro', 'Dados de verificação incorretos, revise os campos CPF, Matricula e Mãe.');
            return back()->withInput();
        }else{
            
            $LOGIN = [
                'USER'   => $cpf,
                'SENHA'   => md5($senha)
            ];

            $login = json_encode($LOGIN);

            try {
                $response = $this->login->postLogin($login);
            } catch (\Exception $e) {
                session()->flash('erro', 'Falha ao incluir usuário, acione o suporte!');
                return  redirect()->route('login');
            }
            
            if (isset($response)) {
                session()->flash('sucesso', 'Usuário ' . $response->USER . ' criado com sucesso!');
                return redirect()->route('login');
            }
        } 
    }

    public function putLogin(Request $request)
    {

        $regras = [
            'cpf'=>'required',
            'matricula'=>'required',
            'senha'=>'required'
        ];

        $message  = [
            'required' => 'Este campo não pode estar em branco!',
        ];

        $this->validate($request, $regras, $message );

        $cpf = $request->cpf;  
        $matricula = $request->matricula;
        $mae = $request->mae;
        $senha = $request->senha;
        $auth = $this->login->Verifica($cpf, $matricula, $mae);
        
        if (empty($auth)){
            session()->flash('erro', 'Dados de verificação incorretos, revise os campos CPF, Matricula e Mãe.');
            return back()->withInput();
        }else{

            $LOGIN = [
                'USER'   => $cpf,
                'SENHA'   => md5($senha)
            ];

            $login = json_encode($LOGIN);
           
            try {
                $response = $this->login->putLogin($login);
               
            } catch (\Exception $e) {
                dd($e);
                session()->flash('erro', 'Falha ao inserir requisição, acione a Equipe Protheus!');
                return  redirect()->route('login');
            }

            if (isset($response)) {

                if (isset($response->USER)){
                    session()->flash('sucesso', 'Senha do usuario ' . $response->USER . ' alterada com sucesso!');
                    return  redirect()->route('login');
                }else{
                    session()->flash('erro', '' .$response.'. Entre em contato com o suporte.');
                    return  redirect()->route('login');
                }
            }
        } 
    }

    public function sair()
    {
        
        session()->flush();
    
        return  redirect()->route('login');  
        //return view('login.login');
    }

    public function loginRedirect()
    {
        return  redirect()->route('login');        
    }

}
