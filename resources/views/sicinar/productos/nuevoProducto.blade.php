@extends('sicinar.principal')

@section('title','Nuevo producto')

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
                <small>Ventas - Productos - Nuevo</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">
                        
                        {!! Form::open(['route' => 'AltaNuevoProducto', 'method' => 'POST','id' => 'nuevoProducto', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label>Código de barras</label> 
                                    <input type="text" class="form-control" name="codigo_barras" id="codigo_barras" placeholder="Código de barras" autocomplete="off" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label>Descripción</label>
                                    <input required autocomplete="off" name="descripcion"
                                        class="form-control" type="text" placeholder="Descripción">
                                </div>
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label>Precio de compra</label>
                                    <input required autocomplete="off" name="precio_compra" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="Precio de compra">
                                </div>
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Precio de venta</label>
                                    <input required autocomplete="off" name="precio_venta" min="0" max="999999999.99"
                                    class="form-control" type="decimal(9,2)" placeholder="Precio de venta">
                                </div>
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>Existencia</label>
                                    <input required autocomplete="off" name="existencia" min="0" max="999999999.99"
                                    class="form-control" type="decimal(9,2)" placeholder="Existencia">
                                </div>
                            </div>

                            <div class="row">               
                                <div class="col-xs-4 form-group">
                                    <label >Foto del producto </label>
                                    <input type="file" class="text-md-center" style="color:red" name="prod_foto1" id="prod_foto1" placeholder="Subir archivo digital de foto del producto">
                                </div>   
                            </div>        

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Registrar ',['class' => 'btn btn-primary btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\productoRequest','#nuevoProducto') !!}
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
