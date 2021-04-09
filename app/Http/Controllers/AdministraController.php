<?php

namespace App\Http\Controllers;

use App\Models\Administradortd;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Session;

class AdministraController extends Controller
{
    public function welcome(){
        return view('welcome');
    }

    public function login()
    {
        return view('login');
    }

    public function registraar()
    {
        return view('registraar');
    }

    public function home()
    {
        return view('homee-td');
    }


    public function registro(Request $datos)
    {
        //echo "hola mensaje de prueba";
        if(!$datos->nombre || !$datos->app || !$datos->apm || !$datos->correo || !$datos->password1 || !$datos->password2)
            return view("registraar",["estatus"=> "error", "mensaje"=> "¡Falta información!"]);

        $verificar = Administradortd::where('correo', $datos->correo)->first();

        if ($verificar)
            return view('registraar', ['estatus' => 'error' ,'mensaje' => 'Ya existe esa cuenta']);

        if ($datos->password1 != $datos->password2)
            return view('registraar', ['estatus' => 'error' ,'mensaje' => 'Las contraseñas no coinciden']);

        $nombre = $datos->nombre;
        $app = $datos->app;
        $apm = $datos->apm;
        $correo = $datos->correo;
        $password = $datos->password1;


        $administra = new Administradortd();
        $administra->nombre = $nombre;
        $administra->apellido_pat = $app;
        $administra->apellido_mat = $apm;
        $administra->correo = $correo;
        $administra->password = bcrypt($password);
        $administra->save();

        return view('login', ['estatus' => 'success' ,'mensaje' => 'Usuario registrado']);
    }


    public function verificarCredenciales(Request $datos)
    {
        if (!$datos->correo || !$datos->password)
            return view('login', ['estatus' => 'error', 'mensaje' => '¡Falta información!']);

        $administra = Administradortd::where('correo', $datos->correo)->first();

        if (!$administra)
            return view('login', ['estatus' => 'error', 'mensaje' => '¡No existe esa cuenta!']);

        if (!Hash::check($datos->password, $administra->password))
            return view('login', ['estatus' => 'error', 'mensaje' => '¡Datos incorrectos!']);

        Session::put('admin', $administra);

        if(isset($datos->url)){
            $url = decrypt($datos->url);
            return redirect($url);
        }else{
            return redirect()->route('admin.home');
        }
    }

    public function cerrarSesion(){
        if(Session::has('admin'))
            Session::forget('admin');

        return redirect()->route('login');
    }
}
