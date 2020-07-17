@extends('sicinar.principal')

@section('title','Ver plantilla de personal')

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
            <h1>Requisitos asistenciales
                <small> Seleccionar alguna para editar o registrar personal </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Requisitos asistenciales </a></li>   
                <li><a href="#">Plantilla de personal    </a></li>               
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            Buscar  
                            {{ Form::open(['route' => 'buscarPersonal', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Nombre persona']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                        <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('ExportPersonalExcel')}}" class="btn btn-success" title="Exportar plantilla de personal (formato Excel)"><i class="fa fa-file-excel-o"></i> Excel
                                    </a>                            
                                    <a href="{{route('nuevoPersonal')}}" class="btn btn-primary btn_xs" title="Nueva persona"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva persona
                                    </a>
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Folio          </th>
                                        <th style="text-align:left;   vertical-align: middle;">IAP            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Persona        </th>
                                        <th style="text-align:left;   vertical-align: middle;">CURP           </th>
                                        <th style="text-align:left;   vertical-align: middle;">Fecha <br>Nac. </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regpersonal as $personal)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$personal->folio}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$personal->iap_id}}   
                                            @foreach($regiap as $iap)
                                                @if($iap->iap_id == $personal->iap_id)
                                                    {{$iap->iap_desc}}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>                                          
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($personal->nombre_completo)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$personal->curp}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">
                                            {{$personal->fecha_nacimiento2}}    
                                        </td>
                                                                                
                                        @if($personal->status_1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarPersonal',$personal->folio)}}" class="btn badge-warning" title="Editar personal"><i class="fa fa-edit"></i></a>
                                            <a href="{{route('borrarPersonal',$personal->folio)}}" class="btn badge-danger" title="Borrar de la plantilla de personal" onclick="return confirm('¿Seguro que desea borrar persona de la plantilla?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regpersonal->appends(request()->input())->links() !!}
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