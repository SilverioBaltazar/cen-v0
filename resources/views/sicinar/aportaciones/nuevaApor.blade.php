@extends('sicinar.principal')

@section('title','Registro de aportación monetaria')

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
                <small>Crédito y combranza - Cobranza - Aportaciones monetarias - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        
                        {!! Form::open(['route' => 'AltaNuevaApor', 'method' => 'POST','id' => 'nuevaApor', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">                                
                                <div class="col-xs-3 form-group">
                                    <label >Cliente </label>
                                    <select class="form-control m-bot15" name="cliente_id" id="cliente_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar cliente</option>
                                        @foreach($regclientes as $factcli)
                                            <option value="{{$factcli->cliente_id}}">{{$factcli->cliente_nombrecompleto}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                 
                                <div class="col-xs-3 form-group">
                                    <label >Factura a pagar </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="factura_folio" id="factura_folio" placeholder="Núm. de factura a pagar" required>
                                </div>                                                                   
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Fecha de aportación </label>
                                    <input type="date" class="form-control" id="apor_fecha" name="apor_fecha" >
                                </div>                   
                                <div class="col-xs-3 form-group">
                                    <label>Fecha de próximo pago </label>
                                    <input type="date" class="form-control" id="apor_fecproxpago" name="apor_fecproxpago" >
                                </div>                   
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Concepto  </label>
                                    <input type="text" class="form-control" name="apor_concepto" id="apor_concepto" placeholder="Digitar concepto de la paotración monetaria" required>
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label >$ Importe </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="apor_importe" id="apor_importe" placeholder="Importe de la aportación" required>
                                </div>                                  
                                <div class="col-xs-3 form-group">
                                    <label >Número de recibo </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="apor_recibo" id="apor_recibo" placeholder="Número de recibo" required>
                                </div>                                   
                            </div>                            
                            <div class="row">               
                                <div class="col-xs-3 form-group">
                                    <label >Forma de pago </label>
                                    <select class="form-control m-bot15" name="fpago_id" id="fpago_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar forma de pago </option>
                                        @foreach($regfpago as $pago)
                                            <option value="{{$pago->fpago_id}}">{{$pago->fpago_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                                <div class="col-xs-3 form-group">
                                    <label >Banco  </label>
                                    <select class="form-control m-bot15" name="banco_id" id="banco_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Banco</option>
                                        @foreach($regbancos as $banco)
                                            <option value="{{$banco->banco_id}}">{{$banco->banco_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                            </div>                            
                            <div class="row">                                
                                <div class="col-xs-3 form-group">
                                    <label >Empleado que recibió la aportación </label>
                                    <select class="form-control m-bot15" name="emp_id" id="emp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar empleado</option>
                                        @foreach($regempleados as $emple)
                                            <option value="{{$emple->emp_id}}">{{$emple->emp_nombrecompleto}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                      
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Archivo de recibo de aportación en formato PDF </label>
                                    <input type="file" class="text-md-center" style="color:red" name="apor_foto1" id="apor_foto1" placeholder="Subir archivo de recibo de aportación en formato PDF">
                                </div>   
                            </div>        

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar ',['class' => 'btn btn-primary btn-flat pull-right']) !!}
                                    <a href="{{route('verApor')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\aportacionesRequest','#nuevaApor') !!}
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
@endsection
