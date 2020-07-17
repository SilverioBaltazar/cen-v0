@extends('sicinar.principal')

@section('title','Ver Formas de pago')

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
            <h1>Catálogo de Formas de pago
                <small> Seleccionar alguno para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Formas de pago  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadformaspago')}}" class="btn btn-success" title="Exportar catálogo de formas de pago (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catformaspagoPDF')}}" class="btn btn-danger" title="Exportar catálogo de formas de pago (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevoFormapago')}}"   class="btn btn-primary btn_xs" title="Alta de nueva forma de pago"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Forma de pago </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa / Inactiva</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regfpago as $formapago)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$formapago->fpago_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$formapago->fpago_desc}}</td>
                                        @if($formapago->fpago_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($formapago->fpago_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarFormapago',$formapago->fpago_id)}}" class="btn btn-warning" title="Editar forma de pago"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarFormapago',$formapago->fpago_id)}}" class="btn btn-danger" title="Borrar forma de pago" onclick="return confirm('¿Seguro que desea borrar la forma de pago?')"><i class="fa fa-trash"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regfpago->appends(request()->input())->links() !!}
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