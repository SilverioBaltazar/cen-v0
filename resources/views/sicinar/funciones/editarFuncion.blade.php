@extends('sicinar.principal')

@section('title','Editar Funcion')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small>Modelado de procesos - Funciones de proceso - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        
                        {!! Form::open(['route' => ['actualizarFuncion', $regfuncion->funcion_id], 'method' => 'PUT', 'id' => 'actualizarFuncion']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label style="color:green;"><i class="fa fa-circle-o-notch"></i>
                                    Proceso: {{$regfuncion->proceso_id.' '.trim($regfuncion->proceso_desc)}}
                                    </label>
                                </div>       
                            </div>
                            <div class="row">                         
                                <div class="col-md-3 offset-md-3">
                                    <label>id. función <br> {{$regfuncion->funcion_id}}</label>
                                </div>  
                                       
                                <div class="col-xs-3 form-group">
                                    <label>Función </label>
                                    <input type="text" class="form-control" name="funcion_desc" placeholder="Nombre de la función del proceso" value="{{$regfuncion->funcion_desc}}" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label>Activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="funcion_status" required>
                                        @if($regfuncion->funcion_status == 'S')
                                            <option value="S" selected>SI</option>
                                            <option value="N">NO</option>
                                        @else
                                            <option value="S">SI</option>
                                            <option value="N" selected>NO</option>
                                        @endif
                                    </select>
                                </div>
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Guardar',['class' => 'btn btn-primary btn-flat pull-right']) !!}
                                    <a href="{{route('verFuncion')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div><br>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\funcionesRequest','#actualizarFuncion') !!}
@endsection

@section('javascrpt')
@endsection