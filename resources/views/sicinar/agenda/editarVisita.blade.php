@extends('sicinar.principal')

@section('title','Registrar visita de diligiencia')

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
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small> Agenda de diligencias - Visitas de diligencia</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar registro de visita de diligencia</h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarVisita',$regvisita->visita_folio], 'method' => 'PUT', 'id' => 'actualizarVisita', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <div class="row">    
                                <div class="col-xs-4 form-group">
                                    <label >IAP </label>
                                    <td style="text-align:left; vertical-align: middle;">   
                                        @foreach($regiap as $iap)
                                            @if($iap->iap_id == $regvisita->iap_id)
                                                    {{$iap->iap_desc}}
                                                    @break
                                            @endif
                                        @endforeach
                                    </td>                                     
                                </div> 
                                <div class="col-xs-4 form-group">
                                    <label >Domicilio </label>
                                    <input type="text" class="form-control" name="visita_dom" id="visita_dom" placeholder="Domicilio a donde va a ser la diligencia" value="{{Trim($regvisita->visita_dom)}}" required>
                                </div>    
                            </div>

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Municipio</label>
                                    <select class="form-control m-bot15" name="municipio2_id" id="municipio2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar municipio</option>
                                        @foreach($regmunicipio as $municipio)
                                            @if($municipio->municipioid == $regvisita->municipio2_id)
                                                <option value="{{$municipio->municipioid}}" selected>{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}</option>
                                            @else 
                                               <option value="{{$municipio->municipioid}}">{{$municipio->entidadfederativa_desc.'-'.$municipio->municipionombre}}
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                  
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Periodo fiscal </label>
                                    <select class="form-control m-bot15" name="periodo2_id" id="periodo2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar Periodo fiscal </option>
                                        @foreach($regperiodos as $periodo)
                                            @if($periodo->periodo_id == $regvisita->periodo2_id)
                                                <option value="{{$periodo->periodo_id}}" selected>{{$periodo->periodo_desc}}</option>
                                            @else                                        
                                               <option value="{{$periodo->periodo_id}}">{{$periodo->periodo_desc}} 
                                               </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>   
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label>
                                    <select class="form-control m-bot15" name="mes2_id" id="mes2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar mes </option>
                                        @foreach($regmeses as $mes)
                                            @if($mes->mes_id == $regvisita->mes2_id)
                                                <option value="{{$mes->mes_id}}" selected>{{$mes->mes_desc}}</option>
                                            @else                                        
                                               <option value="{{$mes->mes_id}}">{{$mes->mes_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                                <div class="col-xs-4 form-group">
                                    <label >Dia </label>
                                    <select class="form-control m-bot15" name="dia2_id" id="dia2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar dia </option>
                                        @foreach($regdias as $dia)
                                            @if($dia->dia_id == $regvisita->dia2_id)
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
                                    <label >Hora </label>
                                    <select class="form-control m-bot15" name="hora2_id" id="hora2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar hora </option>
                                        @foreach($reghoras as $hora)
                                            @if($hora->hora_id == $regvisita->hora2_id)
                                                <option value="{{$hora->hora_id}}" selected>{{$hora->hora_desc}}</option>
                                            @else                                        
                                               <option value="{{$hora->hora_id}}">{{$hora->hora_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Minutos </label>
                                    <select class="form-control m-bot15" name="num2_id" id="num2_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar minutos </option>
                                        @foreach($regminutos as $min)
                                            @if($min->num_id == $regvisita->num2_id)
                                                <option value="{{$min->num_id}}" selected>{{$min->num_desc}}</option>
                                            @else                                        
                                               <option value="{{$min->num_id}}">{{$min->num_desc}} </option>
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                   
                            </div>         

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Auditado </label>
                                    <input type="text" class="form-control" name="visita_auditado1" id="visita_auditado1" placeholder="Auditado" value="{{Trim($regvisita->visita_auditado1)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Puesto</label>
                                    <input type="text" class="form-control" name="visita_puesto1" id="visita_puesto1" placeholder="Puesto del auditado" value="{{Trim($regvisita->visita_puesto1)}}" required>
                                </div>                                
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Se identifica con </label>
                                    <input type="text" class="form-control" name="visita_ident1" id="visita_ident1" placeholder="Se identifica con" value="{{Trim($regvisita->visita_ident1)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Expedida por</label>
                                    <input type="text" class="form-control" name="visita_exped1" id="visita_exped1" placeholder="Expedida por" value="{{Trim($regvisita->visita_exped1)}}" required>
                                </div>                                
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Representante legal de la IAP</label>
                                    <input type="text" class="form-control" name="visita_auditado2" id="visita_auditado2" placeholder="Representante legal de la IAP" value="{{$regvisita->visita_auditado2}}" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Puesto del representante legal de la IAP</label>
                                    <input type="text" class="form-control" name="visita_puesto2" id="visita_puesto2" placeholder="Puesto del representante legal DE LA iap" value="{{Trim($regvisita->regvisita_puesto2)}}" required>
                                </div>                                          
                            </div>
                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Se identifica el representante legal con </label>
                                    <input type="text" class="form-control" name="visita_ident2" id="visita_ident2" placeholder="Se identifica el representante legal con" value="{{Trim($regvisita->visita_ident2)}}" required>
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Expedida por</label>
                                    <input type="text" class="form-control" name="visita_exped2" id="visita_exped2" placeholder="Expedida por" value="{{Trim($regvisita->visita_exped2)}}" required>
                                </div>                                
                            </div>                            

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Pesona que atendio la diligencia en la IAP</label>
                                    <input type="text" class="form-control" name="visita_auditado3" id="visita_auditado3" placeholder="Persona que atendio la diligencia" value="{{$regvisita->visita_auditado3}}" required>
                                </div>                                                                        
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Responsable del JAPEM que realizo la diligencia</label>
                                    <input type="text" class="form-control" name="visita_auditor2" id="visita_auditor2" placeholder="Responsable del JAPEM que realizo la diligencia" value="{{$regvisita->visita_auditor2}}" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Personal del JAPEM presente en la diligencia</label>
                                    <input type="text" class="form-control" name="visita_auditor1" id="visita_auditor1" placeholder="Personal de la JAPEM presente en la diligencia" value="{{Trim($regvisita->visita_auditor1)}}" required>
                                </div>                                                                       
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Testigo 1</label>
                                    <input type="text" class="form-control" name="visita_testigo1" id="visita_testigo1" placeholder="Testigo 1" value="{{$regvisita->visita_testigo1}}" required>
                                </div>   
                                <div class="col-xs-4 form-group">
                                    <label >Testigo 2</label>
                                    <input type="text" class="form-control" name="visita_testigo2" id="visita_testigo2" placeholder="Testigo 2" value="{{Trim($regvisita->visita_testigo2)}}" required>
                                </div>                                                                       
                            </div>                            

                            <div class="row">                                                             
                                <div class="col-xs-4 form-group">                        
                                    <label>Estado de la diligencia (en proceso, cerrada o cancelada </label>
                                    <select class="form-control m-bot15" name="visita_edo" id="visita_edo" required>
                                        @if($regvisita->visita_edo == '1')
                                            <option value="0"         >En proceso</option>
                                            <option value="1" selected>Cerrada   </option>
                                            <option value="2"         >Cancelada </option>
                                        @else
                                            @if($regvisita->visita_edo == '2')
                                               <option value="0"         >En proceso</option>
                                               <option value="1"         >Cerrada   </option>
                                               <option value="2" selected>Cancelada </option>
                                            @else
                                               <option value="0" selected>En proceso</option>
                                               <option value="1"         >Cerrada   </option>
                                               <option value="2"         >Cancelada </option>
                                            @endif
                                        @endif
                                    </select>
                                </div>  
                                <div class="col-xs-4 form-group">                        
                                    <label>¿Se realizo visita de diligencia? </label>
                                    <select class="form-control m-bot15" name="visita_status2" id="visita_status2" required>
                                        @if($regvisita->status2 == 'S')
                                            <option value="S" selected>Si </option>
                                            <option value="N"         >No </option>
                                        @else
                                            <option value="S"         >Si </option>
                                            <option value="N" selected>No </option>
                                        @endif
                                    </select>
                                </div>              
                            </div>

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Criterios de verificación (500 carácteres)</label>
                                    <textarea class="form-control" name="visita_criterios" id="visita_criterios" rows="6" cols="120" placeholder="Criterios de verificación de la visita de diligencia" required>{{Trim($regvisita->visita_criterios)}}
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Visto de la diligencia (500 carácteres)</label>
                                    <textarea class="form-control" name="visita_visto" id="visita_visto" rows="6" cols="120" placeholder="Visto de la diligencia" required>{{Trim($regvisita->visita_visto)}}
                                    </textarea>
                                </div>                                
                            </div>
                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Recomendaciones (500 carácteres)</label>
                                    <textarea class="form-control" name="visita_recomen" id="visita_recomen" rows="6" cols="120" placeholder="Recomendaciones" required>{{Trim($regvisita->visita_recomen)}}
                                    </textarea>
                                </div>                                
                            </div>                                                        

                            <div class="row">                                
                                <div class="col-xs-12 form-group">
                                    <label >Sugerencias (500 carácteres)</label>
                                    <textarea class="form-control" name="visita_sugeren" id="visita_sugeren" rows="6" cols="120" placeholder="Sugerencias" required>{{Trim($regvisita->visita_sugeren)}}
                                    </textarea>
                                </div>                                
                            </div>                                                        

                            <div class="row">                                
                                <div class="col-xs-4 form-group">
                                    <label >Plazo de entrega de duplicado de Acta de visita </label>
                                    <select class="form-control m-bot15" name="num3_id" id="num3_id" required>
                                        <option selected="true" disabled="disabled">Seleccionar plazo de entrega</option>
                                        @foreach($regminutos as $num)
                                            @if($num->num_id == $regvisita->num3_id)
                                                <option value="{{$num->num_id}}" selected>{{$num->num_desc}}</option>
                                            @else                                        
                                               <option value="{{$num->num_id}}">{{$num->num_desc}} </option>
                                            @endif
                                        @endforeach
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
                                    <a href="{{route('verVisitas')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\visitaRequest','#actualizarVisita') !!}
@endsection

@section('javascrpt')
@endsection