@extends('sicinar.principal')

@section('title','Ver rubros')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('estructura')
    {{$estructura}}
@endsection

@section('content')
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Catálogo de Rubros Sociales
                <small> Seleccionar alguno para editar o registrar nuevo rubro</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Catálogos </a></li>
                <li><a href="#">Rubros sociales  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            <a href="{{route('downloadrubros')}}" class="btn btn-success" title="Exportar catálogo de rubros (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel</a>                            
                            <a href="{{route('catrubrosPDF')}}" class="btn btn-danger" title="Exportar catálogo de rubros (formato PDF)"><i class="fa fa-file-pdf-o"></i> PDF</a>
                            <a href="{{route('nuevoRubro')}}"   class="btn btn-primary btn_xs" title="Alta de nuevo rubro"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nuevo rubro</a>                             
                        </div>
                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Id.</th>
                                        <th style="text-align:left;   vertical-align: middle;">Rubro social</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo / Inactivo</th>
                                        <th style="text-align:center; vertical-align: middle;">Fecha registro</th>
                                        <th style="text-align:center; vertical-align: middle;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regrubro as $rubro)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$rubro->rubro_id}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{$rubro->rubro_desc}}</td>
                                        @if($rubro->rubro_status == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($rubro->rubro_fecreg))}}</td>
                                        <td style="text-align:center;">
                                            <a href="{{route('editarRubro',$rubro->rubro_id)}}" class="btn badge-warning" title="Editar rubro social"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarRubro',$rubro->rubro_id)}}" class="btn badge-danger" title="Borrar rubro social" onclick="return confirm('¿Seguro que desea borrar el rubro social?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regrubro->appends(request()->input())->links() !!}
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