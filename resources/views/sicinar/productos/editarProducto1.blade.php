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

                        {!! Form::open(['route' => ['actualizarProducto1',$producto->id], 'method' => 'PUT', 'id' => 'actualizarProducto1', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}
                        <div class="box-body">
                              
                            <table id="tabla1" class="table table-hover table-striped">              
                                <tr>                            
                                    <td style="text-align:left; vertical-align: middle;color:green;"> 
                                        <label >Código de barras:  {{$producto->codigo_barras}}</label>
                                    </td>
                                    <td style="text-align:right; vertical-align: middle;color:green;"> 
                                        <label>Id: {{$producto->id}} </label>
                                    </td>
                                </tr>             
                            </table>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales de fotos, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>

                            <div class="row">    
                                @if(!empty(trim($producto->prod_foto1))&&(!is_null($producto->prod_foto1)))
                                    <div class="col-xs-4 form-group">
                                        <label >Foto del producto</label><br>
                                        <label ><a href="/images/{{$producto->prod_foto1}}" class="btn btn-danger" title="Foto del producto"><i class="fa fa-file-photo-o"></i></a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar foto del producto</label>
                                        <input type="file" accept="image/jpeg,image/png" class="text-md-center" style="color:red" name="prod_foto1" id="prod_foto1" placeholder="Subir archivo digital de foto del producto" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Foto del producto</label>
                                        <input type="file" accept="image/jpeg,image/png" class="text-md-center" style="color:red" name="prod_foto1" id="prod_foto1" placeholder="Subir archivo digital de foto del producto" >
                                    </div>                                                
                                @endif       
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
    {!! JsValidator::formRequest('App\Http\Requests\producto1Request','#actualizarProducto1') !!}
@endsection

@section('javascrpt')
@endsection

