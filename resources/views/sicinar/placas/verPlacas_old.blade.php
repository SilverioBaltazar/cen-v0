@extends('sicinar.principal')

@section('title','Ver placas')

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
            <h1>Placas
                <small> Seleccionar alguna para editar o registrar nueva placa</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Placas </a></li>
                <li><a href="#">Padron  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            Busqueda  
                            {{ Form::open(['route' => 'buscarPlaca', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group">
                                    {{ Form::text('codigo', null, ['class' => 'form-control', 'placeholder' => 'Código de combustible']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('placa', null, ['class' => 'form-control', 'placeholder' => 'Número de placa']) }}
                                </div>
                                <div class="form-group">
                                    {{ Form::text('name', null, ['class' => 'form-control', 'placeholder' => 'Automovil']) }}
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                <a href="{{route('placasPdf')}}" class="btn btn-danger" title="Exportar catálogo de placas a formato PDF"><i class="fa fa-file-pdf-o"></i> PDF
                                </a>                            
                            
                                <a href="{{route('placasExcel')}}" class="btn btn-success" title="Exportar catálogo de placas a formato Excel"><i class="fa fa-file-excel-o"></i> Excel
                                </a>

                                <a href="{{route('nuevaPlaca')}}"   class="btn btn-primary btn_xs" title="Alta de nueva placa"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva placa</a>
                                </div>                                
                            {{ Form::close() }}

                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Código     </th>
                                        <th style="text-align:left;   vertical-align: middle;">placa      </th>
                                        <th style="text-align:left;   vertical-align: middle;">Automovil  </th>     
                                        <th style="text-align:left;   vertical-align: middle;">No.<br>Cil.</th>
                                        
                                        <th style="text-align:left;   vertical-align: middle;">Marca            </th>
                                        <th style="text-align:left;   vertical-align: middle;">T.<br>Gasto      </th>
                                        <th style="text-align:left;   vertical-align: middle;">T.Op.<br>Admon.  </th>
                                        <th style="text-align:left;   vertical-align: middle;">Reguardatario    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Foto             </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa <br>Inact.</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regplaca as $placa)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$placa->placa_id}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$placa->placa_placa}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{Trim($placa->placa_desc)}}</small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$placa->placa_cilindros}}     </small>
                                        </td>
                             
                                        <td style="text-align:left; vertical-align: middle;"><small>
                                            @foreach($regmarca as $marca)
                                                @if($marca->marca_id == $placa->marca_id)
                                                    {{$marca->marca_desc}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>
                                            @foreach($regtipogasto as $tgasto)
                                                @if($tgasto->tipog_id == $placa->tipog_id)
                                                    {{$tgasto->tipog_desc}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>
                                            @foreach($regtipooper as $toper)
                                                @if($toper->tipoo_id == $placa->tipoo_id)
                                                    {{$toper->tipoo_desc}}
                                                    @break
                                                @endif
                                            @endforeach  </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$placa->placa_obs2}} </small>
                                        </td>
                                        @if(isset($placa->placa_foto1))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Fotografía 1">
                                                <a href="/images/{{$placa->placa_foto1}}" class="btn btn-success" title="Fotografía 1"><i class="fa-file-image-o"></i>gif, jpeg o png</a>
                                                <a href="{{route('editarPlaca1',$placa->placa_id)}}" class="btn badge-warning" title="Editar Fotografía de la unidad"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin Fotografía de la unidad"><i class="fa fa-times">{{$placa->placa_foto1}} </i>
                                                <a href="{{route('editarPlaca1',$placa->placa_id)}}" class="btn badge-warning" title="Editar Fotografía de la unidad"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @endif   

                                        @if($placa->placa_status1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Placa activa"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Placa inactiva"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarPlaca',$placa->placa_id)}}" class="btn badge-warning" title="Editar datos de la placa"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarPlaca',$placa->placa_id)}}" class="btn badge-danger" title="Borrar placa del padrón" onclick="return confirm('¿Seguro que desea borrar la placa del padrón?')"><i class="fa fa-times"></i></a>
                                        </td>                                                                                
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regplaca->appends(request()->input())->links() !!}
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