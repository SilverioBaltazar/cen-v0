{{--

    Blog:       https://
    Ayuda:      https://
    Contacto:   https://

    Copyright (c) 2020 Ing. Silverio Baltazar Barrientos Zarate
    Licenciado bajo la licencia MIT

    El texto de arriba debe ser incluido en cualquier redistribucion
--}}

@extends('sicinar.principal')

@section('title','Vender productos ')

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
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small>Ventas - Vender </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
            <div class="col-md-12">
            <div class="box box-primary">
                <h1>Nueva venta <i class="fa fa-cart-plus"></i></h1>
           
                <div class="box-body">            
                    <div class="row">
                        <div class="col-8 col-md-6">
                            <form action="{{route('agregarProductoVenta')}}" method="post">
                                @csrf
                                <div class="form-group">
                                    <label for="codigo">Código de barras</label>
                                    <input id="codigo" autocomplete="off" required autofocus name="codigo" type="text"
                                    class="form-control" placeholder="Código de barras">
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-8 col-md-6">
                            <form action="{{route('terminarOCancelarVenta')}}" method="post">
                                @csrf
                                <!--
                                <div class="form-group">
                                    <label for="cliente_id">Cliente</label>
                                    <select required class="form-control" name="cliente_id" id="cliente_id">
                                        @foreach($clientes as $cliente)
                                            <option value="{{$cliente->cliente_id}}">{{$cliente->cliente_nombrecompleto}}</option>
                                        @endforeach
                                    </select>
                                </div>
                                -->
                                @if(session("productos") !== null)
                                    <div class="col-xs-6 form-group">
                                        <button name="accion" value="terminar" type="submit" class="btn btn-success">Terminar venta</button>
                                        <button name="accion" value="cancelar" type="submit" class="btn btn-danger">Cancelar venta</button>
                                    </div>
                                @endif
                            </form>
                        </div>
                    </div>
                    @if(session("productos") !== null)
                        <h2>Total: ${{number_format($total, 2)}}</h2>
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>Código de barras</th>
                                    <th>Descripción</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Quitar</th>
                                </tr>
                                </thead>
                                <tbody>
                                @foreach(session("productos") as $producto)
                                    <tr>
                                        <td>{{$producto->codigo_barras}}</td>
                                        <td>{{$producto->descripcion}}</td>
                                        <td>${{number_format($producto->precio_venta, 2)}}</td>
                                        <td>{{$producto->cantidad}}</td>
                                        <td>
                                            <form action="{{route('quitarProductoDeVenta')}}" method="post">
                                                @method("delete")
                                                @csrf
                                                <input type="hidden" name="indice" value="{{$loop->index}}">
                                                <button type="submit" class="btn btn-danger"><i class="fa fa-trash"></i></button>
                                            </form>
                                        </td>
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="row">
                            <div class="col-md-12">
                                <h2>Aquí aparecerán los productos de la venta <br>Escanea el código de barras o escribe y presiona Enter</h2>
                            </div>
                        </div>
                    @endif
                </div>

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
    <!--{!! JsValidator::formRequest('App\Http\Requests\tipocreditoRequest','#nuevoTipocredito') !!} -->
@endsection

@section('javascrpt')
@endsection
