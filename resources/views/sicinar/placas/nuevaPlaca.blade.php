@extends('sicinar.principal')

@section('title','Nueva Placa')

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
            <h1>Menú
                <small> Placas - Nueva</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'AltaNuevaPlaca', 'method' => 'POST','id' => 'nuevaPlaca', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Número de placas actual</label>
                                    <input type="text" class="form-control" name="placa_placa" id="placa_placa" placeholder="Digitar número de placas actual" required>
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Número de placas anterior </label>
                                    <input type="text" class="form-control" name="placa_anterior" id="placa_anterior" placeholder="Número de placas anterior" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-8 form-group">
                                    <label >Descripción de la unidad </label>
                                    <input type="text" class="form-control" name="placa_desc" id="placa_desc" placeholder="Descripción de la unidad" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-2 form-group">
                                    <label >Número de cilindros </label>
                                    <input type="text" class="form-control" name="placa_cilindros" id="placa_cilindros" placeholder="Número de cilindros" required>
                                </div>                                                                  
                                <div class="col-xs-2 form-group">
                                    <label >Número de serie del motor </label>
                                    <input type="text" class="form-control" name="placa_serie" id="placa_serie" placeholder="Número de serie del motor" required>
                                </div>                                  
                                <div class="col-xs-4 form-group">
                                    <label >Marca  </label>
                                    <select class="form-control m-bot15" name="marca_id" id="marca_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar marca </option>
                                        @foreach($regmarca as $marca)
                                            <option value="{{$marca->marca_id}}">{{$marca->marca_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div> 
                            </div>                            

                            <div class="row">                                
                                <div class="col-xs-2 form-group">
                                    <label >Modelo </label>
                                    <input type="text" class="form-control" name="placa_modelo2" id="placa_modelo2" placeholder="Modelo de la unidad" required>
                                </div>     
                                <div class="col-xs-2 form-group">
                                    <label >No. de inventario </label>
                                    <input type="text" class="form-control" name="placa_inventario" id="placa_inventario" placeholder="No. de inventario" required>
                                </div>                                
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de gasolina (Premium, Magna o Diesel) </label>
                                    <input type="text" class="form-control" name="placa_gasolina" id="placa_gasolina"  placeholder="Tipo de gasolina: Premium, Magna o Diesel" required>
                                </div>
                            </div>       

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de gasto presupuestal </label>
                                    <select class="form-control m-bot15" name="tipog_id" id="tipog_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de gasto presupuestal</option>
                                        @foreach($regtipogasto as $gasto)
                                            <option value="{{$gasto->tipog_id}}">{{$gasto->tipog_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de operación admon. </label>
                                    <select class="form-control m-bot15" name="tipoo_id" id="tipoo_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de operación admon.</option>
                                        @foreach($regtipooper as $oper)
                                            <option value="{{$oper->tipoo_id}}">{{$oper->tipoo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                          
                            </div>

                            <div class="row">
                                <div class="col-xs-6 form-group">
                                    <label >Unidad adminstrativa a la que es asignada la unidad </label>
                                    <select class="form-control m-bot15" name="dependencia_id" id="dependencia_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Unidad admon. a la que es asignada la unidad </option>
                                        @foreach($regdepen as $dependen)
                                               <option value="{{$dependen->depen_id}}">{{trim($dependen->depen_desc).'-'.$dependen->depen_id}}</option>
                                        @endforeach
                                    </select>    
                                </div>                                
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Resguadataria(o) </label>
                                    <input type="text" class="form-control" name="placa_obs2" id="placa_obs2" placeholder="Servidor público resguadataria(o)" required>
                                </div>                                                                      
                                <div class="col-xs-6 form-group">
                                    <label >Fotografía de la unidad </label>
                                    <input type="file" class="text-md-center" style="color:red" name="placa_foto1" id="placa_foto1" placeholder="Subir archivo de fotografía" >
                                </div>                                                          
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
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
    {!! JsValidator::formRequest('App\Http\Requests\placaRequest','#nuevaPlaca') !!}
@endsection

@section('javascrpt')
<script>
  function soloAlfa(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ.";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }

    function general(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ1234567890,.;:-_<>!%()=?¡¿/*+";
       especiales = "8-37-39-46";

       tecla_especial = false
       for(var i in especiales){
            if(key == especiales[i]){
                tecla_especial = true;
                break;
            }
        }
        if(letras.indexOf(tecla)==-1 && !tecla_especial){
            return false;
        }
    }
</script>

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