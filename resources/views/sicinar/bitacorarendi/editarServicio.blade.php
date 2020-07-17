@extends('sicinar.principal')

@section('title','Editar servicio')

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
                <small>Formatos de comprobación - Bitacora de rendimiento - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarServicio', $regbitaservi->periodo_id,$regbitaservi->bitaco_folio,$regbitaservi->servicio], 'method' => 'PUT', 'id' => 'actualizarServicio', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regbitarendi as $bitarendi)                                               
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_id" name="placa_id" value="{{$bitarendi->placa_id}}">  
                                    <label>Código : </label><b>{{$bitarendi->placa_id}}</b>
                                </td>
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_placa" name="placa_placa" value="{{$bitarendi->placa_placa}}">  
                                    <label>Placas : </label><b>{{$bitarendi->placa_placa}} </b>
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$bitarendi->periodo_id}}">  
                                    <label>Periodo fiscal : </label>{{$bitarendi->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="mes_id" name="mes_id" value="{{$bitarendi->mes_id}}">  
                                    <label>Mes : </label><b>
                                    @foreach($regmes as $mes)
                                        @if($mes->mes_id == $bitarendi->mes_id)
                                            {{$mes->mes_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>
                                </td>                                
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="quincena_id" name="quincena_id" value="{{$bitarendi->quincena_id}}">  
                                    <label>Quincena : </label>
                                    @foreach($regquincena as $quincena)
                                        @if($quincena->quincena_id == $bitarendi->quincena_id)
                                            {{$quincena->quincena_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                     
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="bitaco_folio" name="bitaco_folio" value="{{$bitarendi->bitaco_folio}}">  
                                    <label>Folio : </label>{{$bitarendi->bitaco_folio}}
                                </td>                                                                
                            </tr>      
                            @endforeach     
                            </table>


                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Fecha del servicio - año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año </option>
                                        @foreach($regperiodo as $peri)
                                            @if($peri->periodo_id == $regbitaservi->periodo_id1)
                                                <option value="{{$peri->periodo_id}}" selected>{{$peri->periodo_desc}}</option>
                                            @else                                        
                                                <option value="{{$peri->periodo_id}}">{{$peri->periodo_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label >Mes  </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmes as $mesi)
                                            @if($mesi->mes_id == $regbitaservi->mes_id1)
                                                <option value="{{$mesi->mes_id}}" selected>{{$mesi->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mesi->mes_id}}">{{$mesi->mes_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-3 form-group">
                                    <label >Día  </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día </option>
                                        @foreach($regdia as $diai)
                                            @if($diai->dia_id == $regbitaservi->dia_id1)
                                                <option value="{{$diai->dia_id}}" selected>{{$diai->dia_desc}}</option>
                                            @else                                        
                                               <option value="{{$diai->dia_id}}">{{$diai->dia_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>

                            <div class="row">   
                                <div class="col-xs-3 form-group">
                                    <input type="hidden" id="partida_id" name="partida_id" value="{{$regbitaservi->servicio}}">               
                                    <label >Servidora o servidor público (apellido paterno, materno, nombre)</label>
                                    <input type="text" class="form-control" name="sp_nomb" id="sp_nomb" placeholder="Servidora o servidor público (apellido paterno, materno, nombre)" value="{{$regbitaservi->sp_nomb}}" onkeypress="return soloAlfa(event)" required>
                                </div>                                                           
                                <div class="col-xs-3 form-group">
                                    <label >Dotación de combustible en pesos mexicanos $ </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="servicio_dotacion" id="servicio_dotacion" placeholder="Dotación de combustible en pesos mexicanos $" value="{{$regbitaservi->servicio_dotacion}}"  required>
                                </div>  
                            </div>

                            <div class="row">
                                <div class="col-xs-3 form-group">
                                    <label >Nivel de combustible </label>
                                </div>
                                <div class="col-xs-4 form-group">
                                    R  <input type="checkbox" name="servicio_r" id="servicio_r" value="1" placeholder="Nivel de combustible R" 
                                    @if(old('servicio_r',$regbitaservi->servicio_ir)=="1") checked @endif required>
                                    1/8<input type="checkbox" name="servicio_18" id="servicio_18" value="1" placeholder="Nivel de combustible 1/8" 
                                    @if(old('servicio_18',$regbitaservi->servicio_18)=="1") checked @endif required>
                                    1/4<input type="checkbox" name="servicio_14" id="servicio_14" value="1" placeholder="Nivel de combustible 1/4" 
                                    @if(old('servicio_14',$regbitaservi->servicio_14)=="1") checked @endif required>
                                    1/2<input type="checkbox" name="servicio_12" id="servicio_12" value="1" placeholder="Nivel de combustible 1/2" 
                                    @if(old('servicio_12',$regbitaservi->servicio_12)=="1") checked @endif required>
                                    3/4<input type="checkbox" name="servicio_34" id="servicio_34" value="1" placeholder="Nivel de combustible 3/4" 
                                    @if(old('servicio_34',$regbitaservi->servicio_34)=="1") checked @endif required>
                                    F  <input type="checkbox" name="servicio_f" id="servicio_f" value="1" placeholder="Nivel de combustible F" 
                                    @if(old('servicio_f',$regbitaservi->servicio_f)=="1") checked @endif required>
                                </div>                                
                            </div>

                            <div class="row">                                                            
                                <div class="col-xs-3 form-group">
                                    <label >Km. inicial </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="km_inicial" id="km_inicial" placeholder="Km. inicial" value="{{$regbitaservi->km_inicial}}" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Km. final </label>
                                    <input type="number" min="0" max="999999999999" class="form-control" name="km_final" id="km_final" placeholder="Km. final" value="{{$regbitaservi->km_final}}" required>
                                </div>                                                                      
                            </div>                            

                            <div class="row">                                                                 
                                <div class="col-xs-8 form-group">
                                    <label >Lugar de la comisión </label>
                                    <input type="text" class="form-control" name="servicio_lugar" id="servicio_lugar" placeholder="Lugar de la comisión" value="{{$regbitaservi->servicio_lugar}}" required>
                                </div>
                            </div>
                            <div class="row">                                             
                                <div class="col-xs-3 form-group">
                                    <label >Hr. de salida al lugar de la comisión (hh:mm)</label>
                                    <input type="text" class="form-control" name="servicio_hrsalida" id="servicio_hrsalida" placeholder="Hr. de salida al lugar de la comisión (hh:mm)" value="{{$regbitaservi->servicio_hrsalida}}" required>
                                </div>  
                                <div class="col-xs-3 form-group">
                                    <label >Hr. de regreso del lugar de la comisión (hh:mm) </label>
                                    <input type="text" class="form-control" name="servicio_hrregreso" id="servicio_hrregreso" placeholder="Hr. de regreso del lugar de la comisión (hh:mm)" value="{{$regbitaservi->servicio_hrregreso}}" required>
                                </div>                                                                
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones (4,000 carácteres)</label>
                                    <textarea class="form-control" name="servicio_obs" id="servicio_obs" rows="3" cols="120" placeholder="Observaciones (4,000 carácteres)" required>{{Trim($regbitaservi->servicio_obs)}}
                                    </textarea>
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
            
                                    @foreach($regbitarendi as $bitarendi)  
                                       <a href="{{route('verServicios',array($bitarendi->periodo_id,$bitarendi->bitaco_folio))}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
                                       </a>
                                       @break
                                    @endforeach
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
4
@section('request')
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/2.1.3/jquery.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/twitter-bootstrap/3.3.1/js/bootstrap.min.js"></script>
    <script type="text/javascript" src="{{ asset('vendor/jsvalidation/js/jsvalidation.js')}}"></script>
    {!! JsValidator::formRequest('App\Http\Requests\bitaservicioRequest','#actualizarServicio') !!}
@endsection

@section('javascrpt')
    <script type="text/javascript">
            // Firefox, Google Chrome, Opera, Safari, Internet Explorer from version 9
        // se llama oninput="OnInput(event)" onpropertychange="OnPropChanged(event)"
        //function OnInput (event) {
        //    alert ("The new content: " + event.target.value);
        //}
            // Internet Explorer
        function OnPropChanged (event) {
            if (event.propertyName.toLowerCase () == "value") {
                alert ("The new content: " + event.srcElement.value);
            }
        }
        
    
    function OnInput(event){
        //var input=  document.getElementById('numero');
        //input.addEventListener('input',function(){
        var input= document.getElementById('km_inicial');
        const v1 = document.getElementById('km_inicial');
        const v2 = document.getElementById('km_final');
        //input.addEventListener('input',function(){
        //alert ("The new content: " + event.target.value);
        alert ("The new content: " + this.value.v1);
        if (this.value.v1 < event.target.value) {
            alert ("Es menor: " + event.target.value);
            return true; 
        }
        else{
            alert('Km final debe ser mayor que el Km inicial "' + this.value + '"!');
            $("input#km_final").focus();
            return false; 
        }
    }
    </script>

<script>
    function soloNumeros(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "1234567890";
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
    </script>
@endsection
