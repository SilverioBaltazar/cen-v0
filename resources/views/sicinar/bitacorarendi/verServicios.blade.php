@extends('sicinar.principal')

@section('title','Ver servicio de la bitacora de rendimiento de combustible')

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
                <small> Seleccionar alguno para editar o registrar nuevo servicio</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Comprobación         </a></li>
                <li><a href="#">Bitacora de rendimiento - Servicios  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regbitarendi as $bitarendi)
                            <tr>
                                <td style="text-align:left; vertical-align: middle;">   
                                </td>                                                                 
                                <td style="text-align:left; vertical-align: middle;">   
                                </td>
                                <td style="text-align:center; vertical-align: middle;"> 
                                </td>
                                <td style="text-align:center; vertical-align: middle;"> 
                                </td>
                                <td style="text-align:right; vertical-align: middle;">   
                                    <a href="{{route('verBitarendi')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a la Bitacora de rendimiento</small>
                                    </a>
                                    <a href="{{route('nuevoServicio',$bitarendi->bitaco_folio)}}" class="btn btn-primary btn_xs" title="Nuevo servicio en la bitacora de rendimiento"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nuevo servicio en la bitacora</small>
                                    </a>
                                </td>                                     
                            </tr>                                                   
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_id" name="placa_id" value="{{$bitarendi->placa_id}}">  
                                    <label>Código : </label><b>{{$bitarendi->placa_id}}</b>
                                </td>
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_placa" name="placa_placa" value="{{$bitarendi->placa_placa}}"> 
                                    <label>Placas : </label><b>{{$bitarendi->placa_placa}}</b>
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$bitarendi->periodo_id}}">  
                                    <label>Periodo fiscal : </label>{{$bitarendi->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="mes_id" name="mes_id" value="{{$bitarendi->mes_id}}">  
                                    <label>Mes : </label><b>
                                    @foreach($regmes as $mes)
                                        @if($mes->mes_id == $bitarendi->mes_id)
                                            {{$mes->mes_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>
                                </td>                                
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="quincena_id" name="quincena_id" value="{{$bitarendi->quincena_id}}">  
                                    <label>Quincena : </label>
                                    @foreach($regquincena as $quincena)
                                        @if($quincena->quincena_id == $bitarendi->quincena_id)
                                            {{$quincena->quincena_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                     
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="recibo_folio" name="recibo_folio" value="{{$bitarendi->recibo_folio}}">  
                                    <label>Folio : </label>{{$bitarendi->bitaco_folio}}
                                </td>                                                                
                            </tr>      
                            @endforeach     
                        </table>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">No.<br>Servicio  </th>
                                        <th style="text-align:left;   vertical-align: middle;">Fecha            </th>
                                        <th style="text-align:left;   vertical-align: middle;">Servidora(or)<br>Público</th>
                                        <th style="text-align:left;   vertical-align: middle;">Dotación         </th>
                                        <th style="text-align:left;   vertical-align: middle;">Km.<br>Inicial   </th>
                                        <th style="text-align:left;   vertical-align: middle;">Km.<br>Final     </th>
                                        <th style="text-align:left;   vertical-align: middle;">Lugar<br>Comisión</th> 
                                        <th style="text-align:left;   vertical-align: middle;">Hr.<br>Salida    </th> 
                                        <th style="text-align:left;   vertical-align: middle;">Hr.<br>Regreso   </th> 
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regbitaservi as $servicio)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->servicio}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->servicio_fecha2}}</small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->sp_nomb}} </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;">
                                            <small>{{number_format($servicio->servicio_dotacion,2)}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->km_inicial}} </small>
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->km_final}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small> {{trim($servicio->servicio_lugar)}} </small>
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->servicio_hrsalida}} </small>
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$servicio->servicio_hrregreso}} </small>
                                        </td>                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarServicio',array($servicio->periodo_id,$servicio->bitaco_folio,$servicio->servicio))}}" class="btn badge-warning" title="Editar servicio de bitacora de rendimiento"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarServicio',array($servicio->periodo_id,$servicio->bitaco_folio,$servicio->servicio))}}" class="btn badge-danger" title="Borrar servicio de la bitacora de rendimiento" onclick="return confirm('¿Seguro que desea borrar el servicio de la bitacora de rendimiento?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>                                    

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regbitaservi->appends(request()->input())->links() !!}
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
