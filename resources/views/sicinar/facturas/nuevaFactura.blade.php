@extends('sicinar.principal')

@section('title','Nueva factura')

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
                <small>Ventas - Facturar productos - Nueva factura</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => 'AltaNuevaFactura', 'method' => 'POST','id' => 'nuevaFactura', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Cliente</label>
                                    <select class="form-control m-bot15" name="cliente_id" id="cliente_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar cliente </option>
                                        @foreach($regcliente as $cli)
                                            <option value="{{$cli->cliente_id}}">{{trim($cli->cliente_nombrecompleto)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Crédito de la venta</label>
                                    <select class="form-control m-bot15" name="tipocredito_id" id="tipocredito_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar crédito de la venta </option>
                                        @foreach($regtipocredito as $credito)
                                            <option value="{{$credito->tipocredito_id}}">{{$credito->tipocredito_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label>$ Monto a subsidiar</label>
                                    <input required autocomplete="off" id="efactura_montosubsidio" name="efactura_montosubsidio" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="$ Monto a subsidiar">
                                </div>
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>$ Monto de las aportaciones</label>
                                    <input required autocomplete="off" id="efactura_montoaportaciones" name="efactura_montoaportaciones" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="$ Monto de las aportaciones">
                                </div>
                            </div>                            
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Fecha de pago </label>
                                    <input type="date" class="form-control" id="efactura_fecaportacion1" name="efactura_fecaportacion1" >
                                </div>                                                                
                            </div>

                            <div class="row">                            
                                <div class="col-xs-3 form-group">
                                    <label >Vendedor </label>
                                    <select class="form-control m-bot15" name="emp_id" id="emp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar vendedor </option>
                                        @foreach($regempleado as $emp)
                                            <option value="{{$emp->emp_id}}">{{trim($emp->emp_nombrecompleto)}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verFacturas')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\facturaRequest','#nuevaFactura') !!}
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