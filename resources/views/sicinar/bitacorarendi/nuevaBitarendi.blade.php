@extends('sicinar.principal')

@section('title','Nueva bitacora de rendimiento')

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
            <h1>Menú
                <small> Formatos de comprobación - Bitacora de rendimiento - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12"> 
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'AltaNuevaBitarendi', 'method' => 'POST','id' => 'nuevaBitarendi', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Código </label>
                                    <select class="form-control m-bot15" name="placa_id" id="placa_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar codigo de placas</option>
                                        @foreach($regplaca as $placa)
                                            <option value="{{$placa->placa_id}}">{{$placa->placa_id}} - {{$placa->placa_placa}} - {{trim($placa->placa_obs2)}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-3 form-group">
                                    <label >Servidor público que captura  </label>
                                    <input type="text" class="form-control" name="sp_nomb2" id="sp_nomb2" placeholder="Apellido paterno, materno, nombre(s)" required>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Fecha - año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodo as $peri)
                                            <option value="{{$peri->periodo_id}}">{{$peri->periodo_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                                <div class="col-xs-2 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmes as $mesi)
                                            <option value="{{$mesi->mes_id}}">{{$mesi->mes_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                                <div class="col-xs-2 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdia as $diai)
                                            <option value="{{$diai->dia_id}}">{{$diai->dia_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                                <div class="col-xs-3 form-group">
                                    <label >Quincena a comprobar</label>
                                    <select class="form-control m-bot15" name="quincena_id" id="quincena_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar quincena</option>
                                        @foreach($regquincena as $quincena)
                                            <option value="{{$quincena->quincena_id}}">{{$quincena->quincena_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>                                    
                            </div>

                           
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 carácteres) </label>
                                    <textarea class="form-control" name="bitaco_obs1" id="bitaco_obs1" rows="3" cols="120" placeholder="Observaciones (4,000 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales en formato PDF, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo 1 de bitacora de rendimiento de combustible en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="bitaco_foto1" id="bitaco_foto1" placeholder="Subir archivo 1 de bitacora de rendimiento de combustible en formato PDF" >
                                </div>                                                          
                                <div class="col-xs-4 form-group">
                                    <label >Archivo 2 de bitacora de rendimiento de combustible en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="bitaco_foto2" id="bitaco_foto2" placeholder="Subir archivo 2 de bitacora de rendimiento de combustible en formato PDF" >
                                </div>   
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\BitarendiRequest','#nuevaBitarendi') !!}
@endsection

@section('javascrpt')
<script>
  function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
</script>

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