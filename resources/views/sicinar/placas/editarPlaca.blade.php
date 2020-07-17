@extends('sicinar.principal')

@section('title','Editar Placa')

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
                <small>Padrón vehicular - Placas - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        {!! Form::open(['route' => ['actualizarPlaca',$regplaca->placa_id], 'method' => 'PUT', 'id' => 'actualizarPlaca', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">       
                                <div class="col-xs-2 form-group">
                                    <label >Placa actual </label>
                                    <input type="text" class="form-control" name="placa_placa" id="placa_placa" placeholder="Placa actual" value="{{$regplaca->placa_placa}}" required>
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Placa anterior </label>
                                    <input type="text" class="form-control" name="placa_anterior" id="placa_anterior" placeholder="Placa anterior" value="{{Trim($regplaca->placa_anterior)}}" required>
                                </div>  
                                <div class="col-xs-8 form-group" style="text-align:right;color:green;">
                                    <label>Código: {{$regplaca->placa_id}}</label>
                                </div>                                    
                            </div>

                            <div class="row">                                
                                <div class="col-xs-8 form-group">
                                    <label >Descripción de la unidad </label>
                                    <input type="text" class="form-control" name="placa_desc" id="placa_desc" placeholder="Descripción de la unidad" value="{{Trim($regplaca->placa_desc)}}" required>
                                </div>     
                            </div>

                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Serie del motor </label>
                                    <input type="text" class="form-control" name="placa_serie" id="placa_serie" placeholder="Serie del motor" value="{{Trim($regplaca->placa_serie)}}" required>
                                </div>                                                                
                                <div class="col-xs-2 form-group">
                                    <label >No. de cilindros </label>
                                    <input type="text" class="form-control" name="placa_cilindros" id="placa_cilindros"  placeholder="No. de cilindros de la unidad" value="{{$regplaca->placa_cilindros}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Marca </label>
                                    <select class="form-control m-bot15" name="marca_id" id="marca_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar marca </option>
                                        @foreach($regmarca as $marca)
                                            @if($marca->marca_id == $regplaca->marca_id)
                                                <option value="{{$marca->marca_id}}" selected>{{$marca->marca_desc}}</option>
                                            @else                                        
                                               <option value="{{$marca->marca_id}}">{{$marca->marca_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label >Modelo </label>
                                    <input type="text" class="form-control" name="placa_modelo2" id="placa_modelo2" placeholder="Modelo de la unidad" value="{{$regplaca->placa_modelo2}}" required>
                                </div>     
                                <div class="col-xs-2 form-group">
                                    <label >No. de inventario </label>
                                    <input type="text" class="form-control" name="placa_inventario" id="placa_inventario" placeholder="No. de inventario" value="{{Trim($regplaca->placa_inventario)}}" required>
                                </div>                                
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de gasolina (Premium, Magna o Diesel) </label>
                                    <input type="text" class="form-control" name="placa_gasolina" id="placa_gasolina"  placeholder="Tipo de gasolina: Premium, Magna o Diesel" value="{{$regplaca->placa_gasolina}}" required>
                                </div>
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de gasto presupuestal </label>
                                    <select class="form-control m-bot15" name="tipog_id" id="tipog_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de gasto presupuestal </option>
                                        @foreach($regtipogasto as $gasto)
                                            @if($gasto->tipog_id == $regplaca->tipog_id)
                                                <option value="{{$gasto->tipog_id}}" selected>{{$gasto->tipog_desc}}</option>
                                            @else                                        
                                               <option value="{{$gasto->tipog_id}}">{{$gasto->tipog_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de operación admon. </label>
                                    <select class="form-control m-bot15" name="tipoo_id" id="tipoo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de operación admon. </option>
                                        @foreach($regtipooper as $oper)
                                            @if($oper->tipoo_id == $regplaca->tipoo_id)
                                                <option value="{{$oper->tipoo_id}}" selected>{{$oper->tipoo_desc}}</option>
                                            @else                                        
                                               <option value="{{$oper->tipoo_id}}">{{$oper->tipoo_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Unidad administrativa a la que es asignada la unidad </label>
                                    <select class="form-control m-bot15" name="dependencia_id" id="dependencia_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Unidad admon. a la que es asignada la unidad </option>
                                        @foreach($regdepen as $dependen)
                                            @if($dependen->depen_id == $regplaca->dependencia_id)
                                                <option value="{{$dependen->depen_id}}" selected>{{trim($dependen->depen_desc).'-'.$dependen->depen_id}}</option>
                                            @else                                        
                                               <option value="{{$dependen->depen_id}}">{{trim($dependen->depen_desc).'-'.$dependen->depen_id}}</option>
                                            @endif
                                        @endforeach
                                    </select>    
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Resguardataria(o)</label>
                                    <input type="text" class="form-control" name="placa_obs2" id="placa_obs2" placeholder="Servidor público resguardataria(o)" value="{{Trim($regplaca->placa_obs2)}}" required>
                                </div>                                
                                <div class="col-xs-4 form-group">                        
                                    <label>Placa activa o Inactiva </label>
                                    <select class="form-control m-bot15" name="placa_status1" id="placa_status1" required>
                                        @if($regplaca->placa_status1 == 'S')
                                            <option value="S" selected>Activa  </option>
                                            <option value="N">         Inactiva</option>
                                        @else
                                            <option value="S">         Activa  </option>
                                            <option value="N" selected>Inactiva</option>
                                        @endif
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verPlacas')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\placaRequest','#actualizarPlaca') !!}
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