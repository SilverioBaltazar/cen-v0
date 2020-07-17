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
                <small> Recursos Humanos - Empleados - editar</small>           
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarEmpleado1',$regempleado->emp_id], 'method' => 'PUT', 'id' => 'actualizarEmpleado1', 'enctype' => 'multipart/form-data']) !!}
                        {{ csrf_field() }}
                        <div class="box-body">
                              

                            <table id="tabla1" class="table table-hover table-striped">              
                                <tr>                            
                                    <td style="text-align:left; vertical-align: middle;color:green;"> 
                                        <label >Fecha de ingreso:  {{date("d/m/Y", strtotime($regempleado->emp_fecing2))}}</label>
                                    </td>
                                    <td style="text-align:center; vertical-align: middle;color:green;"> 
                                        <label >empleado:  {{$regempleado->emp_id.' '.$regempleado->emp_nombrecompleto}} </label>
                                    </td>
                                    <td style="text-align:right; vertical-align: middle;color:green;"> 
                                        <label>Id: {{$regempleado->emp_id}} </label>
                                    </td>
                                </tr>             
                            </table>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales en formato PDF, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>

                            <div class="row">    
                                @if(!empty(trim($regempleado->emp_foto1))&&(!is_null($regempleado->emp_foto1)))
                                    <div class="col-xs-4 form-group">
                                        <label >Forma de solicitud del empleado en formato PDF</label><br>
                                        <label ><a href="/images/{{$regempleado->emp_foto1}}" class="btn btn-danger" title="Forma de solicitud del empleado en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital de Forma de solicitud del empleado en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="emp_foto1" id="emp_foto1" placeholder="Subir archivo digital de Forma de solicitud del empleado en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo digital de Forma de solicitud del empleado en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="emp_foto1" id="emp_foto1" placeholder="Subir archivo digital de Forma de solicitud del empleado en formato PDF" >
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
    {!! JsValidator::formRequest('App\Http\Requests\empleado1Request','#actualizarEmpleado1') !!}
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

