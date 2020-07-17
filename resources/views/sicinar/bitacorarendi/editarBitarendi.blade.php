@extends('sicinar.principal')

@section('title','Editar Bitacora de rendimiento')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small>Formatos de comprobación - Bitacora de rendimiento - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarBitarendi',$regbitarendi->bitaco_folio], 'method' => 'PUT', 'id' => 'actualizarBitarendi', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group" style="text-align:left;">
                                    <label>Codigo:{{$regbitarendi->placa_id}}  </label>
                                </div>
                                <div class="col-xs-4 form-group" style="text-align:center;">
                                    <label>Placas: <small>
                                        @foreach($regplaca as $placa)
                                                @if($placa->placa_id == $regbitarendi->placa_id)
                                                    {{$placa->placa_placa}}
                                                    @break
                                                @endif
                                        @endforeach 
                                        </small>
                                    </label>                                    
                                </div>
                                <div class="col-xs-4 form-group" style="text-align:right;">
                                    <label>Folio:{{$regbitarendi->bitaco_folio}}</label>
                                </div>             
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Servidor público que captura </label>
                                    <input type="text" class="form-control" name="sp_nomb2" id="sp_nomb2" placeholder="Apellido paterno, materno, nombre(S)" value="{{$regbitarendi->sp_nomb2}}"  required>
                                </div>                                                                
                            </div>

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Fecha - año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodo as $peri)
                                            @if($peri->periodo_id == $regbitarendi->periodo_id1)
                                                <option value="{{$peri->periodo_id}}" selected>{{$peri->periodo_desc}}</option>
                                            @else                                        
                                                <option value="{{$peri->periodo_id}}">{{$peri->periodo_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div> 
                                <div class="col-xs-2 form-group">
                                    <label >Mes    </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmes as $mesi)
                                            @if($mesi->mes_id == $regbitarendi->mes_id1)
                                                <option value="{{$mesi->mes_id}}" selected>{{$mesi->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mesi->mes_id}}">{{$mesi->mes_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Día  </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día </option>
                                        @foreach($regdia as $diai)
                                            @if($diai->dia_id == $regbitarendi->dia_id1)
                                                <option value="{{$diai->dia_id}}" selected>{{$diai->dia_desc}}</option>
                                            @else                                        
                                               <option value="{{$diai->dia_id}}">{{$diai->dia_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>              
                                <div class="col-xs-3 form-group">
                                    <label >Quincena a comprobar </label>
                                    <select class="form-control m-bot15" name="quincena_id" id="quincena_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar quincena </option>
                                        @foreach($regquincena as $quincena)
                                            @if($quincena->quincena_id == $regbitarendi->quincena_id)
                                                <option value="{{$quincena->quincena_id}}" selected>{{$quincena->quincena_desc}}</option>
                                            @else                                        
                                               <option value="{{$quincena->quincena_id}}">{{$quincena->quincena_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                                                                                                  
                            </div>

                            <div class="row">                                 
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 carácteres) </label>
                                    <textarea class="form-control" name="bitaco_obs1" id="bitaco_obs1" rows="2" cols="120" placeholder="Observaciones (4,000 carácteres)" required>{{Trim($regbitarendi->bitaco_obs1)}}
                                    </textarea>
                                </div>                                
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
                                    <a href="{{route('verBitarendi')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\bitarendiRequest','#actualizarBitarendi') !!}
@endsection

@section('javascrpt')
<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: '-29y',
        endDate: '-18y',
        startView: 2,
        maxViewMode: 2,
        clearBtn: true,        
        language: "es",
        autoclose: true
    });
</script>

@endsection
