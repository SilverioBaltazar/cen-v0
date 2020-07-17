@extends('sicinar.principal')

@section('title','Ver cargas del recibo de bitacora para descarga de combustible')

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
            <h1>Formatos de comprobación
                <small>Recibo de bitacora - Seleccionar para editar o registrar carga</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Formatos de comprobación         </a></li>
                <li><a href="#">Recibos de bitacora - Carga  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regrecibos as $recibo)
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
                                    <a href="{{route('verRecibos')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a recibos de bitacora</small>
                                    </a>
                                    <a href="{{route('nuevaCarga',array($recibo->periodo_id,$recibo->recibo_folio))}}" class="btn btn-primary btn_xs" title="Nueva carga del recibo de bitacora"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva carga en recibo de bitacora</small>
                                    </a>
                                </td>                                     
                            </tr>                                                   
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;"> 
                                    <input type="hidden" id="placa_id" name="placa_id" value="{{$recibo->placa_id}}">  
                                    <label style="color:green;">Código : </label><b>{{$recibo->placa_id}}</b>
                                </td>
                                <td style="text-align:left; vertical-align: middle;"> 
                                    <input type="hidden" id="placa_placa" name="placa_placa" value="{{$recibo->placa_placa}}">  
                                    <label style="color:green;">Placas : </label><b>{{$recibo->placa_placa}}</b>
                                </td>
                                <td style="text-align:center; vertical-align: middle;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$recibo->periodo_id}}">  
                                    <label style="color:green;">Periodo fiscal : </label>{{$recibo->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle;"> 
                                    <input type="hidden" id="mes_id" name="mes_id" value="{{$recibo->mes_id}}">  
                                    <label style="color:green;">Mes : </label><b>
                                    @foreach($regmes as $mes)
                                        @if($mes->mes_id == $recibo->mes_id)
                                            {{$mes->mes_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>
                                </td>                                
                                <td style="text-align:right; vertical-align: middle;">   
                                    <input type="hidden" id="quincena_id" name="quincena_id" value="{{$recibo->quincena_id}}">  
                                    <label style="color:green;">Quincena : </label>
                                    @foreach($regquincena as $quincena)
                                        @if($quincena->quincena_id == $recibo->quincena_id)
                                            {{$quincena->quincena_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                     
                                <td style="text-align:right; vertical-align: middle;">   
                                    <input type="hidden" id="recibo_folio" name="recibo_folio" value="{{$recibo->recibo_folio}}">  
                                    <label style="color:green;">Folio : </label>{{$recibo->recibo_folio}}
                                </td>                                                                
                            </tr>      
                            @endforeach     
                        </table>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">#<br>Carga   </th>
                                        <th style="text-align:left;   vertical-align: middle;">tkp<br>Folio<br>Autoriz. </th>
                                        <th style="text-align:left;   vertical-align: middle;">tkp<br>Fecha    </th>
                                        <th style="text-align:left;   vertical-align: middle;">tkp<br>Hora     </th>
                                        <th style="text-align:left;   vertical-align: middle;">tkp<br>Importe  </th> 
                                        <th style="text-align:left;   vertical-align: middle;">tkb<br>rfc      </th> 
                                        <th style="text-align:left;   vertical-align: middle;">tkb<br>fecha    </th>
                                        <th style="text-align:center; vertical-align: middle;">tkb<br>Importe  </th>
                                        <th style="text-align:center; vertical-align: middle;">PDF con<br>Tickets</th>
                                        <th style="text-align:center; vertical-align: middle;">Edo.            </th>
                                        <th style="text-align:center; vertical-align: middle;">Activo <br>Inact.</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regcargas as $cargas)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$cargas->carga}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$cargas->tkpag_folaprob}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$cargas->tkpag_fecha2}} </small>
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$cargas->tkpag_hora}} </small>
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;"><small>$ {{number_format($cargas->tkpag_importe,2)}} </small>
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$cargas->tkbomba_rfc}} </small>
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$cargas->tkbomba_fecha2}} </small>
                                        </td>                                        
                                        <td style="text-align:center; vertical-align: middle;"><small>$ {{number_format($cargas->tkbomba_importe,2)}} </small>
                                        </td>  

                                        @if(isset($cargas->carga_foto1) or !empty(trim($cargas->carga_foto1)) )
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Archivo digital de carga de combustible en formato PDF">
                                                <a href="/images/{{$cargas->carga_foto1}}" class="btn btn-danger" title="Archivo digital de carga de combustible en formato PDF"><i class="fa fa-file-pdf-o"><small></i>PDF</small>
                                                </a>
                                                <a href="{{route('editarCarga1',array($cargas->periodo_id, $cargas->recibo_folio, $cargas->carga))}}" class="btn badge-warning" title="Editar archivo digital de carga de combustible en formato PDF"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Sin archivo digital de carga de combustible en formato PDF"><i class="fa fa-times">{{$cargas->carga_foto1}} </i>
                                               <a href="{{route('editarCarga1',array($cargas->periodo_id, $cargas->recibo_folio, $cargas->carga))}}" class="btn badge-warning" title="Editar archivo digital de carga de combustible en formato PDF"><i class="fa fa-edit"></i>
                                               </a>
                                            </td>
                                        @endif                                        

                                        @if($cargas->tkpag_importe === $cargas->tkbomba_importe and $cargas->tkpag_fecha2 === $cargas->tkbomba_fecha2)
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Importe y fecha de ticket de bomba y ticket de pago correctos"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Importe y/o fecha de ticket de bomba y ticket de pago distintos"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        @if($cargas->status_1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="carga activa"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="carga inactiva"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarCarga',array($cargas->periodo_id,$cargas->recibo_folio,$cargas->carga))}}" class="btn badge-warning" title="Editar carga del recibo de bitacora"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarCarga',array($cargas->periodo_id,$cargas->recibo_folio,$cargas->carga))}}" class="btn badge-danger" title="Borrar Recibo" onclick="return confirm('¿Seguro que desea borrar la carga del recibo de bitacora?')"><i class="fa fa-times"></i>
                                            </a>
                                        </td>                                    

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regcargas->appends(request()->input())->links() !!}
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