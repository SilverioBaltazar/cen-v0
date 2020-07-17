@extends('sicinar.principal')

@section('title','Editar datos de venta al Cliente')

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
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small> Ventas - Vender - editar datos de venta</small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarventaCliente',$regventacli->factura_folio], 'method' => 'PUT', 'id' => 'actualizarventaCliente', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <input type="hidden" id="venta_id" name="venta_id" value="{{$regventacli->factura_folio}}">  
                                    <label >Venta Id. <br>{{$regventacli->factura_folio}} </label>
                                </div> 
                                <div class="col-xs-2 form-group">
                                    <input type="hidden" id="importe" name="importe" value="{{$importe}}">  
                                    <label >Importe <br>{{$importe}} </label>
                                </div>                                                                                                
                            </div>

                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >Cliente</label>
                                    <select class="form-control m-bot15" name="cliente_id" id="cliente_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar cliente a facturar la venta</option>
                                        @foreach($regcliente as $cli)
                                            @if($cli->cliente_id == $ventacliente->cliente_id)
                                                <option value="{{$cli->cliente_id}}" selected>{{$cli->cliente_nombrecompleto}}</option>
                                            @else                                        
                                               <option value="{{$cli->cliente_id}}">{{$cli->cliente_nombrecompleto}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>               
                                <div class="col-xs-4 form-group">
                                    <label >Vendedor que realizo la venta</label>
                                    <select class="form-control m-bot15" name="emp_id" id="emp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar vendedor</option>
                                        @foreach($regempleado as $emp)
                                            @if($emp->emp_id == $ventacliente->emp_id)
                                                <option value="{{$emp->emp_id}}" selected>{{$emp->emp_nombrecompleto}}</option>
                                            @else 
                                               <option value="{{$emp->emp_id}}">{{$emp->emp_nombrecompleto}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                  
                            </div>

                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >´Crédito</label>
                                    <select class="form-control m-bot15" name="tipocredito_id" id="tipocredito_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar credito</option>
                                        @foreach($regtipocredito as $credito)
                                            @if($credito->tipocredito_id == $ventacliente->tipocredito_id)
                                                <option value="{{$credito->tipocredito_id}}" selected>{{$credito->tipocredito_desc}}</option>
                                            @else                                        
                                               <option value="{{$credito->tipocredito_id}}">{{$credito->tipocredito_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-primary btn-flat pull-right']) !!}
                                    <a href="{{route('venderProducto')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\ventaclienteRequest','#actualizarventaCliente') !!}
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

    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
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
    function soloAlfaSE(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789";
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

