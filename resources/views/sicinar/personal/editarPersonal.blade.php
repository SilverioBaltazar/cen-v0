@extends('sicinar.principal')

@section('title','Editar datos del personal')

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
    <meta http-equiv="Content-type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small> Requisitos asistenciales</small>                
                <small> Plantilla - editar datos del personal</small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">

                        {!! Form::open(['route' => ['actualizarPersonal',$regpersonal->folio], 'method' => 'PUT', 'id' => 'actualizarPersonal', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >IAP </label><br>
                                    <td style="text-align:left; vertical-align: middle;">   
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $regpersonal->iap_id)
                                                {{$iap->iap_desc}}
                                                @break
                                            @endif
                                        @endforeach
                                    </td>                                     
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$regpersonal->folio}}">  
                                    <label >Folio asignado <br>{{$regpersonal->folio}} </label> 
                                </div>    
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de ingreso - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id1" id="periodo_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de ingreso </option>
                                        @foreach($regperiodos as $periodo)
                                            @if($periodo->periodo_id == $regpersonal->periodo_id1)
                                                <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_desc}}</option>
                                            @else                                        
                                               <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id1" id="mes_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de ingreso </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regpersonal->mes_id1)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id1" id="dia_id1" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de ingreso </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regpersonal->dia_id1)
                                                <option value="{{$dia->dia_id}}" selected>{{$dia->dia_desc}}</option>
                                            @else                                        
                                               <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="primer_apellido" id="primer_apellido" placeholder="Apellido paterno" value="{{$regpersonal->primer_apellido}}" onkeypress="return soloLetras(event)" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="segundo_apellido" id="segundo_apellido" placeholder="Apellido materno" value="{{$regpersonal->segundo_apellido}}" onkeypress="return soloLetras(event)" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="nombres" id="nombres" placeholder="Nombre(s)" value="{{$regpersonal->nombres}}" onkeypress="return soloLetras(event)" required>
                                </div>
                            </div>

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de nacimiento - Año </label>
                                    <select class="form-control m-bot15" name="periodo_id2" id="periodo_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar año de nacimiento </option>
                                        @foreach($regperiodos as $periodo)
                                            @if($periodo->periodo_id == $regpersonal->periodo_id2)
                                                <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_desc}}</option>
                                            @else                                        
                                               <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes_id2" id="mes_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes de nacimiento </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regpersonal->mes_id2)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>    
                                <div class="col-xs-4 form-group">
                                    <label >Día </label>
                                    <select class="form-control m-bot15" name="dia_id2" id="dia_id2" required>
                                        <option selected="true" disabled="disabled">Seleccionar día de nacimiento </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regpersonal->dia_id2)
                                                <option value="{{$dia->dia_id}}" selected>{{$dia->dia_desc}}</option>
                                            @else                                        
                                               <option value="{{$dia->dia_id}}">{{$dia->dia_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                    
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="curp" id="curp" placeholder="CURP" value="{{$regpersonal->curp}}" onkeypress="return soloAlfaSE(event)" required>
                                </div>        
                                <div class="col-xs-4 form-group">                        
                                    <label>Sexo </label>
                                    <select class="form-control m-bot15" name="sexo" id="sexo" required>
                                        @if($regpersonal->sexo == 'H')
                                            <option value="H" selected>Hombre </option>
                                            <option value="M">         Mujer  </option>
                                        @else
                                            <option value="H">         Hombre </option>
                                            <option value="M" selected>Mujer  </option>
                                        @endif
                                    </select>
                                </div>                                   
                            </div>                                

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio (Calle, no.ext/int.) </label>
                                    <input type="text" class="form-control" name="domicilio" id="domicilio" value="{{trim($regpersonal->domicilio)}}" placeholder="Domicilio donde vive" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="colonia" id="colonia" value="{{trim($regpersonal->colonia)}}" placeholder="Colonia" required>
                                </div>                           
                                <div class="col-xs-4 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="cp" id="cp" placeholder="Código postal" value="{{$regpersonal->cp}}" required>
                                </div>                                               
                            </div>

                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >Entidad federativa</label>
                                    <select class="form-control m-bot15" name="entidad_fed_id" id="entidad_fed_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad federativa</option>
                                        @foreach($regentidades as $estado)
                                            @if($estado->entidadfederativa_id == $regpersonal->entidad_fed_id)
                                                <option value="{{$estado->entidadfederativa_id}}" selected>{{$estado->entidadfederativa_desc}}</option>
                                            @else                                        
                                               <option value="{{$estado->entidadfederativa_id}}">{{$estado->entidadfederativa_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                                                                      
                                <div class="col-xs-4 form-group">
                                    <label >Municipio </label>
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Localidad" value="{{Trim($regpersonal->localidad)}}" required>
                                </div>                                                                     
                                <!--
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            @if($municipio->municipioid == $regpersonal->municipio_id)
                                                <option value="{{$municipio->municipioid}}" selected>{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}</option>
                                            @else 
                                               <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                -->                                    
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Teléfono </label>
                                    <input type="text" class="form-control" name="telefono" id="telefono" placeholder="Teléfono" value="{{$regpersonal->telefono}}" required>
                                </div>                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >Tipo de empleado</label>
                                    <select class="form-control m-bot15" name="tipoemp_id" id="tipoemp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar tipo de empleado</option>
                                        @foreach($regtipoemp as $tipo)
                                            @if($tipo->tipoemp_id == $regpersonal->tipoemp_id)
                                                <option value="{{$tipo->tipoemp_id}}" selected>{{$tipo->tipoemp_desc}}
                                                </option>
                                            @else 
                                               <option value="{{$tipo->tipoemp_id}}">{{$tipo->tipoemp_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Clase de empleado</label>
                                    <select class="form-control m-bot15" name="claseemp_id" id="claseemp_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar clase de empleado</option>
                                        @foreach($regclaseemp as $clase)
                                            @if($clase->claseemp_id == $regpersonal->claseemp_id)
                                                <option value="{{$clase->claseemp_id}}" selected>{{$clase->claseemp_desc}}
                                                </option>
                                            @else 
                                               <option value="{{$clase->claseemp_id}}">{{$clase->claseemp_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                                   
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Grado de estudios</label>
                                    <select class="form-control m-bot15" name="grado_estudios_id" id="grado_estudios_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar grado de estudios</option>
                                        @foreach($regestudios as $estudios)
                                            @if($estudios->grado_estudios_id == $regpersonal->grado_estudios_id)
                                                <option value="{{$estudios->grado_estudios_id}}" selected>{{$estudios->grado_estudios_desc}}
                                                </option>
                                            @else 
                                               <option value="{{$estudios->grado_estudios_id}}">{{$estudios->grado_estudios_desc}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Sueldo mensual </label>
                                    <input type="number" min="0" max="999999999999.99" class="form-control" name="sueldo_mensual" id="sueldo_mensual" placeholder="Cuota de recuperación" value="{{$regpersonal->sueldo_mensual}}" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Puesto  </label>
                                    <input type="text" class="form-control" name="puesto" id="puesto" placeholder="Puesto" value="{{$regpersonal->puesto}}" required>
                                </div>                                               
                            </div>               
                                            
                            <div class="row">                                             
                                <div class="col-xs-4 form-group">                        
                                    <label>Personal Activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="status_1" id="status_1" required>
                                        @if($regpersonal->status_1 == 'S')
                                            <option value="S" selected>Activo  </option>
                                            <option value="N">         Inactivo</option>
                                        @else
                                            <option value="S">         Activo  </option>
                                            <option value="N" selected>Inactivo</option>
                                        @endif
                                    </select>
                                </div>                                                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Descripción de actividades (300 carácteres)</label>
                                    <textarea class="form-control" name="obs_1" id="obs_1" rows="3" cols="120" placeholder="Descripción de actividades (300 carácteres)" required>{{Trim($regpersonal->obs_1)}}
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
    {!! JsValidator::formRequest('App\Http\Requests\personalRequest','#actualizarPersonal') !!}
@endsection

@section('javascrpt')
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

