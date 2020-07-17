@extends('sicinar.principal')

@section('title','Editar documento')

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
            <h1>
                Menú
                <small> Catálogos - Documentos - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar documento</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarDocto',$regdocto->doc_id], 'method' => 'PUT', 'id' => 'actualizarDocto', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <div class="row">
                                <div class="col-md-12 offset-md-12">
                                    <label>Id.: {{$regdocto->doc_id}}</label>
                                </div>             
                                <div class="col-xs-4 form-group">
                                    <label >Documento </label>
                                    <input type="text" class="form-control" name="doc_desc" placeholder="Documento" value="{{$regdocto->doc_desc}}" required>
                                </div>
                            </div>

                            <div class="row">
                                <label >Area o unidad admon. que solicita documento </label>
                                <select class="form-control m-bot15" name="dependencia_id" id="dependencia_id" required>
                                    <option selected="true" disabled="disabled">Seleccionar unidad admin.</option>
                                    @foreach($dep as $depen)
                                        @if($depen->depen_id == $regdocto->dependencia_id)
                                            <option value="{{$depen->depen_id}}" selected>{{trim($depen->depen_desc)}}</option>
                                        @else                                        
                                            <option value="{{$depen->depen_id}}">{{$depen->depen_id}} - {{trim($depen->depen_desc)}} 
                                            </option>
                                        @endif
                                    @endforeach
                                </select>                                                            
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Formato del tipo de documento </label>
                                    <select class="form-control m-bot15" name="formato_id" id="formato_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar formato del tipo de documento</option>
                                        @foreach($regformato as $formato)
                                            @if($formato->formato_id == $regdocto->formato_id)
                                                <option value="{{$formato->formato_id}}" selected>{{$formato->formato_desc}}</option>
                                            @else                                        
                                               <option value="{{$formato->formato_id}}">{{$formato->formato_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                     
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Periodo o frecuencia de entrega del formato </label>
                                    <select class="form-control m-bot15" name="per_id" id="per_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo o frecuencia de entrega del formato</option>
                                        @foreach($regper as $per)
                                            @if($per->per_id == $regdocto->per_id)
                                                <option value="{{$per->per_id}}" selected>{{$per->per_desc}}</option>
                                            @else                                        
                                               <option value="{{$per->per_id}}">{{$per->per_desc}}</option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                     
                            </div>

                            <div class="row">
                                <label >Rubro asistencial </label>
                                <select class="form-control m-bot15" name="rubro_id" id="rubro_id" required>
                                    <option selected="true" disabled="disabled">Seleccionar rubro asistencial</option>
                                    @foreach($regrubro as $rubro)
                                        @if($rubro->rubro_id == $regdocto->rubro_id)
                                            <option value="{{$rubro->rubro_id}}" selected>{{$rubro->rubro_desc}}</option>
                                        @else                                        
                                            <option value="{{$rubro->rubro_id}}">{{$rubro->rubro_desc}}</option>
                                        @endif
                                    @endforeach
                                </select>                                                            
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">                        
                                    <label>Documento Activo o Inactivo </label>
                                    <select class="form-control m-bot15" name="doc_status" required>
                                        @if($regdocto->doc_status == 'S')
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
                                    <label >Observaciones (300 carácteres)</label>
                                    <textarea class="form-control" name="doc_obs" id="doc_obs" rows="6" cols="120" placeholder="Observaciones" required>{{Trim($regdocto->doc_obs)}}
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
    {!! JsValidator::formRequest('App\Http\Requests\catdoctoRequest','#actualizarDocto') !!}
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