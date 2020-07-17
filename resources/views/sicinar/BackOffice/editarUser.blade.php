@extends('sicinar.principal')

@section('title','Usuarios')

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper" id="principal">
        <section class="content-header">
            <h1><i class="fa fa-users"></i>Usuarios</h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="{{route('verUser')}}">Usuarios</a></li>
                <li class="active">Editar</li>
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        <div class="box-header with-border">
                            <h3 class="box-title"><b>Editar usuario</b></h3>
                            <a href="{{route('verUser')}}" class="btn btn-primary pull-right" title="Ver usuarios"><i class="fa fa-users"> Ver usuarios</i></a>
                        </div>
                        {!! Form::open(['route' => ['actualizarUser',$users->user_id], 'method' => 'PUT', 'id' => 'actualizarUser']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Login </label>
                                    <input type="text" class="form-control" name="user_name" id="user_name" placeholder="Login de usuario Correo Electrónico" value="{{$users->user_name}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Password</label>
                                    <input type="text" class="form-control" name="user_password" id="user_password" placeholder="Contraseña" value="{{$users->user_password}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label>Apellido paterno</label>
                                    <input type="text" class="form-control" name="user_ap" id="user_ap" placeholder="Apellido paterno" value="{{$users->user_ap}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Apellido materno</label>
                                    <input type="text" class="form-control" name="user_am" id="user_am" placeholder="Apellido materno" value="{{$users->user_am}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label>Nombre(s)</label>
                                    <input type="text" class="form-control" name="user_names" id="user_names" placeholder="Nombre(s)" value="{{$users->user_names}}" required>
                                </div>
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Departamento </label>
                                    <select class="form-control m-bot15" name="depto_id" id="depto_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar departamento</option>
                                        @foreach($deptos as $dependen)
                                            @if($dependen->depto_id == $users->depto_id)  
                                                <option value="{{$dependen->depto_id}}" selected>{{$dependen->depto_desc.'-'.$dependen->depto_id}}</option>
                                            @else 
                                               <option value="{{$dependen->depto_id}}">{{$dependen->depto_desc.'-'.$dependen->depto_id}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div> 
                            </div>

                            <div class="row">                            
                                <div class="col-xs-4 form-group">                        
                                    <label>Activo o inactivo </label>
                                    <select class="form-control m-bot15" name="status" id="status" required>
                                        @if($users->status == 'S')
                                            <option value="S" selected>Si</option>
                                            <option value="N">         No</option>
                                        @else
                                            <option value="S">         Si</option>
                                            <option value="N" selected>No</option>
                                        @endif
                                    </select>
                                </div>                                                                                                  
                                <div class="col-xs-4 form-group">                        
                                    <label>Rol </label>
                                    <select class="form-control m-bot15" name="rol_id" id="rol_id" required>
                                    <option selected="true" disabled="disabled">Selecciona Rol</option>
                                    @foreach($roles as $rol)
                                        @if($rol->rol_id == $users->rol_id)
                                            <option value="{{$rol->rol_id}}" selected>{{$rol->rol_name.' '.$rol->rol_desc}}</option>
                                        @else
                                            <option value="{{$rol->rol_id}}">{{$rol->rol_name.' '.$rol->rol_desc}}</option>
                                        @endif
                                    @endforeach
                                    </select>
                                </div>                                
                            </div>

                            @if(count($errors) > 0)
                                <div class="alert alert-danger" role="alert">
                                    <ul>
                                        @foreach($errors->all() as $error)
                                            <li><i class="fa fa-warning"></i> {{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            @endif
                            <br>
                            <div class="col-md-12 offset-md-5">
                                {!! Form::submit('Actualizar',['class' => 'btn btn-primary btn-block']) !!}
                            </div>
                        </div>
                    </div>
                    {!! Form::close() !!}
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\userRequest','#actualizarUser') !!}
@endsection

@section('javascrpt')
    <script type="text/javascript">
        var unidad = document.getElementById('unidad');
        function dis(elemento) {
            t = elemento.value;
            if(t == "1"){
                unidad.disabled = false;
            }else{
                unidad.disabled = true;
            }
        }
    </script>
@endsection