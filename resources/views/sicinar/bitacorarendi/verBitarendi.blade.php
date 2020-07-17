@extends('sicinar.principal')

@section('title','Ver bitacora de rendimiento de combustible')

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
            <h1>Comprobaciones
                <small> Seleccionar alguno para editar o registrar nueva bitacora</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Comprobación             </a></li>
                <li><a href="#">Bitacora de rendimiento  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="page-header" style="text-align:right;">
                            <a href="{{route('nuevaBitarendi')}}"   class="btn btn-primary btn_xs" title="Alta de Bitacora de rendimiento de combustible"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Nueva bitacora
                            </a>
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Per.<br>Fiscal   </th>
                                        <th style="text-align:left;   vertical-align: middle;">Folio            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Código           </th>
                                        <th style="text-align:left;   vertical-align: middle;">Placa            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Resguardatario   </th> 
                                        <th style="text-align:left;   vertical-align: middle;">Mes              </th> 
                                        <th style="text-align:left;   vertical-align: middle;">Quincena         </th>
                                        <th style="text-align:left;   vertical-align: middle;">Servicios        </th>
                                        <th style="text-align:center; vertical-align: middle;">PDF 1<br>Bitacora</th>
                                        <th style="text-align:center; vertical-align: middle;">PDF 2<br>Bitacora</th>
                                        <th style="text-align:center; vertical-align: middle;">Activo <br>Inact.</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Funciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regbitarendi as $ebitarendi) 
                                    <tr>
                                        <td style="text-align:center; vertical-align: middle;"><small>{{$ebitarendi->periodo_id}} </small>
                                        </td>                                        
                                        <td style="text-align:center; vertical-align: middle;"><small>{{$ebitarendi->bitaco_folio}} </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>{{$ebitarendi->placa_id}} </small>
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$ebitarendi->placa_placa}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>
                                            @foreach($regplaca as $placa)
                                                @if($placa->placa_id == $ebitarendi->placa_id and $placa->placa_placa == $ebitarendi->placa_placa)
                                                    {{$placa->placa_obs2}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>
                                            @foreach($regmes as $mes)
                                                @if($mes->mes_id == $ebitarendi->mes_id)
                                                    {{$mes->mes_desc}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>
                                            @foreach($regquincena as $quin)
                                                @if($quin->quincena_id == $ebitarendi->quincena_id)
                                                    {{$quin->quincena_desc}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td> 
                                        <td style="text-align:center; vertical-align: middle;"><small>
                                            @foreach($totservicios as $partida)
                                                @if($partida->periodo_id == $ebitarendi->periodo_id && $partida->bitaco_folio == $ebitarendi->bitaco_folio)
                                                    {{$partida->servicios}}
                                                    @break
                                                @endif
                                            @endforeach </small>
                                        </td>                                                        
                                        @if(isset($ebitarendi->bitaco_foto1))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="PDF de bitacora de rendimiento de combustible">
                                                <a href="/images/{{$ebitarendi->bitaco_foto1}}" class="btn btn-danger" title="PDF de bitacora de rendimiento de combustible"><i class="fa fa-file-pdf-o"><small></i>PDF</small></a>
                                                <a href="{{route('editarBitarendi1',$ebitarendi->bitaco_folio)}}" class="btn badge-warning" title="Editar PDF de bitacora de rendimiento de combustible"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin PDF 1 de bitacora de rendimiento de combustible"><i class="fa fa-times">{{$ebitarendi->bitaco_foto1}} </i>
                                               <a href="{{route('editarBitarendi1',$ebitarendi->bitaco_folio)}}" class="btn badge-warning" title="Editar PDF 1 de bitacora de rendimiento de combustible"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @endif
                                        @if(isset($ebitarendi->bitaco_foto2))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="PDF 2 de bitacora de rendimiento de combustible">
                                                <a href="{{$ebitarendi->bitaco_foto2}}" class="btn btn-danger" title="PDF 2 de bitacora de rendimiento de combustible"><i class="fa fa-file-pdf-o"></i><small>PDF</small></a>
                                                <a href="{{route('editarBitarendi2',$ebitarendi->bitaco_folio)}}" class="btn badge-warning" title="Editar PDF 2 de bitacora de rendimiento de combustible"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin PDF 2 de bitacora de rendimiento de combustible"><i class="fa fa-times">{{$ebitarendi->ebitaco_foto2}} </i>
                                                <a href="{{route('editarBitarendi2',$ebitarendi->bitaco_folio)}}" class="btn badge-warning" title="Editar PDF 2 de bitacora de rendimiento de combustible"><i class="fa fa-edit"></i>
                                                </a>                                                
                                            </td>
                                        @endif                                           

                                        @if($ebitarendi->bitaco_status1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Bitacora activa"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Bitacora inactiva"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                                                                
                                        <td style="text-align:center;">
                                            <a href="{{route('editarBitarendi',$ebitarendi->bitaco_folio)}}" class="btn badge-warning" title="Editar recibo"><i class="fa fa-edit"></i>
                                            </a>
                                            @if(session()->get('role')->rol_name !== 'user')  
                                                <a href="{{route('borrarBitarendi',$ebitarendi->bitaco_folio)}}" class="btn badge-danger" title="Borrar Bitacora de rendimiento de combustible " onclick="return confirm('¿Seguro que desea borrar Bitacora de rendimiento de combustible?')"><i class="fa fa-times"></i>
                                                </a>
                                            @endif 
                                        </td>

                                        <td style="text-align:center;">
                                            <a href="{{route('ExportBitarendiPdf',array($ebitarendi->periodo_id,$ebitarendi->periodo_id,$ebitarendi->bitaco_folio))}}" class="btn btn-danger" title="Bitacora de rendimiento de combustible y sus servicios en formato PDF"><i class="fa fa-file-pdf-o"></i>
                                            <small> PDF</small>  
                                            </a>
                                            <a href="{{route('verServicios',array($ebitarendi->periodo_id,$ebitarendi->bitaco_folio))}}" class="btn btn-primary btn_xs" title="Ver servicios de la bitacora"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Servicios</small>
                                            </a>
                                        </td>                                        

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regbitarendi->appends(request()->input())->links() !!}
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