@extends('sicinar.pdf.layout')

@section('content')
    <head>
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;}
        #header { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #content{ 
                  left: 50px; top: 0px; margin-bottom: 0px; right: 50px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:center;vertical-align: middle; width:720px;} 
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content{ }   
        </style>
    </head>

    <body>
    <header id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/>            
            &nbsp;&nbsp;RECIBO BITACORA PARA DESCARGA DE COMBUSTIBLE
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
        
        <p style="border:0; font-family:'HelveticaNeueLT Std'; font-size:8px; text-align:center;">
            “2020. Año de Laura Méndez de Cuenca; emblema de la mujer Mexiquense”
        </p>
    </header>
    </body>

    <section id="content">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr>            
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr> 

            @foreach($regrecibos as $recibo)
                <tr>
                    <td style="border:0; text-align:left;font-size:10px;"><b>
                        Periodo fiscal:{{$recibo->periodo_id}}<br>
                        Código:{{$recibo->placa_id}}  <br>
                        - <br>
                        KM. INICIAL: {{$recibo->recibo_ki}}<br>
                        KM. FINAL: {{$recibo->recibo_kf}}  <br>
                        Quincena: 
                        @foreach($regquincena as $quincena)
                            @if($quincena->quincena_id == $recibo->quincena_id)
                                {{Trim($quincena->quincena_desc)}} 
                                @break
                            @endif
                        @endforeach
                        </b>
                    </td>
                    <td style="border:0; text-align:center;font-size:09px;"><b>
                        Mes:{{$recibo->Mes_id}}
                        @foreach($regmes as $mes)
                            @if($mes->mes_id == $recibo->mes_id)
                                {{Trim($mes->mes_desc)}} 
                            @break
                            @endif
                        @endforeach <br>
                        Placas:{{$recibo->placa_placa}} <br>
                        Nivel de combustible  <br>
                        @if($recibo->recibo_ir == '1')
                            R  <input type="checkbox" name="recibo_ir" id="recibo_ir" checked="checked"  required>
                        @else
                            R  <input type="checkbox" name="recibo_ir" id="recibo_ir"  required>
                        @endif      
                        @if($recibo->recibo_i18 == '1')
                            1/8<input type="checkbox" name="recibo_i18" id="recibo_i18" checked="checked"  required>
                        @else
                            1/8<input type="checkbox" name="recibo_i18" id="recibo_i18"  required>
                        @endif      
                        @if($recibo->recibo_i14 == '1')
                            1/4<input type="checkbox" name="recibo_i14" id="recibo_i14" checked="checked"  required>
                        @else
                            1/4<input type="checkbox" name="recibo_i14" id="recibo_i14"  required>
                        @endif      
                        @if($recibo->recibo_i12 == '1')
                            1/2<input type="checkbox" name="recibo_i12" id="recibo_i12" checked="checked"  required>
                        @else
                            1/2<input type="checkbox" name="recibo_i12" id="recibo_i12"  required>
                        @endif      
                        @if($recibo->recibo_i34 == '1')
                            3/4<input type="checkbox" name="recibo_i34" id="recibo_i34" checked="checked"  required>
                        @else
                            3/4<input type="checkbox" name="recibo_i34" id="recibo_i34"  required>
                        @endif                                  
                        @if($recibo->recibo_if == '1')
                            F  <input type="checkbox" name="recibo_if" id="recibo_if" checked="checked"  required>
                        @else
                            F  <input type="checkbox" name="recibo_if" id="recibo_if"  required>
                        @endif  
                        <br>                                       
                        @if($recibo->recibo_fr == '1')
                            R  <input type="checkbox" name="recibo_fr" id="recibo_fr" checked="checked"  required>
                        @else
                            R  <input type="checkbox" name="recibo_fr" id="recibo_fr"  required>
                        @endif      
                        @if($recibo->recibo_f18 == '1')
                            1/8<input type="checkbox" name="recibo_f18" id="recibo_f18" checked="checked"  required>
                        @else
                            1/8<input type="checkbox" name="recibo_f18" id="recibo_f18"  required>
                        @endif      
                        @if($recibo->recibo_f14 == '1')
                            1/4<input type="checkbox" name="recibo_f14" id="recibo_f14" checked="checked"  required>
                        @else
                            1/4<input type="checkbox" name="recibo_f14" id="recibo_f14"  required>
                        @endif      
                        @if($recibo->recibo_f12 == '1')
                            1/2<input type="checkbox" name="recibo_f12" id="recibo_f12" checked="checked"  required>
                        @else
                            1/2<input type="checkbox" name="recibo_f12" id="recibo_f12"  required>
                        @endif      
                        @if($recibo->recibo_f34 == '1')
                            3/4<input type="checkbox" name="recibo_f34" id="recibo_f34" checked="checked"  required>
                        @else
                            3/4<input type="checkbox" name="recibo_f34" id="recibo_f34"  required>
                        @endif                                  
                        @if($recibo->recibo_ff == '1')
                            F  <input type="checkbox" name="recibo_ff" id="recibo_ff" checked="checked"  required>
                        @else
                            F  <input type="checkbox" name="recibo_ff" id="recibo_ff"  required>
                        @endif                                                        
                    </b>
                    </td>
                    <td style="border:0; text-align:right;font-size:10px;"><b>
                        Folio:{{$recibo->recibo_folio}}  <br>
                        Asignación: 
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $recibo->placa_id)
                                {{Trim($placa->tipog_desc)}} 
                                @break
                            @endif
                        @endforeach               <br>
                        - <br>
                        Fecha: {{$recibo->dia_id1.' '}}
                        @foreach($regmes as $mes)
                            @if($mes->mes_id == $recibo->mes_id1)
                                {{Trim($mes->mes_desc)}} 
                                @break
                            @endif
                        @endforeach      <br>
                        Fecha: {{$recibo->dia_id2.' '}}
                        @foreach($regmes as $mes)
                            @if($mes->mes_id == $recibo->mes_id2)
                                {{Trim($mes->mes_desc)}} 
                                @break
                            @endif
                        @endforeach  <br>
                        Resguardatario: 
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $recibo->placa_id and $placa->placa_placa == $recibo->placa_placa)
                                {{$placa->placa_obs2}}
                                @break
                            @endif
                        @endforeach
                        </b>
                    </td>
                </tr>
            @endforeach             
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center" border="1">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">#</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size: x-small;">Folio<br>Autoriz.</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">tkp<br>Fecha</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">tkp<br>Hora</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">tkp<br>Importe</b>
            </th>  
            <th style="background-color:darkorange;text-align:center;"><b style="color:white;font-size: x-small;">tkb<br>RFC  </b>
            </th>  
            <th style="background-color:darkorange;text-align:center;"><b style="color:white;font-size: x-small;">tkb<br>Fecha</b>
            </th>
            <th style="background-color:darkorange;text-align:center;"><b style="color:white;font-size: x-small;">tkb<br>Hora</b>
            </th>            
            <th style="background-color:darkorange;text-align:center;"><b style="color:white;font-size: x-small;">tkb<br>Importe</b>
            </th>
            <th style="background-color:darkorange;text-align:center;"><b style="color:white;font-size: x-small;">Edo.</b>
            </th>             
            <th style="background-color:darkorange;text-align:center;"><b style="color:white;font-size: x-small;">Forma pago</b>
            </th>            
 
        </tr>
        </thead>

        <tbody>
            @foreach($regcargas as $carga)
                <tr>
                    <td style="text-align:center;vertical-align: middle;font-size:xx-small;"><p align="justify">{{$carga->carga}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:xx-small;"><p align="justify">{{$carga->tkpag_folaprob}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:xx-small;"><p align="justify">{{$carga->tkpag_fecha2}}</p>
                    </td>
                    <td style="text-align:left;vertical-align: middle;font-size:xx-small;"><p align="justify">{{$carga->tkpag_hora}}</p>
                    </td>      

                    <td style="text-align:center;vertical-align: middle;font-size: xx-small;">
                        ${{number_format($carga->tkpag_importe,2)}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size: xx-small;">
                        {{$carga->tkbomba_rfc}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size: xx-small;">
                        {{$carga->tkbomba_fecha2}}</b>
                    </td>
                    <td style="text-align:left;vertical-align: middle;font-size:xx-small;"><p align="justify">{{$carga->tkbomba_hora}}</p>
                    </td>                     
                    <td style="text-align:center;vertical-align: middle;font-size: xx-small;">
                        ${{number_format($carga->tkbomba_importe,2)}}</b>
                    </td>
                    @if($carga->tkpag_importe === $carga->tkbomba_importe and $carga->tkpag_fecha2 === $carga->tkbomba_fecha2)
                        <td style="color:darkgreen;text-align:center; vertical-align: middle;font-size:10px;" title="Importe y fecha de ticket de bomba y ticket de pago correctos">Ok
                        </td>                                            
                    @else
                        <td style="color:darkred; text-align:center; vertical-align: middle;font-size:10px;" title="Importe y/o fecha de ticket de bomba y ticket de pago distintos">Err
                        </td>                                            
                    @endif
                    <td style="text-align:justify;vertical-align: middle;font-size:10px;">  
                    @foreach($regfpagos as $fpago)
                        @if($fpago->fp_id == $carga->fp_id)
                            {{trim($fpago->fp_desc)}}        
                            @break
                        @endif
                    @endforeach       
                    </td>  

                </tr>
            @endforeach
            <tr>
                <td colspan="16" height="25" style="font-size:07px;vertical-align: middle;"><p align="justify">
                 NOTA: POBALIN-018 El usuario de los vehículos previo a utilizarlos deberá revisar la condiciones en que se y en caso de detectar algún desperfecto, deberá notificarlos de inmediato al titular del área de administración correspondiente, para que de inmediato proceda a su repación,  en caso de que el vehículo presente desperfectos o sufra algún siniestro a causa de la omisión de las previsiones anteriores, los responsables se harán acreedores a las sanciones a que haya lugar; POBALIN-019 Los titulares de las áres de administración y los usuarios de vehiculos de uso operativo serán responsables de que permanescan en los estacionamientos destinados para tal efecto, durante las horas y días inhábiles, salvo que por la naturaleza de las funciones o actividades qa que estén destinados, sea necesaria su utilización en esos días y horas, debiendo las áreas de administración justificar por escrito tal excepción; POBALIN -020 Las áreas de administracón verificaran que los usuarios de vehículos de uso directo y operativo cuenten con licencia de conducir vigente según corresponda; POBALIN-099 Sólo se proporcionará dotación de combustibles, lubricantes y aditivos a los vehículos que se encuentren en operación, inventariados y asegurados.- Será responsabilidad del Coordinador Administrativo o su equivalente reducir las cantidades de las asignaciones mensuales autorizadas, cuando los vehículos se encuentren en reparaciones o estén fuera de circulación por periodo mayor a quince días naturales; POBALIN 101 y POBALIN 102 Será responsabilidad de los titulares de las unidades administrativas vigilar el adecuado consumo de combustible, lubricantes y aditivos, cotejando el tikect que emite el sistema para el control de combustibles, con el que emita la bomba de las estaciones de servicio.   </p>
                </td>
            </tr>             
        </tbody>
        </table>
       
        <!-- ::::::::::::::::::::::: titulos del pie ::::::::::::::::::::::::: 
            Código QR  http://goqr.me/api/
                       http://goqr.me/api/doc/create-qr-code/
        -->
        <table class="table table-sm" align="center">
        <thead>        
            <tr>
                <td style="border:0;">
                    <br><br>
                </td>
                <td style="border:0;text-align:center;font-size:09px;">
                    ________________________________<br>
                    Firma      
                </td>
                <td style="border:0; text-align:right;font-size:08px;"><b>
                    <br>
                    Fecha de emisión: Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}}                    
                   </b>
                </td>
            </tr>
            <tr>
                <td style="border:0;">  </td>
                <td style="border:0;">  </td>                
                <td align="right">
                    <img src = "https://api.qrserver.com/v1/create-qr-code/?data=http://187.216.191.89&size=100x100" alt="" title="" align="right"/>
                </td>
            </tr>             
        </thead>
        </table>
        

    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>SERVICIOS GENERALES, AREA DE COMBUSTIBLES
                </td>
            </tr>
        </table>
    </footer>    
    </body>
@endsection