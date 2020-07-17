@extends('sicinar.principal')

@section('title','Editar Bitacora de rendimiento')

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
    <meta charset="utf-8">
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small>Comprobantes - Bitacora - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        <div class="box-header">
                            <h3 class="box-title">Editar bitacora de rendimiento </h3>
                        </div>
                        {!! Form::open(['route' => ['actualizarBitarendi1',$regbitarendi->bitaco_folio], 'method' => 'PUT', 'id' => 'actualizarBitarendi1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">
                            <table id="tabla1" class="table table-hover table-striped">              
                                <tr>                            
                                    <td style="text-align:left; vertical-align: middle;color:green;"> 
                                        <label >Codigo:    {{$regbitarendi->placa_id}} </label>
                                    </td>
                                    <td style="text-align:center; vertical-align: middle;color:green;"> 
                                        <label >Placas:    {{$regbitarendi->placa_placa}} </label>
                                    </td>
                                    <td style="text-align:right; vertical-align: middle;color:green;"> 
                                        <label>Folio: {{$regbitarendi->bitaco_folio}}</label>
                                    </td>                                    
                                </tr>             
                            </table>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b> Los archivos digitales en formato PDF, NO deberán ser mayores a 1,500 kBytes en tamaño.  </label>
                                </div>   
                            </div>

                            <div class="row">    
                                @if (!empty($regbitarendi->bitaco_foto1)||!is_null($regbitarendi->bitaco_foto1))  
                                    <div class="col-xs-12 form-group">
                                        <label >Archivo 1 de bitacora de rendimiento de combustible en formato PDF</label>
                                        <label ><a href="/images/{{$regbitarendi->bitaco_foto1}}" class="btn btn-danger" title="Archivo 1 de bitacora de rendimiento de combustible en formato PDF"><i class="fa fa-file-pdf-o"></i>{{$regbitarendi->bitaco_foto1}}</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-12 form-group">
                                        <label >Actualizar Archivo 1 de bitacora de rendimiento de combustible en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="bitaco_foto1" id="bitaco_foto1" placeholder="Subir archivo 1 de bitacora de rendimiento de combustible en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-12 form-group">
                                        <label >Archivo 1 de bitacora de rendimiento de combustible en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="bitaco_foto1" id="bitaco_foto1" placeholder="Subir archivo 1 de bitacora de rendimiento de combustible en formato PDF" >
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
                                    {!! Form::submit('Guardar',['class' => 'btn btn-success btn-flat pull-right']) !!}
                                    <a href="{{route('verBitarendi')}}" role="button" id="cancelar" class="btn btn-danger">Cancelar</a>
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
    {!! JsValidator::formRequest('App\Http\Requests\bitarendi1Request','#actualizarBitarendi1') !!}
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