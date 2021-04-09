<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return redirect()->route('welcome');
});
Route::get('/welcome', [\App\Http\Controllers\AdministraController::class, 'welcome'])->name('welcome');
Route::get('/login', [\App\Http\Controllers\AdministraController::class, 'login'])->name('login');
Route::get('/registrarme', [\App\Http\Controllers\AdministraController::class, 'registraar'])->name('registrar');
Route::post('/registrarme', [\App\Http\Controllers\AdministraController::class, 'registro'])->name('registroForm');
Route::post('/login', [\App\Http\Controllers\AdministraController::class, 'verificarCredenciales'])->name('verificarCredenciales');

//usuario1
Route::get('/usuario/login', [\App\Http\Controllers\UsuariosController::class, 'login'])->name('usuario.login');
Route::post('/usuario/login', [\App\Http\Controllers\UsuariosController::class, 'verificarCredenciales'])->name('usuario.verificarCredenciales');
Route::get('/usuario/registrarse', [\App\Http\Controllers\UsuariosController::class, 'registraar'])->name('usuario.registrar');
Route::post('/usuario/registrarse', [\App\Http\Controllers\UsuariosController::class, 'registro'])->name('usuario.registroForm');
Route::get('/cerrarsesion-admin', [\App\Http\Controllers\AdministraController::class, 'cerrarSesion'])->name('admin.cerrarSesion');
Route::get('/cerrarsesion', [\App\Http\Controllers\UsuariosController::class, 'cerrarSesion'])->name('usuario.cerrarSesion');

   Route::prefix('/administrador')->middleware('AdministradorVerificaar')->group(function (){
       Route::get('/prueba',[\App\Http\Controllers\ExameController::class,'prueba']);
    Route::get('/home', [\App\Http\Controllers\AdministraController::class, 'home'])->name('admin.home');

    //examen1
    Route::get('/crear-examen', [\App\Http\Controllers\ExameController::class, 'viewExamen'])->name('admin.viewExamen');
    Route::post('/crear-examen', [\App\Http\Controllers\ExameController::class, 'crearExamen'])->name('admin.crearExamen');
    Route::post('/crear-examen', [\App\Http\Controllers\ExameController::class, 'crearPreguntas'])->name('admin.examen.preguntas');
    Route::get('/examenes', [\App\Http\Controllers\ExameController::class, 'viewExamenAll'])->name('admin.viewExamenAll');
    Route::get('/examen/{id}', [\App\Http\Controllers\ExameController::class, 'verExamen'])->name('admin.verExamen');

    //pdf1
    Route::get('/usuarioPDF/{id}', [\App\Http\Controllers\UsuariotdPDFController::class, 'PDF'])->name('usuario.downPDF');
});

Route::prefix('/usuario')->middleware('UsuariosVeriificaar')->group(function (){
    Route::get('/home', [\App\Http\Controllers\UsuariosController::class, 'usuarioHome'])->name('usuario.home');
    Route::get('/examenes', [\App\Http\Controllers\UsuariosController::class, 'usuarioViewExamenAll'])->name('usuario.viewExamenAll');
    Route::get('/contestar/{id}', [\App\Http\Controllers\UsuariosController::class, 'contestarExamen'])->name('usuario.contestarExamen');
    Route::post('/verificar-respuestas', [\App\Http\Controllers\RespuestaUsuarioController::class, 'verificarRespuestas'])->name('usuario.verificarRespuestas');
    Route::get('/mis-examenes', [\App\Http\Controllers\UsuariosController::class, 'misExamenes'])->name('usuario.misExamenes');
    Route::post('/datos-examen/{idexamen}', [\App\Http\Controllers\RespuestaUsuarioController::class, 'mail'])->name('usuario.mail');
    Route::post('/mis-examenes', [\App\Http\Controllers\UsuariosController::class, 'misExamenesDatos'])->name('usuario.misExamenesDatos');
    Route::post('/update-usuario', [\App\Http\Controllers\UsuariosController::class, 'updateUsuario'])->name('usuario.update');
});



