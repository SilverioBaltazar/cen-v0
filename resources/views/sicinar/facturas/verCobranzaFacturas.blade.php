@extends('sicinar.principal')

@section('title','Ver cobranza de facturas de venta')

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
            <h1>Crédito y cobranza
                <small>Reporte de cobranza de facturas </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Crédito y cobranza  </a></li>
                <li><a href="#">Reporte de cobranza </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-body">
                            <div class="page-header" style="text-align:right;">
                                <div class="form-group">
                                    <a href="{{route('cobranzafacturas')}}" class="btn btn-success" title="Nuevo reporte"></span><small>Nuevo reporte</small>
                                    </a>
                                </div>  
                            </div>
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
                                        <td style="text-align:center;">
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
