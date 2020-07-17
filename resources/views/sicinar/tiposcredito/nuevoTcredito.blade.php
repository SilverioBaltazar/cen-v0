@extends('sicinar.principal')

@section('title','Nuevo Tipo de crédito ')

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
            <h1>Menú
                <small> Catálogos - Tipos de Crédito - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        
                        {!! Form::open(['route' => 'AltaNuevoTipocredito', 'method' => 'POST','id' => 'nuevoTipocredito']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-5 form-group">
                                    <div class="col-xs-12">
                                        <label >Tipo de crédito </label>
                                        <input type="text" class="form-control" id="tipocredito_desc" name="tipocredito_desc" placeholder="Digitar tipo de crédito" required>
                                    </div>
                                    <div class="col-xs-4">
                                        <label >Días de crédito </label>
                                        <input type="number" min="0" max="990" class="form-control" id="tipocredito_dias" name="tipocredito_dias" placeholder="Digitar dias del crédito" required>
                                    </div>                                    
                                </div>
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar',['class' => 'btn btn-primary btn-flat pull-right']) !!}
                                    <a href="{{route('verTiposcredito')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\tipocreditoRequest','#nuevoTipocredito') !!}
@endsection

@section('javascrpt')
@endsection
