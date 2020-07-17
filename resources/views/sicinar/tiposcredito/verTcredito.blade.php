@extends('sicinar.principal')

@section('title','Ver Tipos de crédito')

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
            <h1>Catálogo de Tipos de crédito
                <small> Seleccionar alguno para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Tipos de crédito  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadtipocredito')}}" class="btn btn-success" title="Exportar catálogo de tipos de crédito (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('cattipocreditoPDF')}}" class="btn btn-danger" title="Exportar catálogo de tipos de crédito (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevoTipocredito')}}"   class="btn btn-primary btn_xs" title="Alta de nuevo tipos de crédito"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Tipo de crédito </th>
                                        <th style="text-align:left;   vertical-align: middle;">Días de crédito </th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regtipocredito as $tipocredito)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$tipocredito->tipocredito_id}}  </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$tipocredito->tipocredito_desc}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$tipocredito->tipocredito_dias}}</td>
                                        @if($tipocredito->tipocredito_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($tipocredito->tipocredito_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarTipocredito',$tipocredito->tipocredito_id)}}" class="btn btn-warning" title="Editar tipo de credito"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarTipocredito',$tipocredito->tipocredito_id)}}" class="btn btn-danger" title="Borrar tipo de credito" onclick="return confirm('¿Seguro que desea borrar el tipo de crédito?')"><i class="fa fa-trash"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regtipocredito->appends(request()->input())->links() !!}
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
