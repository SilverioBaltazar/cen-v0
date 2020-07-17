@extends('sicinar.principal')

@section('title','Editar archivo digital de servicio de carga de combustible')

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
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
    <div class="content-wrapper">
        <section class="content-header">
            <h1>
                Menú
                <small>Formatos de comprobación - Recibo de bitacora - cargas - Editar</small>
            </h1>
        </section>
        <section class="content">
            <div class="row">
                <div class="col-md-12">
                    <div class="box box-success">
                        {!! Form::open(['route' => ['actualizarCarga1', $regcargas->periodo_id,$regcargas->recibo_folio,$regcargas->carga], 'method' => 'PUT', 'id' => 'actualizarCarga1', 'enctype' => 'multipart/form-data']) !!}
                        <div class="box-body">

                            <table id="tabla1" class="table table-hover table-striped">
                            @foreach($regrecibos as $recibo)                                               
                            <tr>                            
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_id" name="placa_id" value="{{$recibo->placa_id}}">  
                                    <label>Código: </label><b>{{$recibo->placa_id}}</b>
                                </td>
                                <td style="text-align:left; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="placa_placa" name="placa_placa" value="{{$recibo->placa_placa}}">  
                                    <label>Placas : </label><b>{{$recibo->placa_placa}}</b>
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="periodo_id" name="periodo_id" value="{{$recibo->periodo_id}}">  
                                    <label>Periodo fiscal : </label>{{$recibo->periodo_id}}                                        
                                </td>
                                <td style="text-align:center; vertical-align: middle;color:green;"> 
                                    <input type="hidden" id="mes_id" name="mes_id" value="{{$recibo->mes_id}}">  
                                    <label>Mes : </label><b>
                                    @foreach($regmes as $mes)
                                        @if($mes->mes_id == $recibo->mes_id)
                                            {{$mes->mes_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                    </b>
                                </td>                                
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="quincena_id" name="quincena_id" value="{{$recibo->quincena_id}}">  
                                    <label>Quincena : </label>
                                    @foreach($regquincena as $quincena)
                                        @if($quincena->quincena_id == $recibo->quincena_id)
                                            {{$quincena->quincena_desc}}
                                            @break
                                        @endif
                                    @endforeach
                                </td>                                     
                                <td style="text-align:right; vertical-align: middle;color:green;">   
                                    <input type="hidden" id="recibo_folio" name="recibo_folio" value="{{$recibo->recibo_folio}}">  
                                    <label>Folio : </label>{{$recibo->recibo_folio}}
                                </td>                                                                
                            </tr>      
                            @endforeach     
                            </table>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de emisión del ticket de pago -año </label><br>
                                        {{$regcargas->periodo_id1}}
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Mes </label><br>
                                        @foreach($regmes as $mesi)
                                            @if($mesi->mes_id == $regcargas->mes_id1)
                                                {{$mesi->mes_desc}}
                                                @break                                        
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Día </label><br>
                                        @foreach($regdia as $diai)
                                            @if($diai->dia_id == $regcargas->dia_id1)
                                                {{$diai->dia_desc}}
                                                @break                                        
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>
                            <div class="row">  
                                <div class="col-xs-4 form-group">
                                    <label >Folio de aprobación </label><br>
                                    {{$regcargas->tkpag_folaprob}}
                                </div>                                                           
                                <div class="col-xs-4 form-group">
                                    <label >Hr. (hh:mm) </label><br>
                                    {{$regcargas->tkpag_hora}}
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Venta total $ en M.N. </label><br>
                                    {{$regcargas->tkpag_importe}}
                                </div>                                                                      
                            </div>

                            <div class="row">
                                <div class="col-xs-4 form-group">
                                    <label >Fecha de emisión del ticket de bomba - año </label><br>
                                        {{$regcargas->periodo_id2}}
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Mes    </label><br>
                                        @foreach($regmes as $mesf)
                                            @if($mesf->mes_id == $regcargas->mes_id2)
                                                {{$mesf->mes_desc}}
                                                @break        
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Día    </label><br>
                                        @foreach($regdia as $diaf)
                                            @if($diaf->dia_id == $regcargas->dia_id2)
                                                {{$diaf->dia_desc}}
                                                @break                                        
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                
                            </div>

                            <div class="row">                                                                 
                                <div class="col-xs-4 form-group">
                                    <label >RFC del proveedor de servicio </label><br>
                                    {{$regcargas->tkbomba_rfc}}
                                </div>
                                <div class="col-xs-4 form-group">
                                    <label >Ticket emitido por la bomba de servicio</label><br>
                                    {{$regcargas->tkbomba_ticket}}
                                </div>                                
                            </div>

                            <div class="row">                                 
                                <div class="col-xs-4 form-group">
                                    <label >Hr. (hh:mm) de emisión del ticket de bomba</label><br>
                                    {{$regcargas->tkbomba_hora}}
                                </div>  
                                <div class="col-xs-4 form-group">
                                    <label >Total $ en M.N. </label><br>
                                    {{$regcargas->tkbomba_importe}}
                                </div>                                                                      
                                <div class="col-xs-4 form-group">
                                    <label >Forma de pago </label><br>
                                        @foreach($regfpagos as $fpago)
                                            @if($fpago->fp_id == $regcargas->fp_id)
                                                {{$fpago->fp_desc}}
                                                @break
                                            @endif
                                        @endforeach
                                    </select>                                    
                                </div>                                  
                            </div>

                            <div class="row">
                                <div class="col-xs-12 form-group">
                                    <label style="background-color:yellow;color:red"><b>Nota importante:</b>El archivo digital en formato PDF, NO deberá ser mayor a 1,500 kbytes en tamaño.  </label>
                                </div>   
                            </div>
                            <div class="row">    
                                @if (!empty($regcargas->carga_foto1)||!is_null($regcargas->carga_foto1))  
                                    <div class="col-xs-12 form-group">
                                        <label >Archivo digital de servicio de carga de combustible en formato PDF</label>
                                        <label ><a href="/images/{{$regcargas->carga_foto1}}" class="btn btn-danger" title="Archivo digital de servicio de carga de combustible en formato PDF"><i class="fa fa-file-pdf-o"></i>{{$regcargas->carga_foto1}}</a>
                                        </label>
                                    </div>   
                                    <div class="col-xs-12 form-group">
                                        <label >Actualizar archivo digital de servicio de carga de combustible en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="carga_foto1" id="carga_foto1" placeholder="Subir archivo digital de servicio de carga de combustible en formato PDF" >
                                    </div>      
                                @else     <!-- se captura archivo 1 -->
                                    <div class="col-xs-12 form-group"> 
                                        <label >Archivo digital de servicio de carga de combustible en formato PDF</label>
                                        <input type="file" class="text-md-center" style="color:red" name="carga_foto1" id="carga_foto1" placeholder="Subir archivo digital de servicio de carga de combustible en formato PDF" >
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
            
                                    @foreach($regrecibos as $recibo)
                                       <a href="{{route('verCargas',array($recibo->periodo_id,$recibo->recibo_folio))}}" role="button" id="cancelar" class="btn btn-danger">Cancelar
                                       </a>
                                       @break
                                    @endforeach
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
    {!! JsValidator::formRequest('App\Http\Requests\carga1Request','#actualizarCarga1') !!}
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
