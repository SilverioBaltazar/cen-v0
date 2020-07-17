@extends('sicinar.principal')

@section('title','Nueva Documento')

@section('links')
    <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
@endsection

@section('nombre')
    {{$nombre}}
@endsection

@section('usuario')
    {{$usuario}}
@endsection

@section('estructura')
    {{$estructura}}
@endsection

@section('content')
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Menú
                <small> Documentos - Nuevo </small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header"><h3 class="box-title">Registrar nuevo documento</h3></div>
                        {!! Form::open(['route' => 'AltaNuevoDocto', 'method' => 'POST','id' => 'nuevoDocto', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Nombre del documento </label>
                                    <input type="text" class="form-control" name="doc_desc" id="doc_desc" placeholder="Digitar nombre del documento" onkeypress="return soloAlfa(event)" required>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Area o unidad admon. que solicita documento </label>
                                    <select class="form-control m-bot15" name="dependencia_id" id="dependencia_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar area o unidad admon.</option>
                                        @foreach($dep as $depen)
                                            <option value="{{$depen->depen_id}}">{{$depen->depen_id.' '.$depen->depen_desc}}</option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                                     
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Formato del documento </label>
                                    <select class="form-control m-bot15" name="formato_id" id="formato_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar rubro asistencial</option>
                                        @foreach($regformato as $formato)
                                            <option value="{{$formato->formato_id}}">{{$formato->formato_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                                     
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Periodo o frecuencia de entrega del formato</label>
                                    <select class="form-control m-bot15" name="per_id" id="per_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar periodo o frecuencia de entrega del formato</option>
                                        @foreach($regper as $per)
                                            <option value="{{$per->per_id}}">{{$per->per_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                  
                                </div>                                                                    
                            </div>           
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Rubro asistencial al que aplica</label>
                                    <select class="form-control m-bot15" name="rubro_id" id="rubro_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar rubro asistencial</option>
                                        @foreach($regrubro as $rubro)
                                            <option value="{{$rubro->rubro_id}}">{{$rubro->rubro_id}} - {{$rubro->rubro_desc}}
                                            </option>
                                        @endforeach
                                    </select>                                    
                                </div>                                                                                     
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label >Observaciones </label>
                                    <textarea class="form-control" name="doc_obs" id="doc_obs" rows="6" cols="120" placeholder="Observaciones" required>
                                    </textarea>
                                </div>                                
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Archivo del documento </label>
                                    <input type="file" class="text-md-center" style="color:red" name="doc_file" id="doc_file" placeholder="Subir archivo del documento " >
                                </div>                                                          


                            <div class="row">
                                <div class="col-md-12 offset-md-5">
                                    {!! Form::submit('Dar de alta',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verDoctos')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\catdoctoRequest','#nuevoDocto') !!}
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