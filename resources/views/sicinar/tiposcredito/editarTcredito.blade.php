@extends('sicinar.principal')

@section('title','Editar Tipo de crédito ')

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
                <small> Catálogos - Tipo de crédito - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarTipocredito',$regtipocredito->tipocredito_id], 'method' => 'PUT', 'id' => 'actualizarTipocredito']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>id. : {{$regtipocredito->tipocredito_id}}</label>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Tipo de crédito </label>
                                    <input type="text" class="form-control" name="tipocredito_desc" id="tipocredito_desc" placeholder="Tipo de crédito" value="{{trim($regtipocredito->tipocredito_desc)}}" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label>Días de crédito </label>
                                    <input type="number" min="0" max="999" class="form-control" name="tipocredito_dias" id="tipocredito_dias" placeholder="Días de crédito" value="{{$regtipocredito->tipocredito_dias}}" required>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="tipocredito_status" required>
                                        @if($regtipocredito->tipocredito_status == 'S')
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
                                    <a href="{{route('verTiposcredito')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\tipocreditoRequest','#actualizarTipocredito') !!}
@endsection

@section('javascrpt')
@endsection
