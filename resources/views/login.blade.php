@extends('layout.inniciotd')

@section('titulo')
	<title>Logiin :)</title>
@endsection

@section('estilos')
	<style>
		.bg-personalizado{
            color: darksalmon;
			width: 100%;
			height: 100vh;
			display: flex;
			justify-content: center;
			align-items: center;
		}
		img{
			object-fit: cover;
		}
	</style>
@endsection

@section('contenido')
	<div class="bg-personalizado">
		<div class="container-md h-75">
			<div class="row h-75 overflow-hidden">
				<img src="https://images.app.goo.gl/VYmNfks8HdSQe76LA" class="w-50 col" alt="">
				<div class="col">
					<h1 class="text-center mb-2">Inicia sesion</h1>
					@if (isset($estatus))
						@if ($estatus == 'error')
							<div class="alert alert-danger">
								{{ $mensaje }}
							</div>
						@else
							<div class="alert alert-success">
								{{ $mensaje }}
							</div>
						@endif
					@endif
					<form action="{{ route('verificarCredenciales') }}" method="POST">
						@csrf
						<div class="input-group mb-3">
						  	<span class="input-group-text" id="basic-addon1">@</span>
						  	<input type="text" name="correo" class="form-control" placeholder="Correo" aria-label="Username" aria-describedby="basic-addon1">
						</div>
						<div class="input-group">
							<input class="form-control" name="password" type="password" placeholder="Cpntraseña">
						</div>
						<div class="col-12">
							<button type="submit" class="btn btn-success w-100 btn-md mt-2">Entrar</button>
						</div>
						@if(isset($_GET["r"]))
                            <input type="hidden" name="url" value="{{$_GET["oops"]}}">
                        @endif
					</form>
					<hr>
					<a href="{{ route('registrar') }}" class="text-sm-left text-primary">¡Registrate quí por favor!</a>
				</div>
			</div>
		</div>
	</div>
@endsection
