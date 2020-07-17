@extends('sicinar.principal')

@section('title','Editar aportación monetaria')

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
            <h1>Menú
                <small>Crédito y combranza - Cobranza - Aportaciones monetarias - Editar</small>                
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-primary">

                        {!! Form::open(['route' => ['actualizarApor1',$regapor->apor_folio], 'method' => 'PUT', 'id' => 'actualizarApor1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <table id="tabla1" class="table table-hover table-striped">              
                                <tr>                            
                                    <td style="text-align:left; vertical-align: middle;color:green;"> 
                                        <label >Fecha de pago:  {{date("d/m/Y", strtotime($regapor->apor_fecha2))}}</label>
                                    </td>
                                    <td style="text-align:center; vertical-align: middle;color:green;"> 
                                        <label >Cliente:    
                                        @foreach($regclientes as $cli)
                                            @if($cli->cliente_id == $regapor->cliente_id)
                                                {{$cli->cliente_nombrecompleto}}
                                                @break
                                            @endif
                                        @endforeach 
                                        </label>
                                    </td>
                                    <td style="text-align:right; vertical-align: middle;color:green;"> 
                                        <label>Folio: {{$regapor->apor_folio}} </label>
                                    </td>                                    
                                </tr>             
                            </table>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales en formato PDF, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>


                            <div class="row">    
                                @if(!empty(trim($regapor->apor_foto1))&&(!is_null($regapor->apor_foto1)))
                                    <div class="col-xs-4 form-group">
                                        <label >Recibo de cobro de aportación en formato PDF</label><br>
                                        <label ><a href="/images/{{$regapor->apor_foto1}}" class="btn btn-danger" title="Recibo de cobro de aportación en formato PDF"><i class="fa fa-file-pdf-o"></i>PDF</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-6 form-group">
                                        <label >Actualizar archivo digital de Recibo de cobro de aportación en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="apor_foto1" id="apor_foto1" placeholder="Subir archivo digital de Recibo de cobro de aportación en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-4 form-group">
                                        <label >Archivo digital de Recibo de cobro de aportación en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="apor_foto1" id="apor_foto1" placeholder="Subir archivo digital de Recibo de cobro de aportación en formato PDF" >
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
                                    <a href="{{route('verApor')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\aportaciones1Request','#actualizarApor1') !!}
@endsection

@section('javascrpt')
@endsection