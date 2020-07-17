@extends('sicinar.pdf.layout')

@section('content')
    <head>
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;}
        #header { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #content{ 
                  left:10px; top: 0px; margin-bottom: 0px; right:10px;
                  border: solid 0px #000;
                  font: 1em arial, helvetica, sans-serif;
                  color: black; text-align:center;vertical-align: middle; width:910px;} 
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content{ }   
        </style>
    </head>

    <body>
    <header id="header">
        <p style="border:0; font-family:'Arial, Helvetica, sans-serif'; font-size:11px; text-align:center;">
            <img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;" align="left"/> 
            &nbsp;&nbsp;BITACORA DE RENDIMIENTO DE COMBUSTIBLE
            <img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;" align="right"/>
        </p>
        <p style="border:0; font-family:'HelveticaNeueLT Std'; font-size:8px; text-align:center;">
            “2020. Año de Laura Méndez de Cuenca; emblema de la mujer Mexiquense”
        </p>
    </header>
    </body>

    <section id="content">
        <table class="table table-hover table-striped"> 
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr>            
            <tr>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
                <td style="border:0;"></td>
            </tr> 

            @foreach($regbitarendi as $bitarendi)
                <tr>
                    <td style="text-align:left;font-size:10px;width:220px;"><b>
                        Periodo fiscal :{{$bitarendi->periodo_id}}<br>
                        Código :{{$bitarendi->placa_id}}<br>
                        - </b>
                    </td>
                    <td style="border:0; text-align:center;font-size:10px;width:220px;"><b>
                        Mes:{{$bitarendi->Mes_id}}
                        @foreach($regmes as $mes)
                            @if($mes->mes_id == $bitarendi->mes_id)
                                {{Trim($mes->mes_desc)}} 
                                @break
                            @endif
                        @endforeach
                        <br>
                        Placas:{{$bitarendi->placa_placa}}<br>
                        Modelo :
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $bitarendi->placa_id)
                                {{$placa->placa_modelo2}}
                                @break
                            @endif
                        @endforeach</b>
                    </td>
                    <td style="border:0;text-align:center; font-size:10px;width:220px;">
                        <b>Quincena: 
                        @foreach($regquincena as $quincena)
                            @if($quincena->quincena_id == $bitarendi->quincena_id)
                                {{Trim($quincena->quincena_desc)}} 
                                @break
                            @endif
                        @endforeach
                        <br>     
                        Vehículo: 
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $bitarendi->placa_id)
                                {{$placa->placa_desc}}
                                @break
                            @endif
                        @endforeach
                        <br>
                        Tipo de gasolina :
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $bitarendi->placa_id)
                                {{$placa->placa_gasolina}}
                                @break
                            @endif
                        @endforeach</b>                 
                    </td>                    
                    <td style="border:0; text-align:right;font-size:10px;width:270px;">
                        <b>Folio:{{$bitarendi->bitaco_folio}} 
                        <br>
                        Cilindros: 
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $bitarendi->placa_id)
                                {{$placa->placa_cilindros}}
                                @break
                            @endif
                        @endforeach
                        <br>
                        No. de inventario :
                        @foreach($regplaca as $placa)
                            @if($placa->placa_id == $bitarendi->placa_id)
                                {{$placa->placa_inventario}}
                                @break
                            @endif
                        @endforeach</b>  
                    </td>
                </tr>
            @endforeach             
        </table>

        <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
        <table class="table table-sm" align="center" border="1">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size:11px;">#</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:11px;">Fecha</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;"><b style="color:white;font-size:11px;">Servidora<br>Servidor</b>
            </th>            
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:11px;">Dotación<br>$</b>
            </th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:08px;">R  </b></th>  
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:08px;">1/8</b></th>  
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:08px;">1/4</b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:08px;">1/2</b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:08px;">3/4</b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:08px;">F  </b></th>

            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:10px;">Km.<br>Inicial </b></th>  
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:10px;">Km.<br>Final   </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:10px;">Lugar de la comisión</b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:10px;">Hr.<br>Salida  </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:10px;">Hr.<br>Regreso </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size:10px;">Firma </b>          </th>                          
        </tr>
        </thead>

        <tbody>
            @foreach($regbitaservi as $bitaservicio)
                <tr>
                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:03px;"><p align="justify">{{$bitaservicio->servicio}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:06px;"><p align="justify">{{$bitaservicio->servicio_fecha2}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:08px;width:25px;"><p align="justify">{{$bitaservicio->sp_nomb}}</p>
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:06px;">
                        {{number_format($bitaservicio->servicio_dotacion,2)}}</b>
                    </td>

                    <td style="text-align:center;vertical-align: middle;font-size:11px;width:03px;">
                       @if($bitaservicio->servicio_r == '1')
                            <input type="checkbox" name="servicio_r" id="servicio_r" checked="checked" required>
                        @else
                            <input type="checkbox" name="servicio_r" id="servicio_r"  required>
                        @endif 
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:11px;width:03px;">
                        @if($bitaservicio->servicio_18 == '1')
                            <input type="checkbox" name="servicio_18" id="servicio_18" checked="checked" required>
                        @else
                            <input type="checkbox" name="servicio_18" id="servicio_18"  required>
                        @endif
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:11px;width:03px;">                              
                        @if($bitaservicio->servicio_14 == '1')
                            <input type="checkbox" name="servicio_14" id="servicio_14" checked="checked" required>
                        @else
                            <input type="checkbox" name="servicio_14" id="servicio_14"  required>
                        @endif 
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:11px;width:03px;">                             
                        @if($bitaservicio->servicio_12 == '1')
                            <input type="checkbox" name="servicio_12" id="servicio_12" checked="checked" required>
                        @else
                            <input type="checkbox" name="servicio_12" id="servicio_12"  required>
                        @endif      
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:11px;width:03px;">                        
                        @if($bitaservicio->servicio_34 == '1')
                            <input type="checkbox" name="servicio_34" id="servicio_34" checked="checked" required>
                        @else
                            <input type="checkbox" name="servicio_34" id="servicio_34"  required>
                        @endif
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:11px;width:03px;">     
                        @if($bitaservicio->servicio_f == '1')
                            <input type="checkbox" name="servicio_f" id="servicio_f" checked="checked" required>
                        @else
                            <input type="checkbox" name="servicio_f" id="servicio_f"  required>
                        @endif                                
                    </td>                   

                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:05px;">
                        {{$bitaservicio->km_inicial}}
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:05px;">
                        {{$bitaservicio->km_final}}
                    </td>
                    <td style="text-align:left;vertical-align: middle;font-size:08px;width:40px;">
                        {{trim($bitaservicio->servicio_lugar)}}
                    </td>
                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:06px;">{{$bitaservicio->servicio_hrsalida}} </td>
                    <td style="text-align:center;vertical-align: middle;font-size:10px;width:06px;">{{$bitaservicio->servicio_hrregreso}}</td>      

                    <td style="text-align:justify;vertical-align: middle;font-size:xx-small;width:30px;"> </td>                                                                             
                </tr>
            @endforeach
            <tr>
                <td colspan="16" height="25" style="border:0;font-size:07px;vertical-align: middle;"><p align="justify">
                 NOTA: POBALIN-018 El usuario de los vehículos previo a utilizarlos deberá revisar la condiciones en que se y en caso de detectar algún desperfecto, deberá notificarlos de inmediato al titular del área de administración correspondiente, para que de inmediato proceda a su repación,  en caso de que el vehículo presente desperfectos o sufra algún siniestro a causa de la omisión de las previsiones anteriores, los responsables se harán acreedores a las sanciones a que haya lugar; POBALIN-019 Los titulares de las áres de administración y los usuarios de vehiculos de uso operativo serán responsables de que permanescan en los estacionamientos destinados para tal efecto, durante las horas y días inhábiles, salvo que por la naturaleza de las funciones o actividades qa que estén destinados, sea necesaria su utilización en esos días y horas, debiendo las áreas de administración justificar por escrito tal excepción; POBALIN -020 Las áreas de administracón verificaran que los usuarios de vehículos de uso directo y operativo cuenten con licencia de conducir vigente según corresponda; POBALIN-099 Sólo se proporcionará dotación de combustibles, lubricantes y aditivos a los vehículos que se encuentren en operación, inventariados y asegurados.- Será responsabilidad del Coordinador Administrativo o su equivalente reducir las cantidades de las asignaciones mensuales autorizadas, cuando los vehículos se encuentren en reparaciones o estén fuera de circulación por periodo mayor a quince días naturales; POBALIN 101 y POBALIN 102 Será responsabilidad de los titulares de las unidades administrativas vigilar el adecuado consumo de combustible, lubricantes y aditivos, cotejando el tikect que emite el sistema para el control de combustibles, con el que emita la bomba de las estaciones de servicio.   </p>
                </td>
            </tr> 
        </tbody>
        </table>
       
        <!-- ::::::::::::::::::::::: titulos del pie ::::::::::::::::::::::::: -->
        <table style="page-break-inside: avoid;" class="table table-hover table-striped" align="center">          
            <tr>
                <td style="border:0;font-size:10px;text-align:center;width:300px;">
                    RECIBIO<br>__________________________
                </td>
                <td style="border:0;font-size:10px;text-align:center;width:300px;">
                    RESGUARDATARIO<br>
                    @foreach($regplaca as $placa)
                        @if($placa->placa_id == $bitarendi->placa_id and $placa->placa_placa == $bitarendi->placa_placa)
                            {{$placa->placa_obs2}}
                            @break
                        @endif
                    @endforeach 
                </td>                
                <td style="border:0;font-family:'Arial, Helvetica, sans-serif'; font-size:8px; text-align:right; width:350px;">
                    <br><br>
                    <b>Fecha de emisión: Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}} </b>
                </td>
            </tr>         
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