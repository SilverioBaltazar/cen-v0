@extends('sicinar.principal')

@section('title','Nuevo servicio')

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
                <small>Formatos de comprobación - Bitacora de rendimiento - Nuevo servicio</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'AltaNuevoServicio', 'method' => 'POST','id' => 'nuevoServicio', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                        <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regbitarendi as $bitarendi)                                               
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_id" name="placa_id" value="{{$bitarendi->placa_id}}">  
                                    <label>Código : </label><b>{{$bitarendi->placa_id}} </b>
                                </td>
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_placa" name="placa_placa" value="{{$bitarendi->placa_placa}}">  
                                    <label>Placas : </label><b>{{$bitarendi->placa_placa}}</b>
                                </td>
                                <td style="text-align:left; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$bitarendi->periodo_id}}">  
                                    <label>Periodo fiscal : </label>{{$bitarendi->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="mes_id" name="mes_id" value="{{$bitarendi->mes_id}}">  
                                    <label>Mes : </label><b>
                                    @foreach($regmes as $mes)
                                        @if($mes->mes_id == $bitarendi->mes_id)
                                            {{$mes->mes_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>
                                </td>                                
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="quincena_id" name="quincena_id" value="{{$bitarendi->quincena_id}}">  
                                    <label>Quincena : </label>
                                    @foreach($regquincena as $quincena)
                                        @if($quincena->quincena_id == $bitarendi->quincena_id)
                                            {{$quincena->quincena_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                     
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="bitaco_folio" name="bitaco_folio" value="{{$bitarendi->bitaco_folio}}">  
                                    <label>Folio : </label>{{$bitarendi->bitaco_folio}}
                                </td>                                                                
                            </tr>      
                            @endforeach     
                        </table>

                        <table id="tabla1" class="table table-hover table-striped" border="1" style="color: green;">
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Fecha del servicio - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodo as $peri)
                                            <option value="{{$peri->periodo_id}}">{{$peri->periodo_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                                <div class="col-xs-3 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmes as $mesi)
                                            <option value="{{$mesi->mes_id}}">{{$mesi->mes_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                                <div class="col-xs-3 form-group">
                                    <label >Dia </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdia as $diai)
                                            <option value="{{$diai->dia_id}}">{{$diai->dia_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                            </div>

                            <div class="row">  
                                <div class="col-xs-3 form-group">
                                    <label >Servidora o servidor público (apellido paterno, materno, nombre) </label>
                                    <input type="text" class="form-control" name="sp_nomb" id="sp_nomb" placeholder="Servidora o servidor público (apellido paterno, materno, nombre)" onkeypress="return soloAlfa(event)" required>
                                </div>                                                           
                                <div class="col-xs-3 form-group">
                                    <label >Dotación en pesos mexicanos $ </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="servicio_dotación" id="servicio_dotacion" placeholder="Dotación de combustible en S pesos mexicanos" required>
                                </div>  
                            </div>

                            <div class="row">                                 
                                <div class="col-xs-3 form-group">
                                    <label >Nivel de combustible </label><br>
                                </div>
                                <div class="col-xs-4 form-group">
                                    R  <input type="checkbox" name="servicio_r" id="servicio_r" value="1" placeholder="Nivel de combustible R" required>
                                    1/8<input type="checkbox" name="servicio_18" id="servicio_18" value="1" placeholder="Nivel de combustible 1/8" required>
                                    1/4<input type="checkbox" name="servicio_14" id="servicio_14" value="1" placeholder="Nivel de combustible 1/4" required>
                                    1/2<input type="checkbox" name="servicio_12" id="servicio_12" value="1" placeholder="Nivel de combustible 1/2" required>
                                    3/4<input type="checkbox" name="servicio_34" id="servicio_34" value="1" placeholder="Nivel de combustible 3/4" required>
                                    F  <input type="checkbox" name="servicio_f" id="servicio_f" value="1" placeholder="Nivel de combustible F" required>
                                </div>                                
                            </div>

                            <div class="row">  
                                <div class="col-xs-3 form-group">
                                    <label >Km. inicial </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="km_inicial" id="km_inicial" placeholder="Km. inicial" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Km. final </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="km_final" id="km_final" placeholder="Km. final" required>
                                </div>                                                                      
                            </div>

                            <div class="row">                                                                 
                                <div class="col-xs-8 form-group">
                                    <label >Lugar de la comisión </label>
                                    <input type="text" class="form-control" name="servicio_lugar" id="servicio_lugar" placeholder="Lugar de la comisión" required>
                                </div>
                            </div>
                            <div class="row">                                                                 
                                <div class="col-xs-3 form-group">
                                    <label >Hr. de salida (hh:mm)</label>
                                    <input type="text" class="form-control" name="servicio_hrsalida" id="servicio_hrsalida" placeholder="Hr. de salida al lugar de la comisión (hh:mm)" required>
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label >Hr. de regreso (hh:mm)</label>
                                    <input type="text" class="form-control" name="servicio_hrregreso" id="servicio_hrregreso" placeholder="Hr. de rgreso del lugar de la comisión (hh:mm)" required>
                                </div>                                                                
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 carácteres) </label>
                                    <textarea class="form-control" name="servicio_obs" id="servicio_obs" rows="3" cols="120" placeholder="Observaciones (4,000 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>                            
                        </table>
                            
                            <div class="row">
                                <div class="col-md-12 offset-md-5"> 
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    
                                    @foreach($regbitarendi as $bitarendi)
                                       <a href="{{route('verServicios',array($bitarendi->periodo_id,$bitarendi->bitaco_folio))}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
                                       </a>
                                       @break
                                    @endforeach
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
    {!! JsValidator::formRequest('App\Http\Requests\bitaservicioRequest','#nuevoServicio') !!}
@endsection

@section('javascrpt')
<script>
    function soloNumeros(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "1234567890";
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