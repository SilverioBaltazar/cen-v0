@extends('sicinar.principal')

@section('title','Editar fotografia 1 de la unidad')

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
                <small> Padrón - placas - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar fotografía de la unida</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarPlaca1',$regplaca->placa_id], 'method' => 'PUT', 'id' => 'actualizarPlaca1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>Código: {{$regplaca->placa_id}}</label>
                                </div>             
                                <div class="col-xs-4 form-group">
                                    <label >Placas </label>
                                    <input type="text" class="form-control" name="placa_placa" id="placa_placa" placeholder="Placas" value="{{$regplaca->placa_placa}}" required>
                                </div>                               
                            </div>

                            <div class="row">
                                @if (!empty($regplaca->placa_foto1)||!is_null($regplaca->placa_foto1))  
                                    <div class="col-xs-4 form-group">
                                        <label >Fotografía 1 de la unidad en formato jpg, jpeg o png</label>
                                        <label ><a href="/images/{{$regplaca->placa_foto1}}" class="btn btn-danger" title="Fotografía 1  de la unidad en formato jpg, jpeg, png"><i class="fa-file-image-o"></i> jpg, jpeg, png</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-4 form-group">
                                        <label >Actualizar archivo de fotografía 1 de la unidad jpg, jpeg o png</label>
                                        <input type="file" class="text-md-center" style="color:red" name="placa_foto1" id="placa_foto1" placeholder="Subir archivo de Fotografía 1 de la unidad en formato jpg, jpeg, png" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo de fotografía 1 de la unidad en formato jpg, jpeg o png</label>
                                        <input type="file" class="text-md-center" style="color:red" name="placa_foto1" id="placa_foto1" placeholder="Subir archivo de Fotografía 1 de la unidad en formato jpg, jpeg o png" >
                                    </div>                                                
                                @endif 
                            </div>

                            <div class="row">
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verPlacas')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div>
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
    {!! JsValidator::formRequest('App\Http\Requests\placa1Request','#actualizarPlaca1') !!}
@endsection

@section('javascrpt')
@endsection