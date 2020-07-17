@extends('sicinar.principal')

@section('title','Editar Empleado')

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
                <small>Recursos Humanos - Empleados - editar</small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarEmpleado',$regempleado->emp_id], 'method' => 'PUT', 'id' => 'actualizarEmpleado', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$regempleado->folio}}">  
                                    <label >Id sistema <br>{{$regempleado->emp_id}} </label>
                                </div> 
                                <!--   
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de registro (dd/mm/aaaa) </label>
                                    <input type="text" class="form-control" name="emp_fecing" id="emp_fecing" placeholder="Fecha de registro (dd/mm/aaaa)" value="{{$regempleado->emp_fecing}}" required>
                                </div>  -->   
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="emp_ap" id="emp_ap" placeholder="Apellido paterno" value="{{Trim($regempleado->emp_ap)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="emp_am" id="emp_am" placeholder="Apellido materno" value="{{Trim($regempleado->emp_am)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="emp_nombres" id="emp_nombres" placeholder="Nombre(s)" value="{{Trim($regempleado->emp_nombres)}}" required>
                                </div>
                            </div>

                            <div class="row">    
                                <!--
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de nacimiento (dd/mm/aaaa) </label>
                                    <input type="text" class="form-control" name="emp_fecnac" id="emp_fecnac" placeholder="Fecha de nacimiento (dd/mm/aaaa)" value="{{$regempleado->emp_fecnac}}" required>
                                </div>   
                               -->
                                <div class="col-xs-3 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="emp_curp" id="emp_curp" placeholder="CURP" value="{{$regempleado->emp_curp}}" required>
                                </div>        
                                <div class="col-xs-2 form-group">                        
                                    <label>Sexo </label>
                                    <select class="form-control m-bot15" name="emp_sexo" id="emp_sexo" required>
                                        @if($regempleado->emp_sexo == 'H')
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
                                    <input type="text" class="form-control" name="emp_dom" id="emp_dom" value="{{trim($regempleado->emp_dom)}}" placeholder="Domicilio " required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="emp_col" id="emp_col" value="{{trim($regempleado->emp_col)}}" placeholder="Colonia" required>
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="emp_cp" id="emp_cp" placeholder="Código postal" value="{{$regempleado->emp_cp}}" required>
                                </div>                                                                                  
                            </div>

                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >Entidad de nacimiento</label>
                                    <select class="form-control m-bot15" name="entidadnac_id" id="entidadnac_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad de nacimiento</option>
                                        @foreach($regentidades as $estado)
                                            @if($estado->entidadfederativa_id == $regempleado->entidadnac_id)
                                                <option value="{{$estado->entidadfederativa_id}}" selected>{{$estado->entidadfederativa_desc}}</option>
                                            @else                                        
                                               <option value="{{$estado->entidadfederativa_id}}">{{$estado->entidadfederativa_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>               
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio_id" id="municipio_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            @if($municipio->municipioid == $regempleado->municipio_id)
                                                <option value="{{$municipio->municipioid}}" selected>{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}</option>
                                            @else 
                                               <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>                  
                            </div>

                            <div class="row">                                                                
                                <div class="col-xs-4 form-group">
                                    <label >Localidad </label>
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Localidad" value="{{Trim($regempleado->localidad)}}" required>
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label >Referencia del domicilio </label>
                                    <input type="text" class="form-control" name="emp_otraref" id="emp_otraref" placeholder="Referencia del domicilio" value="{{Trim($regempleado->emp_otraref)}}" required>
                                </div>                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-3 form-group">
                                    <label >Teléfono </label>
                                    <input type="number" min="0" max="9999999999" class="form-control" name="emp_tel" id="emp_tel" placeholder="Teléfono" value="{{$regempleado->emp_tel}}" required>
                                </div> 
                                <div class="col-xs-3 form-group">
                                    <label >Celular </label>
                                    <input type="number" min="0" max="9999999999" class="form-control" name="emp_cel" id="emp_cel" placeholder="Celular" value="{{$regempleado->emp_cel}}" required>
                                </div>    
                                <div class="col-xs-3 form-group">
                                    <label >email </label>
                                    <input type="email" class="form-control" name="emp_email" id="emp_email" placeholder="Correo electrónico" value="{{$regempleado->emp_email}}" required>
                                </div>                                                                                                                                 
                            </div>

                            <div class="row">           
                                <!--                                                     
                                <div class="col-xs-3 form-group">
                                    <label >Georeferenciación latitud </label>
                                    <input type="number" min="0" max="99999999.9999999999" class="form-control" name="emp_georeflatitud" id="emp_georeflatitud" placeholder="Georeferenciación latitud" value="{{$regempleado->emp_georeflatitud}}" required>
                                </div> 
                                <div class="col-xs-3 form-group">
                                    <label >Georeferenciación longitud </label>
                                    <input type="number" min="0" max="99999999.9999999999" class="form-control" name="emp_georeflongitud" id="emp_georeflongitud" placeholder="Georeferenciación longitud" value="{{$regempleado->emp_georeflongitud}}" required>
                                </div>                                                                  
                                -->
                                <div class="col-xs-4 form-group">                        
                                    <label>Activo o Inactivo </label>
                                    <select class="form-control m-bot15" id="emp_status1" name="emp_status1" required>
                                        @if($regempleado->emp_status1 == 'S')
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
                                    <a href="{{route('verEmpleados')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\empleadoRequest','#actualizarEmpleado') !!}
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

