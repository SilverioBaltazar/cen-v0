@extends('sicinar.pdf.layout')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:740px; text-align:center;"><h4 style="color:black;">Padrón de placas </h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>   
        <tr>
            <th colspan="9" ></th>
        </tr>  
        <tr>
            <th style="background-color:darkgreen;text-align:center;width: 3px;"><b style="color:white;font-size: xx-small;">Código</b></th>
            <th style="background-color:darkgreen;text-align:center;width: 3px;"><b style="color:white;font-size: xx-small;">Placas</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 30px;"><b style="color:white;font-size: xx-small;">Placa Ant.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 30px;"><b style="color:white;font-size: xx-small;">Descrip. del vehiculo</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 30px;"><b style="color:white;font-size: xx-small;">Serie<br>Motor</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 30px;"><b style="color:white;font-size: xx-small;">No.<br>Cilindros</b></th>                        
            <th style="background-color:darkgreen;text-align:left;width: 20px;"><b style="color:white;font-size: xx-small;">Marca</b></th>     
            <th style="background-color:darkgreen;text-align:left;width: 5px;"><b style="color:white;font-size: xx-small;">Tipo <br>Gasto</b></th>                                    
            <th style="background-color:darkgreen;text-align:left;width: 20px;"><b style="color:white;font-size: xx-small;">Tipo <br>Op.admon.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 50px;"><b style="color:white;font-size: xx-small;">Unidad Admon. responsable</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 50px;"><b style="color:white;font-size: xx-small;">Resguardataria(o)</b></th>            
            <th style="background-color:darkgreen;text-align:center; width: 5px;"><b style="color:white;font-size: xx-small;">Activa<br>Inactiva</b></th>
            <th style="background-color:darkgreen;text-align:center; width: 8px;"><b style="color:white;font-size: xx-small;">Fecha reg.</b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regplaca as $placa)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_placa}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_anterior}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_desc}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_serie}}</b>
                    </td>    
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_cilindros}}</b>
                    </td>
                                       
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->marca_desc}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->gasto_desc}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->tipoo_desc}}</b>
                    </td>

                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_obs1}}</b>
                    </td>    
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$placa->placa_obs2}}</b>
                    </td>
                    
                    @if($placa->placa_status1 == 'S')
                       <td style="text-align:center;vertical-align: middle;"><b style="color:black;font-size: xx-small;">Activa</b>
                       </td>
                    @else
                       <td style="text-align:center;vertical-align: middle;"><b style="color:black;font-size: xx-small;">Inactiva</b>
                       </td>
                    @endif
                    <td style="text-align:center; vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{date("d/m/Y", strtotime($placa->fecreg))}}</b>
                    </td>                
                </tr>
            @endforeach
        </tbody>
    </table>
    <table style="page-break-inside: avoid;" class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th style="text-align:right;"><b style="font-size: x-small;"><b>Fecha de emisión: {!! date('d/m/Y') !!}</b></th>
        </tr>
        </thead>
    </table>
@endsection