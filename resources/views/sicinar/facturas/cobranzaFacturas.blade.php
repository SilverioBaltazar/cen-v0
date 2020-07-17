@extends('sicinar.principal')

@section('title','Reporte de cobranza')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Crédito y cobranza
                <small>Reporte de cobranza </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Crédito y cobranza </a></li>
                <li><a href="#">Reporte de cobranza</a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'vercobranzafacturas', 'method' => 'POST','id' => 'vercobranzafacturas', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <select class="form-control m-bot15" name="perr" id="perr" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo fiscal</option>
                                        @foreach($regperiodo as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>
                            <div class="row">                                
                                <div class="col-xs-3 form-group"> 
                                    <select class="form-control m-bot15" name="mess" id="mess" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes</option>
                                        @foreach($regmes as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>       
                            </div>                            

                            <div class="row">
                                <div class="col-xs-3 form-group"> 
                                    <input type="number" min="0" max="999999" class="form-control" name="diaa" id="diaa" placeholder="Día">   
                                </div>       
                            </div>                            

                            <div class="row">                                
                                <div class="col-xs-3 form-group"> 
                                    <select class="form-control m-bot15" name="cliee" id="cliee" required>
                                        <option selected="true" disabled="disabled">Seleccionar cliente</option>
                                        @foreach($regcliente as $cli)
                                            <option value="{{$cli->cliente_id}}">{{trim($cli->cliente_nombrecompleto)}}</option>
                                        @endforeach   
                                    </select>                                    
                                </div>       
                            </div>                            

                            <div class="row">   
                                <div class="col-xs-3 form-group">
                                    <select class="form-control m-bot15" name="empp" id="empp" required>
                                        <option selected="true" disabled="disabled">Seleccionar cobrador</option>
                                        @foreach($regempleado as $emp)
                                            <option value="{{$emp->emp_id}}">{{trim($emp->emp_nombrecompleto)}}</option>
                                        @endforeach   
                                    </select>                                    
                                </div>
                            </div>
                            <div class="row">    
                                <div class="col-xs-6 form-group">Enviar reporte a: &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="tipo" checked value="P" style="margin-right:8px;">
                                    Pantalla &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="tipo" value="E" style="margin-right:8px;">Excel &nbsp;&nbsp;&nbsp;
                                    <input type="radio" name="tipo" value="D" style="margin-right:8px;">PDF
                                    </label>
                                </div>  
                            </div>               

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Generar reporte',['class' => 'btn btn-danger btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\cobranzafacturasRequest','#cobranzaFacturas') !!}
@endsection
