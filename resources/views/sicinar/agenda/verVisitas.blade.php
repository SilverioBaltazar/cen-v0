@extends('sicinar.principal')

@section('title','Ver Visitas de diligencias')

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
            <h1>Visitas de diligencia
                <small> Seleccionar alguna para editar o nueva visita de diligencia</small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Agenda de diligencias </a></li>
                <li><a href="#">Visitas de diligencia  </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">

                        <div class="box-header" style="text-align:right;">
                            Busqueda  
                            {{ Form::open(['route' => 'buscarVisita', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group"> Periodo
                                    <!--{{ Form::text('fper', null, ['class' => 'form-control', 'placeholder' => 'Periodo','maxlength' => '10']) }}
                                    {!! Form::label('fper','IAP') !!} -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="fper" id="fper" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{trim($periodo->periodo_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <div class="form-group">Mes
                                    <!--{{ Form::text('fmes', null, ['class' => 'form-control', 'placeholder' => 'Mes','maxlength' => '10']) }}  -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="fmes" id="fmes" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>                                
                                <div class="form-group">IAP
                                    <!--{{ Form::text('fiap', null, ['class' => 'form-control', 'placeholder' => 'IAP','maxlength' => '10']) }}-->
                                    <select class="form-control m-bot15" name="fiap" id="fiap" class="form-control">
                                        <option value=""> </option>
                                        @foreach($regiap as $iap)
                                            <option value="{{$iap->iap_id}}">{{substr($iap->iap_desc,1,20)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                <!--
                                <div class="form-group">
                                    {{ Form::text('bio', null, ['class' => 'form-control', 'placeholder' => 'Concepto']) }}
                                </div>
                                -->
                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:center; vertical-align: middle;">Folio      </th>
                                        <th style="text-align:center; vertical-align: middle;">Per.       </th>
                                        <th style="text-align:center; vertical-align: middle;">Mes        </th>
                                        <th style="text-align:center; vertical-align: middle;">Dia        </th>
                                        <th style="text-align:center; vertical-align: middle;">Hora       </th>

                                        <th style="text-align:left;   vertical-align: middle;">IAP        </th>
                                        <th style="text-align:left;   vertical-align: middle;">Domicilio  </th>
                                        <th style="text-align:center; vertical-align: middle;">Contacto   </th>
                                        <th style="text-align:left;   vertical-align: middle;">Teléfono   </th>
                                        <th style="text-align:left;   vertical-align: middle;">Objetivo   </th>                                        
                                        <th style="text-align:center; vertical-align: middle;">Abierta<br>Cerrada<br>Cancel</th>
                                        
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regvisita as $visita)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$visita->visita_folio}}    </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$visita->periodo_id}}</td> 
                                        <td style="text-align:left; vertical-align: middle;">  
                                            @foreach($regmeses as $mes)
                                                @if($mes->mes_id == $visita->mes_id)
                                                    {{$mes->mes_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>                    
                                        <td style="text-align:center; vertical-align: middle;">
                                            @foreach($regdias as $dia)
                                                @if($dia->dia_id == $visita->dia_id)
                                                    {{$dia->dia_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>
                                        <td style="text-align:center; vertical-align: middle;">
                                            @foreach($reghoras as $hora)
                                                @if($hora->hora_id == $visita->hora_id)
                                                    {{$hora->hora_desc}}
                                                    @break
                                                @endif
                                            @endforeach 
                                        </td>

                                        <td style="text-align:left; vertical-align: middle;">{{$visita->iap_id}}   
                                            @foreach($regiap as $iap)
                                                @if($iap->iap_id == $visita->iap_id)
                                                    {{$iap->iap_desc}}
                                                    @break
                                                @endif
                                            @endforeach
                                        </td>                                        
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($visita->visita_dom)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($visita->visita_contacto)}}
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($visita->visita_tel)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($visita->visita_obj)}}</td>
                                        @switch($visita->visita_edo)
                                        @case(0)  <!-- amarillo -->
                                            <td style="color:orange;text-align:center; vertical-align: middle;" title="En proceso"><i class="fa fa-ellipsis-h"></i>
                                            </td>
                                            @break
                                        @case(1)  <!-- cerrada -->
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Cerrada"><i class="fa fa-check"></i>
                                            </td>
                                            @break
                                        @case(2)
                                            <td style="color:red;text-align:center; vertical-align: middle;" title="Cancelada"><i class="fa fa-times"></i>
                                            </td>
                                            @break 
                                        @default 
                                            <td style="color:blue;text-align:center; vertical-align: middle;" title="Sin especificar"><i class="fa fa-times"></i>{{$visita->visita_edo}}
                                            </td>                                          
                                        @endswitch
                                        
                                        <td style="text-align:center;">
                                            <a href="{{route('editarVisita',$visita->visita_folio)}}" class="btn badge-warning" title="Registrar visita de diligencia"><i class="fa fa-edit"></i>
                                            </a>
                                            <a href="{{route('borrarVisita',$visita->visita_folio)}}" class="btn badge-danger" title="Borrar registro de visita de la agenda" onclick="return confirm('¿Seguro que desea borrar el registro de visita de la agenda de diligencias?')"><i class="fa fa-times"></i>
                                            </a>
                                            <a href="{{route('actavisitaPDF',$visita->visita_folio)}}" class="btn btn-danger" title="Generar la Acta de visita de verificación en formato PDF"><i class="fa fa-file-pdf-o"></i><small>PDF</small>
                                            </a>
                                        </td>                          
                                        <td style="text-align:center;">
                                            <a href="{{route('nuevoQuestion',$visita->visita_folio)}}" class="btn badge-warning" title="Aplicar cuestionario de la Diligencia"><i class="fa fa-times"></i><small>Aplicar</small>
                                            </a>
                                            <a href="{{route('editarQuestion',$visita->visita_folio)}}" class="btn btn-danger" title="Editar cuestionario de la diligencia"><i class="fa fa-times"></i><small>Editar</small>
                                            </a>
                                        </td>                                                                          
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regvisita->appends(request()->input())->links() !!}
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </div>
@endsection

@section('request')
@endsection

@section('javascrpt')
@endsection