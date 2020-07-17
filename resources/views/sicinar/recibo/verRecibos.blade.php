@extends('sicinar.principal')

@section('title','Ver recibos de bitacora para descarga de combustible')

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
                <small> Recibo de bitacora - Seleccionar para editar o registrar recibo</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Formatos de comprobación         </a></li>
                <li><a href="#">Recibos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            Buscar 
                            {{ Form::open(['route' => 'buscarRecibo', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="col-xs-2 form-group">
                                    {{ Form::text('codigo', null, ['class' => 'form-control', 'placeholder' => 'Código']) }}
                                </div>
                                <div class="col-xs-2 form-group">
                                    {{ Form::text('placa', null, ['class' => 'form-control', 'placeholder' => 'Número de placas']) }}
                                </div>
                                <div class="col-xs-2 form-group">
                                    {{ Form::text('folio', null, ['class' => 'form-control', 'placeholder' => 'Folio']) }}
                                </div>
                                <div class="col-xs-2 form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('nuevoRecibo')}}"   class="btn btn-primary btn_xs" title="Alta de Recibos de bitacora para descarga de combustible"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nuevo recibo</small>
                                    </a>
                                </div>                                
                            {{ Form::close() }}
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
                                        <th style="text-align:left;   vertical-align: middle;">Cargas           </th>

                                        <th style="text-align:center; vertical-align: middle;">Activo <br>Inact.</th>
                                        <th style="text-align:center; vertical-align: middle;">Edo.   <br>Comp. </th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Funciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regrecibos as $recibo)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$recibo->periodo_id}}
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$recibo->recibo_folio}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$recibo->placa_id}}
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$recibo->placa_placa}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">
                                            @foreach($regplaca as $placa)
                                                @if($placa->placa_id == $recibo->placa_id and $placa->placa_placa == $recibo->placa_placa)
                                                    {{$placa->placa_obs2}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">
                                            @foreach($regmes as $mes)
                                                @if($mes->mes_id == $recibo->mes_id)
                                                    {{$mes->mes_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">
                                            @foreach($regquincena as $quin)
                                                @if($quin->quincena_id == $recibo->quincena_id)
                                                    {{$quin->quincena_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>         
                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">
                                            @foreach($totpartidas as $partida)
                                                @if($partida->periodo_id == $recibo->periodo_id && $partida->recibo_folio == $recibo->recibo_folio)
                                                    {{$partida->partidas}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>                                                               

                                        @if($recibo->recibo_status1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="folio activo"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Folio inactivo"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif

                                        <td style="text-align:center;margin-right: 15px;vertical-align: middle;">
                                        @switch($recibo->recibo_status2)
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" /> 
                                            @break
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg') }}" width="15px" height="15px"/> 
                                            @break
                                        @default
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px"/> 
                                        @endswitch
                                        </td>

                                        @if($recibo->recibo_status2 == '2')
                                           <td></td>
                                        @else                                        
                                            <td style="text-align:center;">
                                                <a href="{{route('editarRecibo',$recibo->recibo_folio)}}" class="btn badge-warning" title="Editar recibo"><i class="fa fa-edit"></i>
                                                </a>
                                                @if(session()->get('role')->rol_name !== 'user')  
                                                   <a href="{{route('borrarRecibo',$recibo->recibo_folio)}}" class="btn badge-danger" title="Borrar Recibo" onclick="return confirm('¿Seguro que desea borrar el recibo?')"><i class="fa fa-times"></i>
                                                   </a>
                                                @endif 
                                            </td>
                                        @endif
                                        <td style="text-align:center;">
                                            <a href="{{route('ExportReciboPdf',array($recibo->periodo_id,$recibo->periodo_id,$recibo->recibo_folio))}}" class="btn btn-danger" title="Recibo de bitacora y sus cargas (formato PDF)"><i class="fa fa-file-pdf-o"></i>
                                            <small> PDF</small>
                                            </a>
                                            <a href="{{route('verCargas',array($recibo->periodo_id, $recibo->recibo_folio))}}" class="btn btn-primary btn_xs" title="Ver cargas del recibo de bitacora"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Cargas</small>
                                            </a>
                                        </td>                                        

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regrecibos->appends(request()->input())->links() !!}
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