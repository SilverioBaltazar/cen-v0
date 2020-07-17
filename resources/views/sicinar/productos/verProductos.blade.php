@extends('sicinar.principal')

@section('title','Ver productos')

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
            <h1>Productos
                <small> Seleccionar alguno para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Ventas </a></li>
                <li><a href="#">Productos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="page-header" style="text-align:right;">
                            Buscar  
                            {{ Form::open(['route' => 'buscarProducto', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Descripción']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('ExportProductosExcel')}}" class="btn btn-success" title="Exportar productos (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel
                                    </a>                            
                                    <a href="{{route('nuevoProducto')}}" class="btn btn-primary btn_xs" title="Nuevo producto"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo producto
                                    </a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.         </th>
                                        <th style="text-align:left;   vertical-align: middle;">Código      </th>
                                        <th style="text-align:left;   vertical-align: middle;">Descripción </th>
                                        <th style="text-align:center; vertical-align: middle;">Precio compra</th>
                                        <th style="text-align:center; vertical-align: middle;">Precio venta </th>
                                        <th style="text-align:center; vertical-align: middle;">Utilidad    </th>
                                        <th style="text-align:center; vertical-align: middle;">Existencia  </th>
                                        <th style="text-align:center; vertical-align: middle;">Foto        </th>
                                        <th style="text-align:center; vertical-align: middle;">Edo.        </th>                                        
                                        <th style="text-align:center; vertical-align: middle;">Funciones   </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($producto as $prod)
                                    <tr>
                                        <td style="text-align:left;  vertical-align: middle;">{{$prod->id}}            </td> 
                                        <td style="text-align:left;  vertical-align: middle;">{{$prod->codigo_barras}} </td>
                                        <td style="text-align:left;  vertical-align: middle;">{{$prod->descripcion}}   </td>
                                        <td style="text-align:center;vertical-align: middle;">{{number_format($prod->precio_compra,2)}} </td>
                                        <td style="text-align:center;vertical-align: middle;">{{number_format($prod->precio_venta,2)}} </td>
                                        <td style="text-align:center;vertical-align: middle;">{{number_format($prod->precio_venta - $prod->precio_compra,2)}} </td>
                                        <td style="text-align:center;vertical-align: middle;">{{number_format($prod->existencia,0)}} </td>

                                        @if(!empty(trim($prod->prod_foto1))&&(!is_null($prod->prod_foto1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Foto del producto">
                                                <a href="/images/{{$prod->prod_foto1}}" class="btn btn-danger" title="foto"><i class="fa fa-file-photo-o"></i>
                                                </a>
                                                <a href="{{route('editarProducto1',$prod->id)}}" class="btn btn-warning" title="Editar fotografía del producto"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Foto del producto">
                                                <i class="fa fa-times"></i>
                                                <a href="{{route('editarProducto1',$prod->id)}}" class="btn btn-warning" title="Editar foto del producto"><i class="fa fa-edit"></i>
                                                </a>
                                                
                                            </td>   
                                        @endif   
                                                                                                                        
                                        @if($prod->prod_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif

                                        <td>
                                            <a href="{{route('editarProducto',$prod->id)}}" class="btn btn-warning" title="Editar producto"><i class="fa fa-edit"></i>
                                            </a>
                                            @if($role->rol_name == 'user')
                                            @else                                            
                                                <a href="{{route('borrarProducto',$prod->id)}}" class="btn btn-danger" title="Borrar producto" onclick="return confirm('¿Seguro que desea borrar el producto?')"><i class="fa fa-trash"></i>
                                                </a>
                                            @endif
                                        </td>

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $producto->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection
