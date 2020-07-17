@extends('sicinar.principal')

@section('title','Editar aportación monetaria')

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
            <h1>
                Menú
                <small> IAPS - Aportaciones monetarias</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar registro de aportación monetaria</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarApor',$regapor->apor_folio], 'method' => 'PUT', 'id' => 'actualizarApor', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >IAP </label>
                                    <select class="form-control m-bot15" name="iap_id" id="iap_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar IAP </option>
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $regapor->iap_id)
                                                <option value="{{$iap->iap_id}}" selected>{{$iap->iap_desc}}</option>
                                            @else                                        
                                               <option value="{{$iap->iap_id}}">{{$iap->iap_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label>Folio: {{$regapor->apor_folio}}</label>
                                </div>             
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo_id" id="periodo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo fiscal </option>
                                        @foreach($regperiodos as $periodo)
                                            @if($periodo->periodo_id == $regapor->periodo_id)
                                                <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_desc}}</option>
                                            @else                                        
                                               <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes de aplicación </label>
                                    <select class="form-control m-bot15" name="mes_id" id="mes_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de aplicación </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regapor->mes_id)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Concepto </label>
                                    <input type="text" class="form-control" name="apor_concepto" id="apor_concepto" placeholder="Concepto de la aportación monetaria" value="{{Trim($regapor->apor_concepto)}}" required>
                                </div>                                
                                <div class="col-xs-4 form-group">
                                    <label >$ Monto </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="apor_monto" id="apor_monto" placeholder="Monto de la aportación monetaria" value="{{$regapor->apor_monto}}" required>
                                </div>          
                            </div>


                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Institución bancaria </label>
                                    <select class="form-control m-bot15" name="banco_id" id="banco_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Institución bancaria</option>
                                        @foreach($regbancos as $banco)
                                            @if($banco->banco_id == $regapor->banco_id)
                                                <option value="{{$banco->banco_id}}" selected>{{$banco->banco_desc}}</option>
                                            @else                                        
                                               <option value="{{$banco->banco_id}}">{{$banco->banco_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                                <div class="col-xs-4 form-group">
                                    <label >Número de cheque </label>
                                    <input type="text" class="form-control" name="apor_nocheque" id="apor_nocheque" placeholder="Número de cheque bancario" value="{{Trim($regapor->apor_nocheque)}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Personal que recibe </label>
                                    <input type="text" class="form-control" name="apor_recibe" id="apor_recibe" placeholder="Nombre de la persona que recibe la aportación monetaria" value="{{Trim($regapor->apor_recibe)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Persona que entrega</label>
                                    <input type="text" class="form-control" name="apor_entrega" id="apor_entrega" placeholder="Nombre de la persona que entrega la aportación monetaria" value="{{Trim($regapor->apor_entrega)}}" required>
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verApor')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\aportacionesRequest','#actualizarApor') !!}
@endsection

@section('javascrpt')
@endsection