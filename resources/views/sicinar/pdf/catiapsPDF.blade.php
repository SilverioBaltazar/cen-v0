@extends('sicinar.pdf.layoutiaps')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/Gobierno.png') }}" alt="EDOMEX" width="90px" height="55px" style="margin-right: 15px;"/></th>
            <th style="width:740px; text-align:center;"><h4 style="color:black;">Catálogo de Instituciones Privadas</h4></th>
            <th><img src="{{ asset('images/Edomex.png') }}" alt="EDOMEX" width="80px" height="55px" style="margin-left: 15px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>   
        <tr>
            <th style="background-color:darkgreen;text-align:center;width: 3px;"><b style="color:white;font-size: xx-small;">
            id. </b></th>
            <th style="background-color:darkgreen;text-align:left;width: 30px;"><b style="color:white;font-size: xx-small;">Institución Privada </b></th>   
            <th style="background-color:darkgreen;text-align:left;width: 5px;"><b style="color:white;font-size: xx-small;">Teléfono </b></th>                                    
  
            <th style="background-color:darkgreen;text-align:center; width: 5px;"><b style="color:white;font-size: xx-small;">Act.<br> Inact. </b></th>
            <th style="background-color:darkgreen;text-align:center; width: 8px;"><b style="color:white;font-size: xx-small;">Fecha reg. </b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regiap as $iap)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$iap->iap_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$iap->iap_desc}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$iap->iap_telefono}}</b>
                    </td>                    

                    <td style="text-align:center;vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{$iap->iap_status}}</b>
                    </td>
                    <td style="text-align:center; vertical-align: middle;"><b style="color:black;font-size: xx-small;">{{date("d/m/Y", strtotime($iap->iap_fecreg))}}</b>
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