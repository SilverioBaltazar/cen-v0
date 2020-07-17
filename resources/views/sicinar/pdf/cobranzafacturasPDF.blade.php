@extends('sicinar.pdf.layout')

@section('content')
<!DOCTYPE html>
<html>
    <!--
    <style>
        @page { margin: 180px 50px; }
        #header { position: fixed; left: 0px; top: -180px; right: 0px; height: 150px; background-color: orange; text-align: center; }
        #footer { position: fixed; left: 0px; bottom: -180px; right: 0px; height: 150px; background-color: lightblue; }
        #footer .page:after { content: counter(page, upper-roman); }
    </style>
    @page:right{ 
            @bottom-left {
            margin: 10pt 0 30pt 0;
            border-top: .25pt solid #666;
            content: "My book";
            font-size: 9pt;
            color: #333;
            }
        }
        table, figure {
            page-break-inside: avoid;
        }        
    #footer .page:after { content: counter(page); }
    #footer .page:after {content: "Page " counter(page) " of " counter(pages);}
    #content{ position: fixed; left: 0px; top: 0px; right: 0px; text-align:left;vertical-align: middle; width:1050px;}   
    -->
    <head>      
        <style>
        @page { margin-top: 30px; margin-bottom: 30px; margin-left: 50px; margin-right: 50px; } 
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;font-size: 12px;}
        h1 {
        page-break-before: always;
        }

        #header { position: fixed; left: 0px; top: 0px; right: 0px; height: 375px; }
        #content{ 
                  left: 50px; top: 0px; margin-bottom: 0px; right: 50px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:left;vertical-align: middle; width:1000px;}   
        #footer { position: fixed; left: 0px; bottom: -10px; right: 0px; height: 60px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page); }        
        </style>
    </head>
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <body>
    <div id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/logo-cen.jpg') }}" alt="CEN" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;REPORTE DE RUTA DE COBRANZA (FACTURAS - CLIENTES)
            <img src="{{ asset('images/logo-cen-gobmexico.jpg') }}" alt="CEN" width="140px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
        <p style="border:0; font-size:08px; text-align:center;"><b>Fecha de reporte: {!! date('d/m/Y') !!}</b></p>             
    </div>

    
    <div id="content">               
        <p>...</p>
        <table class="table table-hover table-striped" align="center" width="100%">  
            <tr><td></td><td></td><td></td><td></td></tr>       
            <tr><td></td><td></td><td></td><td></td></tr>
            
            @foreach($regfactura as $factura)
            <tr>
                <td style="border:0; text-align:left;font-size:10px;">Periodo:<b>{{$factura->periodo_id}}</b></td>
                <td style="border:0; text-align:center;font-size:10px;">
                    <b>Responsable de ruta:&nbsp;&nbsp; 
                    @foreach($regempleado as $emp)
                           {{Trim($emp->emp_nombrecompleto)}} 
                           @break
                    @endforeach
                    </b>                
                </td>
                <td style="border:0; text-align:right;font-size:10px;">
                </td>   
                <td style="text-align:right; font-size:10px;">Mes:<b>
                     @foreach($regmes as $mes)
                        @if($mes->mes_id == $factura->mes_id)
                           {{Trim($mes->mes_desc)}} 
                           @break
                        @endif
                    @endforeach
                    </b>
                </td>             
            </tr>
            @break
            @endforeach
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center" >     
            <tr>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Folio<br>Factura </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Importe          </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Acum.<br>Pagos   </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Saldo       </b></th>                
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Cliente     </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Domicilio   </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Colonia     </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">C.P.        </b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Teléfono<br>Celular</b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Fec.Prox.<br>Cobro</b></th>
                <th style="background-color:darkgreen;text-align:left;"><b style="color:white;font-size:09px;">Status<br>Factura </b></th>                                                
            </tr>
            @foreach($regfactura as $factura) 
                <tr>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{Trim($factura->factura_folio)}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{number_format($factura->subsidio,2)}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{number_format($factura->acumpagos,2)}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{number_format(($factura->subsidio-$factura->acumpagos),2)}}</b>
                    </td>                                                            
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{Trim($factura->nombre)}}</b>
                    </td>                    
               
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;">
                        {{Trim($factura->cliente_dom)}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;width:07px;">                        
                        {{Trim($factura->cliente_col)}} </b>
                    </td>                      
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;width:07px;">                        
                        {{Trim($factura->cliente_cp)}} </b>
                    </td>                      
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;width:07px;">                        
                        {{Trim($factura->cliente_tel).' '.Trim($factura->cliente_cel)}}</b>
                    </td>                               
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:09px;"> 
                        {{substr($factura->fecproxcobro,0,10)}}
                        </b>
                    </td>                               
                    <td style="text-align:justify;vertical-align: middle;"><b style="font-size:07px;width:07px;">                        
                        @if($factura->efactura_status2 == '0')
                            Por cobrar
                        @else
                            @if($factura->efactura_status2 == '1')
                                Cancelada
                             @else
                               @if($factura->efactura_status2 == '2')
                                   Pagada
                                @else                         
                                   Sin especificar
                                @endif  
                            @endif
                        @endif
                        </b>
                    </td>                                                                                                                                           
                </tr>
            @endforeach
            
            <!--<p style="page-break-before: always;">--</p> -->
            <p style="page-break-inside: avoid;">++
                <div id="footer">
                    <p class="page">Page </p>
                    <p style="border:0; text-align:right;font-size:08px;">
                    <b>Campaña Educativa Nacional (CEN)</b>
                    </p>
                </div>  
            </p>
        </table> 
    </div>
    

    </body>  
</html>    
@endsection
