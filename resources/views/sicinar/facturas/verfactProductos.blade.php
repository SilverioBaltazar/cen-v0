@extends('sicinar.principal')

@section('title','Ver productos de factura de venta')

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
                <small>Facturar productos - Seleccionar para editar o registrar productos a facturar</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Ventas         </a></li>
                <li><a href="#">Facturar productos - productos  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regfactura as $factura)
                            <tr>
                                <td style="text-align:left;  vertical-align: middle;">  </td>                                     
                                <td style="text-align:center;vertical-align: middle;">  </td>
                                <td style="text-align:center;vertical-align: middle;">  </td>
                                <td style="text-align:right; vertical-align: middle;">  </td>
                                <td style="text-align:right; vertical-align: middle;">   
                                    <a href="{{route('verFacturas')}}" role="button" id="cancelar" class="btn btn-success"><small>Regresar a facturas</small>
                                    </a>
                                    <a href="{{route('nuevafactProducto',array($factura->periodo_id,$factura->factura_folio))}}" class="btn btn-primary btn_xs" title="Nuevo producto a facturar"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span><small>Producto</small>
                                    </a>
                                </td>                                     
                            </tr>                                                   
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;"> 
                                    <input type="hidden" id="periodo_id"    name="factura_folio" value="{{$factura->periodo_id}}">  
                                    <input type="hidden" id="mes_id"        name="mes_id"        value="{{$factura->mes_id}}">  
                                    <input type="hidden" id="dia_id"        name="dia_id"        value="{{$factura->dia_id}}">  
                                    <input type="hidden" id="factura_folio" name="factura_folio" value="{{$factura->factura_folio}}">  
                                    <label style="color:green;">Folio factura: </label><b>{{$factura->factura_folio}}</b>
                                </td>
                                <td style="text-align:center; vertical-align: middle;">  
                                    <input type="hidden" id="cliente_id" name="cliente_id" value="{{$factura->cliente_id}}">  
                                    <label style="color:green;">Cliente: </label><b>
                                    @foreach($regcliente as $cli)
                                        @if($cli->cliente_id == $factura->cliente_id)
                                            {{trim($cli->cliente_nombrecompleto)}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>                                    
                                </td>                                
                                <td style="text-align:center; vertical-align: middle;">   
                                    <input type="hidden" id="emp_id" name="emp_id" value="{{$factura->emp_id}}">  
                                    <label style="color:green;">Vendedor : </label><b>
                                    @foreach($regempleado as $emp)
                                        @if($emp->emp_id == $factura->emp_id)
                                            {{trim($emp->emp_nombrecompleto)}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>                                                                        
                                </td>                                                                     
                                <td style="text-align:right; vertical-align: middle;">   
                                </td>                    
                                <td style="text-align:right; vertical-align: middle;">   
                                    <label style="color:green;">Fecha registro:</label>
                                    <b>{{date("Y/m/d", strtotime($factura->fecreg))}}</b>
                                </td>                                     
                            </tr>  
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;"> 
                                    <input type="hidden" id="tipocredito_id" name="tipocredito_id" value="{{$factura->tipocredito_id}}">  
                                    <label style="color:green;">Crédito: </label>
                                    @foreach($regtipocredito as $credito)
                                        @if($credito->tipocredito_id == $factura->tipocredito_id)
                                            {{$credito->tipocredito_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>
                                <td style="text-align:center; vertical-align: middle;"> 
                                    <input type="hidden" id="efactura_montosubsidio" name="efactura_montosubsidio" value="{{$factura->efactura_montosubsidio}}">  
                                    <label style="color:green;">Monto subsidiado: </label><b>${{number_format($factura->efactura_montosubsidio,2)}}</b>
                                </td>                                
                                <td style="text-align:center; vertical-align: middle;"> 
                                    <input type="hidden" id="efactura_montoaportaciones" name="efactura_montoaportaciones" value="{{$factura->efactura_montoaportaciones}}">  
                                    <label style="color:green;">Monto de aportaciones: </label><b>${{number_format($factura->efactura_montoaportaciones,2)}}</b> 
                                </td>                                

                                <td style="text-align:right; vertical-align: middle;">   
                                    <input type="hidden" id="efactura_numaportaciones" name="efactura_numaportaciones" value="{{$factura->efactura_numaportaciones}}">  
                                    <label style="color:green;">Pagos a realizar: </label><b>{{number_format($factura->efactura_numaportaciones,2)}}</b>
                                </td>
                                <td style="text-align:right; vertical-align: middle;">   
                                    <label style="color:green;">Fecha de pago:</label>
                                    <b> {{substr($factura->efactura_fecaportacion1,0,10)}}</b>
                                </td>                                                                                                    
                            </tr>                                  
                            @endforeach     
                        </table>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">#<br>Partida  </th>
                                        <th style="text-align:left;   vertical-align: middle;">Código<br>Barras </th>
                                        <th style="text-align:left;   vertical-align: middle;">Producto      </th>
                                        <th style="text-align:center; vertical-align: middle;">Cantidad      </th>
                                        <th style="text-align:center; vertical-align: middle;">Precio        </th> 
                                        <th style="text-align:center; vertical-align: middle;">Importe       </th> 
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regfacturaprod as $partida)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$partida->dfactura_npartida}}</small></td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{$partida->codigo_barras}}    </small></td>
                                        <td style="text-align:left; vertical-align: middle;"><small>{{trim($partida->descripcion)}}</small></td>                                        
                                        <td style="text-align:center;vertical-align: middle;"><small>{{number_format($partida->cantidad,0)}}</small>
                                        </td>                                        
                                        <td style="text-align:center;vertical-align: middle;"><small>${{number_format($partida->precio,2)}} </small>
                                        </td>  
                                        <td style="text-align:center;vertical-align: middle;">
                                            <small>${{number_format($partida->cantidad*$partida->precio,2)}}</small>
                                        </td>                                       
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarfactProducto',array($partida->periodo_id,$partida->factura_folio,$partida->dfactura_npartida))}}" class="btn btn-warning" title="Editar producto de factura de venta"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarfactProducto',array($partida->periodo_id,$partida->factura_folio, $partida->dfactura_npartida))}}" class="btn btn-danger" title="Borrar producto de factura de venta" onclick="return confirm('¿Seguro que deseas borrar producto de factura de venta?')"><i class="fa fa-trash"></i>
                                            </a>
                                        </td>                                    

                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regfacturaprod->appends(request()->input())->links() !!}
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
