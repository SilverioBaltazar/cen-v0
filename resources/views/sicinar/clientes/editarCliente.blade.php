@extends('sicinar.principal')

@section('title','Editar Cliente')

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
                <small> Ventas - Cartera de clientes - editar</small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarCliente',$regcliente->cliente_id], 'method' => 'PUT', 'id' => 'actualizarCliente', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}
                        <div class="box-body">
                            <div class="row">    
                                <div class="col-xs-3 form-group">
                                    <input type="hidden" id="folio" name="folio" value="{{$regcliente->folio}}">  
                                    <label >Id sistema <br>{{$regcliente->cliente_id}} </label>
                                </div> 
                                <div class="col-xs-2 form-group">
                                    <label >Folio </label>
                                    <input type="number" min="0" max="9999999999" class="form-control" name="cliente_folio" id="cliente_folio" placeholder="Folio de solicitud" value="{{$regcliente->cliente_folio}}" required>
                                </div>                                                                                                
                                <!--   
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de registro (dd/mm/aaaa) </label>
                                    <input type="text" class="form-control" name="cliente_fecing" id="cliente_fecing" placeholder="Fecha de registro (dd/mm/aaaa)" value="{{$regcliente->cliente_fecing}}" required>
                                </div>  -->   
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Apellido paterno </label>
                                    <input type="text" class="form-control" name="cliente_ap" id="cliente_ap" placeholder="Apellido paterno" value="{{Trim($regcliente->cliente_ap)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Apellido materno </label>
                                    <input type="text" class="form-control" name="cliente_am" id="cliente_am" placeholder="Apellido materno" value="{{Trim($regcliente->cliente_am)}}" required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Nombre(s) </label>
                                    <input type="text" class="form-control" name="cliente_nombres" id="cliente_nombres" placeholder="Nombre(s)" value="{{Trim($regcliente->cliente_nombres)}}" required>
                                </div>
                            </div>

                            <div class="row">    
                                <!--
                                <div class="col-xs-3 form-group">
                                    <label >Fecha de nacimiento (dd/mm/aaaa) </label>
                                    <input type="text" class="form-control" name="cliente_fecnac" id="cliente_fecnac" placeholder="Fecha de nacimiento (dd/mm/aaaa)" value="{{$regcliente->cliente_fecnac}}" required>
                                </div>   
                               -->
                                <div class="col-xs-3 form-group">
                                    <label >CURP </label>
                                    <input type="text" class="form-control" name="cliente_curp" id="cliente_curp" placeholder="CURP" value="{{$regcliente->cliente_curp}}" required>
                                </div>        
                                <div class="col-xs-2 form-group">                        
                                    <label>Sexo </label>
                                    <select class="form-control m-bot15" name="cliente_sexo" id="cliente_sexo" required>
                                        @if($regcliente->cliente_sexo == 'H')
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
                                    <input type="text" class="form-control" name="cliente_dom" id="cliente_dom" value="{{trim($regcliente->cliente_dom)}}" placeholder="Domicilio " required>
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Colonia)</label>
                                    <input type="text" class="form-control" name="cliente_col" id="cliente_col" value="{{trim($regcliente->cliente_col)}}" placeholder="Colonia" required>
                                </div>
                                <div class="col-xs-2 form-group">
                                    <label >Código postal </label>
                                    <input type="number" min="0" max="99999" class="form-control" name="cliente_cp" id="cliente_cp" placeholder="Código postal" value="{{$regcliente->cliente_cp}}" required>
                                </div>                                                                                  
                            </div>

                            <div class="row">        
                                <div class="col-xs-4 form-group">
                                    <label >Entidad de nacimiento</label>
                                    <select class="form-control m-bot15" name="entidadnac_id" id="entidadnac_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar entidad de nacimiento</option>
                                        @foreach($regentidades as $estado)
                                            @if($estado->entidadfederativa_id == $regcliente->entidadnac_id)
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
                                            @if($municipio->municipioid == $regcliente->municipio_id)
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
                                    <input type="text" class="form-control" name="localidad" id="localidad" placeholder="Localidad" value="{{Trim($regcliente->localidad)}}" required>
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label >Referencia del domicilio </label>
                                    <input type="text" class="form-control" name="cliente_otraref" id="cliente_otraref" placeholder="Referencia del domicilio" value="{{Trim($regcliente->cliente_otraref)}}" required>
                                </div>                                  
                            </div>

                            <div class="row">                                
                                <div class="col-xs-3 form-group">
                                    <label >Teléfono </label>
                                    <input type="number" min="0" max="9999999999" class="form-control" name="cliente_tel" id="cliente_tel" placeholder="Teléfono" value="{{$regcliente->cliente_tel}}" required>
                                </div> 
                                <div class="col-xs-3 form-group">
                                    <label >Celular </label>
                                    <input type="number" min="0" max="9999999999" class="form-control" name="cliente_cel" id="cliente_cel" placeholder="Celular" value="{{$regcliente->cliente_cel}}" required>
                                </div>    
                                <div class="col-xs-3 form-group">
                                    <label >email </label>
                                    <input type="email" class="form-control" name="cliente_email" id="cliente_email" placeholder="Correo electrónico" value="{{$regcliente->cliente_email}}" required>
                                </div>                                                                                                                                 
                            </div>

                            <div class="row">           
                                
                                <div class="col-xs-3 form-group">
                                    <label >Georeferenciación latitud </label>
                                    <input type="number" min="0" max="99999999.9999999" class="form-control" name="cliente_georeflatitud" id="cliente_georeflatitud" placeholder="Georeferenciación latitud" value="{{$regcliente->cliente_georeflatitud}}" required>
                                </div> 
                                <div class="col-xs-3 form-group">
                                    <label >Georeferenciación longitud </label>
                                    <input type="number" min="0" max="99999999.9999999" class="form-control" name="cliente_georeflongitud" id="cliente_georeflongitud" placeholder="Georeferenciación longitud" value="{{$regcliente->cliente_georeflongitud}}" required>
                                </div>                                                                  
                                
                                <div class="col-xs-4 form-group">                        
                                    <label>Activo o Inactivo </label>
                                    <select class="form-control m-bot15" id="cliente_status1" name="cliente_status1" required>
                                        @if($regcliente->cliente_status1 == 'S')
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
                                    <a href="{{route('verClientes')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\clienteRequest','#actualizarCliente') !!}
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

