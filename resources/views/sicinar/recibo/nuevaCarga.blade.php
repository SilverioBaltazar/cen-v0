@extends('sicinar.principal')

@section('title','Nueva carga')

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
                <small> Formatos de comprobación - Recibo de bitacora - Nueva carga</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                    
                        {!! Form::open(['route' => 'AltaNuevaCarga', 'method' => 'POST','id' => 'nuevaCarga', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                        <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regrecibos as $recibo)                                               
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="placa_id" name="placa_id" value="{{$recibo->placa_id}}">  
                                    <label >Código : </label><b>{{$recibo->placa_id}}</b>
                                </td>
                                <td style="text-align:left; vertical-align: middle; color:green;"> 
                                    <input type="hidden" id="placa_placa" name="placa_placa" value="{{$recibo->placa_placa}}">  
                                    <label >Placas : </label><b>{{$recibo->placa_placa}}</b>
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$recibo->periodo_id}}">  
                                    <label>Periodo fiscal : </label>{{$recibo->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="mes_id" name="mes_id" value="{{$recibo->mes_id}}">  
                                    <label>Mes : </label><b>
                                    @foreach($regmes as $mes)
                                        @if($mes->mes_id == $recibo->mes_id)
                                            {{$mes->mes_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>
                                </td>                                
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="quincena_id" name="quincena_id" value="{{$recibo->quincena_id}}">  
                                    <label>Quincena : </label>
                                    @foreach($regquincena as $quincena)
                                        @if($quincena->quincena_id == $recibo->quincena_id)
                                            {{$quincena->quincena_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                     
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="recibo_folio" name="recibo_folio" value="{{$recibo->recibo_folio}}">  
                                    <label>Folio : </label>{{$recibo->recibo_folio}}
                                </td>                                                                
                            </tr>      
                            @endforeach     
                        </table>

                        <table id="tabla1" class="table table-hover table-striped" border="1" style="color: green;">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de emisión del ticket de pago -año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodo as $peri)
                                            <option value="{{$peri->periodo_id}}">{{$peri->periodo_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmes as $mesi)
                                            <option value="{{$mesi->mes_id}}">{{$mesi->mes_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
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
                                <div class="col-xs-4 form-group">
                                    <label >Folio de aprobación </label>
                                    <input type="text" class="form-control" name="tkpag_folaprob" id="tkpag_folaprob" placeholder="Folio de aprobación del ticket de pago " required>
                                </div>                                                           
                                <div class="col-xs-4 form-group">
                                    <label >Hr. (hh:mm) </label>
                                    <input type="text" class="form-control" name="tkpag_hora" id="tkpag_hora" placeholder="Hora de registro de la transacción en formato hh:mm" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Venta total $ en M.N. </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="tkpag_importe" id="tkpag_importe" placeholder="Venta total en moneda nacional ($ pesos mexicanos)" required>
                                </div>                                                                      
                            </div>
                        </table>    

                        <table id="tabla2" style="color: green;" class="table table-hover table-striped">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de emisión del ticket de la bomba - año</label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodo as $perf)
                                            <option value="{{$perf->periodo_id}}">{{$perf->periodo_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                               
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmes as $mesf)
                                            <option value="{{$mesf->mes_id}}">{{$mesf->mes_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
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
                                <div class="col-xs-4 form-group">
                                    <label >RFC del proveedor de servicio </label>
                                    <input type="text" class="form-control" name="tkbomba_rfc" id="tkbomba_rfc" placeholder="RFC del proveedor del servicio del ticket de la bomba" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Ticket emitido por la bomba de servicio</label>
                                    <input type="text" class="form-control" name="tkbomba_ticket" id="tkbomba_ticket" placeholder="Ticket emitido por la bomba de servicio" required>
                                </div>                                                            
                            </div>

                            <div class="row">                                 
                                <div class="col-xs-4 form-group">
                                    <label >Hr. (hh:mm) de emisión del ticket de bomba</label>
                                    <input type="text" class="form-control" name="tkbomba_hora" id="tkbomba_hora" placeholder="Hora de emisión de ticket de la bomba en formato hh:mm" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Total $ en M.N. </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="tkbomba_importe" id="tkbomba_importe" placeholder="Total en moneda nacional ($ pesos mexicanos)" required>
                                </div>                                                                      
                                <div class="col-xs-4 form-group">
                                    <label >Forma de pago </label>
                                    <select class="form-control m-bot15" name="fp_id" id="fp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar forma de pago </option>
                                        @foreach($regfpagos as $pago)
                                            <option value="{{$pago->fp_id}}">{{$pago->fp_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                  
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 carácteres) </label>
                                    <textarea class="form-control" name="obs_1" id="obs_1" rows="3" cols="120" placeholder="Observaciones (4,000 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>                            

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>El archivo digital en formato PDF, NO deberá ser mayor a 1,500 kbytes en tamaño.   </label>
                                </div>   
                            </div>
                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label >Archivo digital de servicio de carga de combustible en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="carga_foto1" id="carga_foto1" placeholder="Subir archivo digital de servicio de carga de combustible en formato PDF" >
                                </div>                                                          
                            </div>

                        </table> 
                            
                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    
                                    @foreach($regrecibos as $recibo)
                                       <a href="{{route('verCargas',array($recibo->periodo_id,$recibo->recibo_folio))}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
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
    {!! JsValidator::formRequest('App\Http\Requests\cargaRequest','#nuevaCarga') !!}
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