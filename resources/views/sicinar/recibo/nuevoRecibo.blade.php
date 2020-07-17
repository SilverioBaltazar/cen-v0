@extends('sicinar.principal')

@section('title','Nuevo recibo')

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
                <small>Formatos de comprobación - Recibo de bitacora - Nuevo recibo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => 'AltaNuevoRecibo', 'method' => 'POST','id' => 'nuevoRecibo', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Código </label>
                                    <select class="form-control m-bot15" name="placa_id" id="placa_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar codigo de placa</option>
                                        @foreach($regplaca as $placa)
                                            <option value="{{$placa->placa_id}}">{{$placa->placa_id}} - {{$placa->placa_placa}} - {{trim($placa->placa_obs2)}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-3 form-group">
                                    <label >Número de tarjeta </label>
                                    <input type="text" class="form-control" name="tarjeta_no" id="tarjeta_no" placeholder="Número de tarjeta" required>
                                </div>
                            </div>

                            <div class="row">                                 
                                <div class="col-xs-2 form-group">
                                    <label >KM. inicial </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="recibo_ki" id="recibo_ki" placeholder="Kilometraje inicial" required>
                                </div>                                      

                                <div class="col-xs-4 form-group">
                                    <label >Nivel de combustible inicial</label><br>
                                    R  <input type="checkbox" name="recibo_ir" id="recibo_ir" value="1" placeholder="Nivel de combustible R" required>
                                    1/8<input type="checkbox" name="recibo_i18" id="recibo_i18" value="1" placeholder="Nivel de combustible 1/8" required>
                                    1/4<input type="checkbox" name="recibo_i14" id="recibo_i14" value="1" placeholder="Nivel de combustible 1/4" required>
                                    1/2<input type="checkbox" name="recibo_i12" id="recibo_i12" value="1" placeholder="Nivel de combustible 1/2" required>
                                    3/4<input type="checkbox" name="recibo_i34" id="recibo_i34" value="1" placeholder="Nivel de combustible 3/4" required>
                                    F  <input type="checkbox" name="recibo_if" id="recibo_if" value="1" placeholder="Nivel de combustible F" required>
                                </div>
                            </div>

                            <div class="row">                                                                 
                                <div class="col-xs-2 form-group">
                                    <label >KM. final </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="recibo_kf" id="recibo_kf" placeholder="Kilometraje final" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nivel de combustible final  </label><br>
                                    R  <input type="checkbox" name="recibo_fr" id="recibo_fr" value="1" placeholder="Nivel de combustible R" required>
                                    1/8<input type="checkbox" name="recibo_f18" id="recibo_f18" value="1" placeholder="Nivel de combustible 1/8" required>
                                    1/4<input type="checkbox" name="recibo_f14" id="recibo_f14" value="1" placeholder="Nivel de combustible 1/4" required>
                                    1/2<input type="checkbox" name="recibo_f12" id="recibo_f12" value="1" placeholder="Nivel de combustible 1/2" required>
                                    3/4<input type="checkbox" name="recibo_f34" id="recibo_f34" value="1" placeholder="Nivel de combustible 3/4" required>
                                    F  <input type="checkbox" name="recibo_ff" id="recibo_ff" value="1" placeholder="Nivel de combustible F" required>
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
                                    <label >Día - inicial </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdia as $diai)
                                            <option value="{{$diai->dia_id}}">{{$diai->dia_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-2 form-group">
                                    <label >Día - final </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdia as $diaf)
                                            <option value="{{$diaf->dia_id}}">{{$diaf->dia_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                        
                            </div>

                            <div class="row">
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
                                    <label >Observaciones (200 carácteres) </label>
                                    <textarea class="form-control" name="recibo_obs1" id="recibo_obs1" rows="5" cols="120" placeholder="Observaciones (200 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>


                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verRecibos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\reciboRequest','#nuevoRecibo') !!}
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