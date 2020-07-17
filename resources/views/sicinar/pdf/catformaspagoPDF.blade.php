@extends('sicinar.pdf.layout')

@section('content')
    <!--<h1 class="page-header">Listado de productos</h1>-->
    <table class="table table-hover table-striped" align="center">
        <thead>
        <tr>
            <th><img src="{{ asset('images/logo-cen.jpg') }}" alt="CEN" width="100px" height="50px" style="margin-left: 10px;"/></th>
            <th style="width:410px; text-align:center;"><h4 style="color:black;">Catálogo de Formas de pago</h4></th>
            <th><img src="{{ asset('images/logo-cen-gobmexico.jpg') }}" alt="CEN" width="160px" height="50px" style="margin-right: 10px;"/>
            </th>
        </tr>
        </thead>
    </table>
    <!-- ::::::::::::::::::::::: titulos ::::::::::::::::::::::::: -->
    <table class="table table-sm" align="center">
        <thead>        
        <tr>
            <th style="background-color:darkgreen;text-align:center;vertical-align: middle;width: 5px;"><b style="color:white;font-size: x-small;">id.</b></th>
            <th style="background-color:darkgreen;text-align:left;width: 300px;"><b style="color:white;font-size: x-small;">Forma de pago </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Activo/Inactivo   </b></th>
            <th style="background-color:darkgreen;text-align:center;"><b style="color:white;font-size: x-small;">Fecha reg.        </b></th>
        </tr>
        </thead>
        <tbody>
            @foreach($regfpago as $formapago)
                <tr>
                    <td style="text-align:justify;vertical-align: middle;width: 5px;"><b style="color:black;font-size: x-small;">{{$formapago->fpago_id}}</b>
                    </td>
                    <td style="text-align:justify;vertical-align: middle;width: 300px;"><b style="color:black;font-size: xx-small;">{{$formapago->fpago_desc}}</b>
                    </td>
                    <td style="text-align:center;vertical-align: middle;"><b style="color:black;font-size: x-small;">{{$formapago->fpago_status}}</b>
                    </td>
                    <td style="text-align:center; vertical-align: middle;"><b style="color:black;font-size: x-small;">{{date("d/m/Y", strtotime($formapago->fpago_fecreg))}}</b>
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
