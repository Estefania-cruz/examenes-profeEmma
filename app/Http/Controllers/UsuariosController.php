<?php

namespace App\Http\Controllers;

use App\Mail\ResultacorreoMailable;
use App\Models\Examenn;
use App\Models\preguntas;
use App\Models\Respuestastd;
use App\Models\Usuario;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Session;

class UsuariosController extends Controller
{
    public function login()
    {
        return view('usuariologiin');
    }
    public function registraar()
    {
        return view('usuarioregistrartd');
    }

    public function usuarioHome()
    {
        return view('usuariohomeetd');
    }

    public function usuarioViewExamenAll()
    {
        $examen = Examenn::all();
        return view('usuario-examenes', ['examenes' => $examen]);
    }

    public function misExamenesDatos()
    {
        $usuario = Usuario::where('id', session('usuario')->id)->first();
        $examenes = Examenn::where('id', $usuario->examen_id)->first();
        return json_encode(['estatus' => 'success', 'mensaje' => 'Tus examenes', 'examenes' => $examenes, 'usuario' => $usuario]);
    }

    public function misExamenes()
    {
        return view('mis-examenes');
    }

    public function contestarExamen($id)
    {
        $examen = Examenn::where('id', $id)->first();
        $preguntas = preguntas::where('examen_id', $id)->get();
        $respuesta = Respuestastd::where('usuario_id', session('usuario')->id)->get()->count();
        if($respuesta)
            return view('examen-ok', ['estatus' => 'success', 'mensaje' => 'Examen contestado']);

        $usuario = Usuario::find(session('usuario')->id);
        $usuario->examen_id = $id;
        $usuario->save();

        $usuarioMail = Usuario::where('id', session('usuario')->id)->first();

        $correo =  new ResultacorreoMailable();
        Mail::to($usuarioMail->correo)->send($correo);

        return view('contestar-examen', ['examen' => $examen ,'preguntas' => $preguntas]);
    }

    public function registro(Request $datos)
    {
        if(!$datos->nombre || !$datos->app || !$datos->apm || !$datos->correo || !$datos->password1 || !$datos->password2)
            return view("usuarioregistrartd",["estatus"=> "error", "mensaje"=> "¡Falta información!"]);

        $verificar = Usuario::where('correo', $datos->correo)->first();

        if ($verificar)
            return view('usuarioregistrartd', ['estatus' => 'error' ,'mensaje' => 'Ya existe esa cuenta']);

        if ($datos->password1 != $datos->password2)
            return view('usuarioregistrartd', ['estatus' => 'error' ,'mensaje' => 'Las contraseñas no coinciden']);

        $datos->validate([
            'correo' => 'required|email',
        ]);

        $examen = Examenn::orderBy('created_at','DESC')->first();

        $nombre = $datos->nombre;
        $app = $datos->app;
        $apm = $datos->apm;
        $correo = $datos->correo;
        $password = $datos->password1;

        $usuario = new Usuario();
        $usuario->nombre = $nombre;
        $usuario->apellido_pat = $app;
        $usuario->apellido_mat = $apm;
        $usuario->correo = $correo;
        $usuario->password = bcrypt($password);
        $usuario->examen_id = $examen->id;
        $usuario->save();

        return view('usuariologiin', ['estatus' => 'success' ,'mensaje' => 'Usuario registrado']);

    }

    public function verificarCredenciales(Request $datos)
    {
        if (!$datos->correo || !$datos->password)
            return view('usuariologiin', ['estatus' => 'error', 'mensaje' => '¡Falta información!']);

        $usuario = Usuario::where('correo', $datos->correo)->first();

        if (!$usuario)
            return view('usuariologiin', ['estatus' => 'error', 'mensaje' => '¡No existe esa cuenta!']);

        if (!Hash::check($datos->password, $usuario->password))
            return view('usuariologiin', ['estatus' => 'error', 'mensaje' => '¡Datos incorrectos!']);

        Session::put('usuario', $usuario);

        if(isset($datos->url)){
            $url = decrypt($datos->url);
            return redirect($url);
        }else{
            return redirect()->route('usuariohommetd');
        }
    }

    public function updateUsuario(Request $datos)
    {
        $usuario = Usuario::find($datos->id);
        $usuario->nombre = $datos->nombre;
        $usuario->apellido_pat = $datos->app;
        $usuario->apellido_mat = $datos->apm;
        $usuario->correo = $datos->correo;
        $usuario->save();
    }

    public function cerrarSesion()
    {
        if(Session::has('usuario'))
            Session::forget('usuario');

        return redirect()->route('usuario.login');
    }

}
