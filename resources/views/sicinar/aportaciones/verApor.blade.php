@extends('sicinar.principal')

@section('title','Ver Aportaciones monetarias')

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
    <div class="content-wrapper">
        <section class="content-header">
            <h1>Aportaciones monetarias
                <small> Seleccionar alguna para editar o registrar </small>
            </h1>
            <ol class="breadcrumb">
                <li><a href="#"><i class="fa fa-dashboard"></i> Menú</a></li>
                <li><a href="#">Crédito y combranza - Cobranza - Aportaciones monetarias   </a></li>         
            </ol>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box">
                        <div class="box-header" style="text-align:right;">
                            
                            {{ Form::open(['route' => 'buscarApor', 'method' => 'GET', 'class' => 'form-inline pull-right']) }}
                                <div class="form-group"> Periodo
                                    <!--{{ Form::text('perr', null, ['class' => 'form-control', 'placeholder' => 'Periodo','maxlength' => '10']) }} -->
                                    <select class="form-control m-bot15" id="perr" name="perr" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regperiodos as $periodo)
                                            <option value="{{$periodo->periodo_id}}">{{trim($periodo->periodo_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>
                                
                                <div class="form-group">Cliente
                                    <!-- {{ Form::text('cliee', null, ['class' => 'form-control', 'placeholder' => 'Cliente','maxlength' => '20']) }} -->
                                    <select class="form-control m-bot15" id="cliee" name="cliee" class="form-control">
                                        <option value=""> </option>
                                        @foreach($regclientes as $cliente)
                                            <option value="{{$cliente->cliente_id}}">{{trim($cliente->cliente_nombrecompleto)}}</option>
                                        @endforeach   
                                    </select>
                                </div>

                                <div class="form-group">Mes
                                    <!--{{ Form::text('fmes', null, ['class' => 'form-control', 'placeholder' => 'Mes','maxlength' => '10']) }}  -->
                                    <!--<option value=""> --Seleccionar periodo-- </option> -->
                                    <select class="form-control m-bot15" name="mess" id="mess" class="form-control">
                                        <option value=""> </option> 
                                        @foreach($regmeses as $mes)
                                            <option value="{{$mes->mes_id}}">{{trim($mes->mes_desc)}}</option>
                                        @endforeach   
                                    </select>
                                </div>                                

                                <div class="form-group">
                                    <button type="submit" class="btn btn-default">
                                    <span class="glyphicon glyphicon-search"></span>
                                    </button>
                                </div>
                                <div class="form-group">
                                    <a href="{{route('nuevaApor')}}" class="btn btn-primary btn_xs" title="Registrar nueva aportación"><i class="fa fa-file-new-o"></i><span class="glyphicon glyphicon-plus"></span>Registrar aportación</a> 
                                </div>                                
                            {{ Form::close() }}
                        </div>

                        <div class="box-body">
                            <table id="tabla1" class="table table-striped table-bordered table-sm">
                                <thead style="color: brown;" class="justify">
                                    <tr>
                                        <th style="text-align:left;   vertical-align: middle;">Periodo <br>Fiscal    </th>
                                        <th style="text-align:left;   vertical-align: middle;">Folio   <br>Sistema   </th>
                                        <th style="text-align:left;   vertical-align: middle;">No.     <br>recibo    </th>                                        
                                        <th style="text-align:left;   vertical-align: middle;">Cliente               </th>
                                        <th style="text-align:left;   vertical-align: middle;">Concepto              </th>
                                        <th style="text-align:left;   vertical-align: middle;">Importe $             </th>

                                        <th style="text-align:left;   vertical-align: middle;">Empleado que cobro aportación </th>     
                                        <th style="text-align:center; vertical-align: middle;">Comprob. <br>Pago     </th>
                                        <th style="text-align:center; vertical-align: middle;">Fec.reg.              </th>
                                        <th style="text-align:center; vertical-align: middle;">Activa / <br>Cancel.  </th> 
                                        <th style="text-align:center; vertical-align: middle; width:100px;">Acciones </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($regapor as $apor)
                                    <tr>
                                        <td style="text-align:left; vertical-align: middle;">{{$apor->periodo_id}}   </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$apor->apor_folio}}   </td>
                                        <td style="text-align:left; vertical-align: middle;">{{$apor->apor_recibo}}</td>                                        
                                        <td style="text-align:left; vertical-align: middle;">
                                        @foreach($regclientes as $cliente)
                                            @if($cliente->cliente_id == $apor->cliente_id)
                                                {{$cliente->cliente_id.' '.$cliente->cliente_nombrecompleto}}
                                                @break
                                            @endif
                                        @endforeach 
                                        </td>
                                        <td style="text-align:left; vertical-align: middle;">{{Trim($apor->apor_concepto)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">{{number_format($apor->apor_importe,2)}}</td>
                                        <td style="text-align:left; vertical-align: middle;">
                                        @foreach($regempleados as $empleado)
                                            @if($empleado->emp_id == $apor->emp_id)
                                                {{$empleado->emp_id.' '.$empleado->emp_nombrecompleto}}
                                                @break
                                            @endif
                                        @endforeach
                                        </td>
                                        
                                        @if(!empty(trim($apor->apor_foto1))&&(!is_null($apor->apor_foto1)))
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Comprobante de aportación">
                                                <a href="/images/{{$apor->apor_foto1}}" class="btn btn-danger" title="Comprobante de aportación"><i class="fa fa-file-pdf-o"></i>PDF
                                                </a>
                                                <a href="{{route('editarApor1',$apor->apor_folio)}}" class="btn btn-warning" title="Editar Comprobante de aportación"><i class="fa fa-edit"></i>
                                                </a>
                                            </td>
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Comprobante de aportación">
                                                <i class="fa fa-times"></i>
                                                <a href="{{route('editarApor1',$apor->apor_folio)}}" class="btn btn-warning" title="Editar comprobante de aportación"><i class="fa fa-edit"></i>
                                                </a>                                                
                                            </td>   
                                        @endif   
                                        
                                        <td style="text-align:center; vertical-align: middle;">{{date("d/m/Y", strtotime($apor->fecreg))}}
                                        </td>                                                                              
                                        @if($apor->apor_status1 == 'S')
                                            <td style="color:darkgreen;text-align:center; vertical-align: middle;" title="Activa"><i class="fa fa-check"></i>
                                            </td>                                            
                                        @else
                                            <td style="color:darkred; text-align:center; vertical-align: middle;" title="Cancelada"><i class="fa fa-times"></i>
                                            </td>                                            
                                        @endif
                                        @if($role->rol_name == 'user')
                                            <td style="text-align:center;"> - </td>                                        
                                        @else
                                            <td style="text-align:center;">
                                                <!--
                                                <a href="{{route('editarApor',$apor->apor_folio)}}" class="btn badge-warning" title="Editar"><i class="fa fa-edit"></i></a> 
                                                -->
                                                <a href="{{route('borrarApor',$apor->apor_folio)}}" class="btn btn-danger" title="Cancelar aportación monetaria" onclick="return confirm('¿Seguro que desea cancelar la aportación monetaria?')"><i class="fa fa-trash"></i>
                                                </a>
                                            </td>
                                        @endif 
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                            {!! $regapor->appends(request()->input())->links() !!}
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
