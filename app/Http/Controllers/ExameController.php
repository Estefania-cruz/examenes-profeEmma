<?php

namespace App\Http\Controllers;


use App\Models\Examenn;

use App\Models\preguntas;

use App\Models\Respuestastd;
use App\Models\Usuario;
use Illuminate\Http\Request;

class ExameController extends Controller
{

    public function viewExamen()
    {
        return view('crearlosexamentd');
    }

    public function verExamen($id)
    {
        $examen = Examenn::where('id', $id)->first();

        $pregunta = preguntas::where('examen_id', $id)->get()->count();

        $respuestaOK = Respuestastd::where('examen_id', $id)->where('estatus', 1)->get()->count();
        $respuestaError = Respuestastd::where('examen_id', $id)->where('estatus', 0)->get()->count();

        $usuarios = Usuario::where('examen_id', $id)->get()->count();
        $usuarios_examen = Usuario::where('examen_id', $id)->get();

        $mejores = Usuario::where('examen_id', $id)->orderBy('total_respuestas','DESC')->take(5)->get();
        $peores = Usuario::where('examen_id', $id)->orderBy('total_respuestas','ASC')->take(5)->get();

        return view('ver-examen', ['examen' => $examen, 'pregunta' => $pregunta, 'respuestaOK' => $respuestaOK, 'respuestaError' => $respuestaError, 'usuarios' => $usuarios, 'usuarios_examen' => $usuarios_examen, 'mejores' => $mejores, 'peores' => $peores]);
    }

    public function viewExamenAll()
    {
        $examen = Examenn::all();
        return view('examene', ['examenes' => $examen]);
    }

    public function crearExamen(Request $datos)
    {
        if (!$datos->nombre)
            return view('crearlosexamentd');

        $examen = new Examenn();
        $examen->nombre = $datos->nombre;
        $examen->administrador_id = session('admin')->id;
        $examen->save();

        $examenOk = Examenn::orderBy('created_at','DESC')->first();

        return view('crearlosexamentd', ['examen' => $examenOk, "estatus" => 'succes', 'mensaje' => '¡Examen creado, añade las preguntas!']);

    }

    public function crearPreguntas(Request $datos){

        for ($i=0; $i < 1; $i++) {
            $pregunta = new Preguntas();
            $pregunta->texto = $datos->texto[$i];
            $pregunta->respuesta_ok = $datos->pregunta1[$i];
            $pregunta->respuesta2 = $datos->pregunta2[$i];
            $pregunta->respuesta3 = $datos->pregunta3[$i];
            $pregunta->examen_id = $datos->examen_id;
            $pregunta->save();
        }
        return view('crearlosexamentd', ['estatusP' => 'success', 'mensaje' => '¡Preguntas añadidas!']);
    }

}
