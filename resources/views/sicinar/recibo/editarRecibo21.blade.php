@extends('sicinar.principal')

@section('title','Editar Recibo')

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
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small>Comprobantes - Recibos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar recibos </h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarRecibo21',$regrecibos->recibo_folio], 'method' => 'PUT', 'id' => 'actualizarRecibo21', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>Folio : {{$regrecibos->recibo_folio}}</label>
                                </div>  
                            </div>
                            <div class="row">             
                                <div class="col-md-12 offset-md-12">
                                    <label >Codigo:    {{$regrecibos->placa_id}} </label>
                                </div>
                            </div>
                            <div class="row">               
                                <div class="col-md-12 offset-md-12">
                                    <label >Placas: <small>
                                        @foreach($regplaca as $placa)
                                                @if($placa->placa_id == $regrecibos->placa_id)
                                                    {{$placa->placa_placa}}
                                                    @break
                                                @endif
                                        @endforeach </small>
                                    </label>
                                </div>             
                            </div>
                            <div class="row">                                  
                                <div class="col-md-12 offset-md-12">
                                    <label >Resguardatario : <small>
                                        @foreach($regplaca as $placa)
                                                @if($placa->placa_id == $regrecibos->placa_id)
                                                    {{$placa->placa_obs2}}
                                                    @break
                                                @endif
                                        @endforeach </small>
                                    </label>
                                </div>             
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales en formato PDF, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>

                            <div class="row">    
                                @if (!empty($regrecibos->recibo_bfoto1)||!is_null($regrecibos->recibo_bfoto1))  
                                    <div class="col-xs-12 form-group">
                                        <label >Archivo 2 de recibo de bitacora para descarga de combustible en formato PDF</label>
                                        <label ><a href="/images/{{$regrecibos->recibo_bfoto1}}" class="btn btn-danger" title="Archivo 2 de recibo de bitacora para descarga de combustible en formato PDF"><i class="fa fa-file-pdf-o"></i>{{$regrecibos->recibo_bfoto1}}</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-12 form-group">
                                        <label >Archivo 2 de recibo de bitacora para descarga de combustible en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="recibo_bfoto1" id="recibo_bfoto1" placeholder="Subir archivo 2 de recibo de bitacora para descarga de combustible en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-12 form-group">
                                        <label >Archivo 2 de recibo de bitacora para descarga de combustible en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="recibo_bfoto1" id="recibo_bfoto1" placeholder="Subir archivo 2 de recibo de bitacora para descarga de combustible en formato PDF" >
                                    </div>                                                
                                @endif       
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verRecibos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\recibo21Request','#actualizarRecibo21') !!}
@endsection

@section('javascrpt')
<script>
    $('.datepicker').datepicker({
        format: "dd/mm/yyyy",
        startDate: '-29y',
        endDate: '-18y',
        startView: 2,
        maxViewMode: 2,
        clearBtn: true,        
        language: "es",
        autoclose: true
    });
</script>

@endsection