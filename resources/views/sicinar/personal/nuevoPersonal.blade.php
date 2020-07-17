@extends('sicinar.principal')

@section('title','Nuevo personal')

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
            <h1>Menú
                <small> Requisitos jurídicos    </small>                
                <small> Padrón - Nuevo personal </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        
                        {!! Form::open(['route' => 'AltaNuevoPersonal', 'method' => 'POST','id' => 'nuevoPersonal', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >IAP  </label>
                                    <select class="form-control m-bot15" name="iap_id" id="iap_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar IAP </option>
                                        @foreach($regiap as $iap)
                                            <option value="{{$iap->iap_id}}">{{$iap->iap_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                  
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de ingreso - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de ingreso </option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de ingreso </option>
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de ingreso </option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" placeholder="Apellido paterno" onkeypress="return soloLetras(event)" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" placeholder="Apellido materno" onkeypress="return soloAlfa(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombre(s)" onkeypress="return soloLetras(event)" required>
                                </div>
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de nacimiento - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de nacimiento </option>
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de nacimiento </option>
                                        @foreach($regmeses as $mes)                                      
                                            <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de nacimiento </option>
                                        @foreach($regdias as $dia)
                                            <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                        @endforeach
                                    </select>       
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP" onkeypress="return soloAlfaSE(event)" required>
                                </div>        
                                <div class="col-md-4 offset-md-0"><p>
                                    <label >Sexo </label><br>
                                    <input type="radio" name="sexo" checked value="H" style="margin-right:5px;">Hombre
                                    <input type="radio" name="sexo"         value="M" style="margin-right:5px;">Mujer</p>
                                </div>                          
                            </div>                                
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio (Calle, no.ext/int.) </label>
                                    <input type="text" class="form-control" name="domicilio" id="domicilio" placeholder="Domicilio donde vive" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="colonia" id="colonia" placeholder="Colonia" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="cp" id="cp" placeholder="Código postal" required>
                                </div>                                                                      
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Entidad federativa donde vive</label>
                                    <select class="form-control m-bot15" name="entidad_fed_id" id="entidad_fed_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad federativa</option>
                                        @foreach($regentidades as $estado)
                                            <option value="{{$estado->entidadfederativa_id}}">{{$estado->entidadfederativa_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Municipio </label>
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Municipio" required>
                                </div>                                      
                                
                                <!---
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>                                                                    
                                
                                <div class="col-md-4 offset-md-0">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled" value="" selected>Seleccionar municipio</option>                                   
                                    </select>
                                </div>  
                               -->
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" required>
                                </div>                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de empleado </label>
                                    <select class="form-control m-bot15" name="tipoemp_id" id="tipoemp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de empleado</option>
                                        @foreach($regtipoemp as $tipo)
                                            <option value="{{$tipo->tipoemp_id}}">{{$tipo->tipoemp_desc}}</option>
                                        @endforeach
                                    </select>                                  
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label >Clase de empleado </label>
                                    <select class="form-control m-bot15" name="claseemp_id" id="claseemp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar clase de empleado</option>
                                        @foreach($regclaseemp as $clase)
                                            <option value="{{$clase->claseemp_id}}">{{$clase->claseemp_desc}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>                                   
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Grado de estudios </label>
                                    <select class="form-control m-bot15" name="grado_estudios_id" id="grado_estudios_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar grado de estudios</option>
                                        @foreach($regestudios as $estudios)
                                            <option value="{{$estudios->grado_estudios_id}}">{{$estudios->grado_estudios_desc}}</option>
                                        @endforeach
                                    </select>                                  
                                </div>                                 
                                <div class="col-xs-4 form-group">
                                    <label >Sueldo mensual </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="sueldo_mensual" id="sueldo_mensual" placeholder="Sueldo mensual" required>
                                </div>                                
                                <div class="col-xs-4 form-group">
                                    <label >Puesto </label>
                                    <input type="text" class="form-control" name="puesto" id="puesto" placeholder="Puesto" required>
                                </div>                                
                            </div>
                            
                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Actividades (300 carácteres)</label>
                                    <textarea class="form-control" name="obs_1" id="obs_1" rows="3" cols="120" placeholder="Actividades (300 carácteres)" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verPersonal')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\personalRequest','#nuevoPersonal') !!}
@endsection

@section('javascript')
<script language="javascript">
        $(document).ready(function(){
            $("#entidad_fed_id").on('change', function(){
                var entidadfed = $(this).val();
                alert(entidadfed);
                if(entidadfed) {
                    $.ajax({
                        url: '/siinap-v2/public/control-interno/municipios/'+entidadfed,
                        type: "GET",
                        dataType: "json",
                        success:function(data){
                            $("#municipio_id").empty();
                            //var aux = data;
                            //alert(data.length);
                            var html_select = '<option value="">Seleccionar municipio....</option>';
                            for (var i=0; i<data.length ;++i)
                                html_select += '<option value="'+data[i].municipioid+'">'+data[i].municipionombre+'</option>';
                            $('#municipio_id').html(html_select);
                        }
                    });
                }else{
                    var html_select = '<option value="">Seleccionar municipio..............</option>';
                    $("#municipio_id").html(html_select);
                }
            });

        });
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

    function soloLetras(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyz ABCDEFGHIJKLMNÑOPQRSTUVWXYZ";
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
    function soloAlfaSE(e){
       key = e.keyCode || e.which;
       tecla = String.fromCharCode(key);
       letras = "abcdefghijklmnñopqrstuvwxyzABCDEFGHIJKLMNÑOPQRSTUVWXYZ0123456789";
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