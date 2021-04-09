<?php

namespace App\Http\Controllers;

use App\Models\Usuario;
use Illuminate\Http\Request;

class UsuariotdPDFController extends Controller
{
    public function PDF($id)
    {
        $usuario = Usuario::where('id', $id)->first();

        $pdf = PDF::loadView('usuario-pdf', compact('usuario'));
//$pdf = PDF::loadView('usuario-pdf', compact('admin'));
        //manda error corregir
        //tercer intento manda error

        return $pdf->download($usuario->nombre.'_'.$usuario->id.'.pdf');
    }
}
