@extends('sicinar.principal')

@section('title','Ver facturas de venta')

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
            <h1>Ventas
                <small>Facturar productos - Seleccionar para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Ventas             </a></li>
                <li><a href="#">Facturar productos </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="page-header" style="text-align:right;">
                            
                            <div class="row">
                            <div class="col-md-12">
                            <!--<b style="text-align:left; vertical-align: middle;font-size:11px;">Buscar </b> -->
                            {{ Form::open(['route' => 'buscarFactura', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="col-xs-2 form-group"><b style="text-align:left; vertical-align: middle;font-size:10px;"> </b>
                                    <select class="form-control m-bot15" name="perr" id="perr" class="form-control">
                                        <option value="">Seleccionar Periodo </option> 
                                        @foreach($regperiodo as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{trim($periodo->periodo_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <div class="col-xs-2 form-group"><b style="text-align:left; vertical-align: middle;font-size:10px;">  </b>
                                    <select class="form-control m-bot15" name="mess" id="mess" class="form-control">
                                        <option value="">Seleccionar mes</option> 
                                        @foreach($regmes as $mes)
                                            <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>                                
                                <div class="col-xs-1 form-group">
                                    <input type="number" min="0" max="999999" class="form-control" name="diaa" id="diaa" placeholder="Día">
                                </div> 
                                <!--
                                <div class="form-group">
                                    {{ Form::text('diaa', null, ['class' => 'form-control', 'placeholder' => 'Dia']) }}
                                </div>
                                -->                                
                                <div class="col-xs-3 form-group"><b style="text-align:left; vertical-align: middle;font-size:10px;"> </b>
                                    <select class="form-control m-bot15" name="cliee" id="cliee" class="form-control">
                                        <option value="">Seleccionar cliente </option>
                                        @foreach($regcliente as $cli)
                                            <option value="{{$cli->cliente_id}}">{{trim($cli->cliente_nombrecompleto)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <div class="col-xs-1 form-group">
                                    <!--{{ Form::text('folioo', null, ['class' => 'form-control', 'placeholder' => 'Folio']) }} -->
                                    <input type="number" min="0" max="999999999" class="form-control" name="folioo" id="folioo" placeholder="Folio factura">
                                </div>
                                <div class="col-xs-1 form-group">
                                    <select class="form-control" name="statuss" id="statuss">
                                        <option value="">Status facturas</option>
                                        <option value="0">Pendientes</option>
                                        <option value="2">Pagadas   </option>
                                        <option value="1">Canceladas</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('nuevaFactura')}}" class="btn btn-primary btn_xs" title="Facturar productos"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Nueva factura</small>
                                    </a>
                                </div>                                
                            {{ Form::close() }}
                            </div>
                            </div>
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Per.<br>Fiscal   </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Mes              </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Factura          </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Cliente          </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Vendedor         </th>

                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">$ Importe        </th> 
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">$ Pagado         </th> 
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">$ Saldo          </th> 
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;"># Prod's         </th>
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Fec.cobro        </th> 
                                        <th style="text-align:left;   vertical-align: middle;font-size:11px;">Fecha reg.       </th>

                                        <th style="text-align:center; vertical-align: middle;">st.                  </th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Funciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regfactura as $factura)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$factura->periodo_id}}   </td>
                                        <td style="text-align:center;vertical-align: middle;font-size:10px;">
                                            @foreach($regmes as $mes)
                                                @if($mes->mes_id == $factura->mes_id)
                                                    {{$mes->mes_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{$factura->factura_folio}}</td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">
                                            @foreach($regcliente as $cli)
                                                @if($cli->cliente_id == $factura->cliente_id)
                                                    {{trim($cli->cliente_nombrecompleto)}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">
                                            @foreach($regempleado as $emp)
                                                @if($emp->emp_id == $factura->emp_id)
                                                    {{trim($emp->emp_nombrecompleto)}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>  

                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($factura->efactura_montosubsidio,2)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format($factura->efactura_montopagos,2)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;font-size:10px;">{{number_format(($factura->efactura_montosubsidio-$factura->efactura_montopagos),2)}}
                                        </td>                                        
                                        <td style="text-align:center; vertical-align:middle;font-size:10px;">
                                            @foreach($totprods as $partida)
                                                @if($partida->periodo_id == $factura->periodo_id && $partida->factura_folio == $factura->factura_folio)
                                                    {{$partida->partidas}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>                                                               
                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">
                                            {{date("d/m/Y", strtotime($factura->efactura_fecaportacion1))}}
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;font-size:10px;">
                                            {{date("d/m/Y", strtotime($factura->fecreg))}}
                                        </td>                                        
                                        <td style="text-align:center;"">
                                        @switch($factura->efactura_status2)
                                        @case('2')
                                            <img src="{{ asset('images/semaforo_verde.jpg') }}" width="15px" height="15px" title="Pagada"/> 
                                            @break
                                        @case('0')
                                            <img src="{{ asset('images/semaforo_amarillo.jpg') }}" width="15px" height="15px" title="Pendiente por pagar"/> 
                                            @break
                                        @case('1')
                                            <img src="{{ asset('images/semaforo_rojo.jpg') }}" width="15px" height="15px" title="Cancelada"/> 
                                            @break                                            
                                        @default
                                        @endswitch
                                        </td>
                                        
                                        <td style="text-align:center;">
                                            <!--
                                                <a href="{{route('editarFactura',$factura->factura_folio)}}" class="btn btn-warning" title="Editar factura de venta"><i class="fa fa-edit"></i>
                                                </a>
                                            -->
                                                @if(session()->get('role')->rol_name !== 'user')  
                                                   <a href="{{route('borrarFactura',array($factura->periodo_id,$factura->factura_folio))}}" class="btn btn-danger" title="Cancelar factura de venta" onclick="return confirm('¿Seguro que desea cancelar la factura de venta?')"><i class="fa fa-trash"></i>
                                                   </a>
                                                @endif 
                                        </td>

                                        <td style="text-align:center;">
                                            <a href="{{route('ExportFacturaPdf',array($factura->periodo_id,$factura->periodo_id,$factura->factura_folio))}}" class="btn btn-danger" title="Factura de venta de productos (formato PDF)"><i class="fa fa-file-pdf-o"></i>
                                            </a>
                                            <a href="{{route('verfactProductos',array($factura->periodo_id, $factura->factura_folio))}}" class="btn btn-primary btn_xs" title="Facturar productos"><i class="fa fa-cart-plus"></i>
                                            </a>
                                        </td>                                        

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regfactura->appends(request()->input())->links() !!}
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
