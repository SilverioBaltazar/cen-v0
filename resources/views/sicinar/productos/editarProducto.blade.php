@extends('sicinar.principal')

@section('title','Editar Producto')

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
                <small> Ventas - Productos - editar</small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarProducto',$producto->id], 'method' => 'PUT', 'id' => 'actualizarProducto', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}
                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">              
                                <tr>
                                    <input type="hidden" id="id" name="id" value="{{$producto->id}}">                              
                                    <td style="text-align:right; vertical-align: middle;color:green;">                                     
                                    <label>Id. {{$producto->id}} </label>
                                </tr> 
                            </table>

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Código de barras</label> 
                                    <input type="text" class="form-control" name="codigo_barras" id="codigo_barras" placeholder="Código de barras" value="{{$producto->codigo_barras}}" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Descripción</label>
                                    <input required value="{{$producto->descripcion}}" autocomplete="off" name="descripcion"
                                        class="form-control" type="text" placeholder="Descripción">
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label>Precio de compra</label>
                                    <input required value="{{$producto->precio_compra}}" autocomplete="off" name="precio_compra" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="Precio de compra">
                                </div>
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Precio de venta</label>
                                    <input required value="{{$producto->precio_venta}}" autocomplete="off" name="precio_venta" min="0" max="999999999.99"
                                    class="form-control" type="decimal(9,2)" placeholder="Precio de venta">
                                </div>
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Existencia</label>
                                    <input required value="{{$producto->existencia}}" autocomplete="off" name="existencia" min="0" max="999999999.99"
                                    class="form-control" type="decimal(9,2)" placeholder="Existencia">
                                </div>
                            </div>

                            <div class="row">           
                                <div class="col-xs-3 form-group">                        
                                    <label>Activo o Inactivo </label>
                                    <select class="form-control m-bot15" id="prod_status" name="prod_status" required>
                                        @if($producto->prod_status == 'S')
                                            <option value="S" selected>Activo  </option>
                                            <option value="N">         Inactivo</option>
                                        @else
                                            <option value="S">         Activo  </option>
                                            <option value="N" selected>Inactivo</option>
                                        @endif
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
                                    <a href="{{route('verProductos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\productoRequest','#actualizarProducto') !!}
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

