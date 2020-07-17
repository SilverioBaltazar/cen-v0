@extends('sicinar.principal')

@section('title','Editar Factura de venta ')

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
            <h1>
                Menú
                <small>Ventas - Facturar productos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarFactura',$regfactura->factura_folio], 'method' => 'PUT', 'id' => 'actualizarFactura', 'enctype' => 'multipart/form-data']) !!}
                        
                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <tr>
                                    <td style="text-align:left; vertical-align: middle;color:green;">
                                        <label>Folio : {{$regfactura->factura_folio}}</label>
                                    </td>  
                                    <td style="text-align:right; vertical-align: middle;color:green;"> 
                                        <label >Fecha de registro : {{date("d/m/Y", strtotime($regfactura->fecreg))}} </label>
                                    </td>             
                                </tr>
                            </table>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Cliente</label>
                                    <select class="form-control m-bot15" name="cliente_id" id="cliente_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar cliente </option>
                                        @foreach($regcliente as $cli)
                                            @if($cli->cliente_id == $regfactura->cliente_id)
                                                <option value="{{$cli->cliente_id}}" selected>{{trim($cli->cliente_nombrecompleto)}}</option>
                                            @else                                        
                                                <option value="{{$cli->cliente_id}}">{{trim($cli->cliente_nombrecompleto)}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Crédito de la venta</label>
                                    <select class="form-control m-bot15" name="tipocredito_id" id="tipocredito_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar crédito de la venta </option>
                                        @foreach($regtipocredito as $credito)
                                            @if($credito->tipocredito_id == $regfactura->tipocredito_id)
                                                <option value="{{$credito->tipocredito_id}}" selected>{{$credito->tipocredito_desc}}</option>
                                            @else                                        
                                               <option value="{{$credito->tipocredito_id}}">{{$credito->tipocredito_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label>$ Monto a subsidiar</label>
                                    <input required autocomplete="off" id="efactura_montosubsidio" name="efactura_montosubsidio" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="$ Monto a subsidiar" value="{{$regfactura->efactura_montosubsidio}}">
                                </div>
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-2 form-group">
                                    <label>$ Monto de las aportaciones</label>
                                    <input required autocomplete="off" id="efactura_montoaportaciones" name="efactura_montoaportaciones" min="0" max="999999999.99" class="form-control" type="decimal(9,2)" placeholder="$ Monto de las aportaciones" value="{{$regfactura->efactura_montoaportaciones}}">
                                </div>
                            </div>                            

                            <div class="row">                            
                                <div class="col-xs-3 form-group">
                                    <label >Vendedor </label>
                                    <select class="form-control m-bot15" name="emp_id" id="emp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar vendedor </option>
                                        @foreach($regempleado as $emp)
                                            @if($emp->emp_id == $regfactura->emp_id)
                                                <option value="{{$emp->emp_id}}" selected>{{trim($emp->emp_nombrecompleto)}}</option>
                                            @else                                        
                                               <option value="{{$emp->emp_id}}">{{trim($emp->emp_nombrecompleto)}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>

                            <div class="row">
                                @if(count($errors) > 0)
                                    <div class="alert alert-danger" role="alert">
                                        <ul>
                                            @foreach($errors->all() as $error)
                                                <li>{{ $error }}</li>
                                            @endforeach
                                        </ul>
                                    </div>
                                @endif
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Guardar',['class' => 'btn btn-primary btn-flat pull-right']) !!}
                                    <a href="{{route('verFacturas')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
                                </div>
                            </div>
                        </div>
                        {!! Form::close() !!}
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\facturaRequest','#actualizarFactura') !!}
@endsection

@section('javascrpt')
@endsection
