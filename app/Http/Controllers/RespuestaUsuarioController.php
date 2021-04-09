<?php

namespace App\Http\Controllers;

use App\Models\Examenn;
use App\Models\preguntas;
use App\Models\Respuestastd;
use App\Models\Usuario;
use Illuminate\Http\Request;

class RespuestaUsuarioController extends Controller
{
    public function verificarRespuestas(Request $datos)
    {

        $respuestas = preguntas::select('respuesta_ok','respuesta2','respuesta3')->where('examen_id', $datos->examen_id)->get();

        $respuesta_ok = [];

        foreach ($respuestas as $respuesta) {
            array_push($respuesta_ok, $respuesta->respuesta_ok);
        }

        foreach ($datos->respuesta as $v) {
            $respuesta = new Respuestastd();
            $respuesta->pregunta_id = $datos->pregunta_id;
            $respuesta->examen_id = $datos->examen_id;
            $respuesta->usuario_id = session('usuario')->id;
            if(in_array($v, $respuesta_ok)){
                $respuesta->respuesta = $v;
                $respuesta->estatus = 1;
            }else{
                $respuesta->respuesta = $v;
                $respuesta->estatus = 0;
            }

            $respuesta->save();
        }

        $usuario_preguntas_total = Respuestastd::where('examen_id', $datos->examen_id)->where('estatus','1')->get()->count();

        $usuario_preguntas_total_mal = Respuestastd::where('examen_id', $datos->examen_id)->where('estatus','0')->get()->count();

        $usuario = Usuario::find(session('usuario')->id);
        $usuario->total_respuestas = $usuario_preguntas_total;
        $usuario->total_respuestas_mal = $usuario_preguntas_total_mal;
        $usuario->save();

        return view('examen-ok', ['estatus' => 'success', 'mensaje' => 'Examen contestado']);
    }

    public function mail($idexamen)
    {
        $examen = Examenn::where('id', $idexamen)->first();
        return json_encode($examen);
    }

}
