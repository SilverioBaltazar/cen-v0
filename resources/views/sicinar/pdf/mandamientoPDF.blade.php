@extends('sicinar.pdf.layoutMandamiento')

@section('content')
    <head>
        <style>
        @page { margin-top: 50px; margin-bottom: 100px; margin-left: 50px; margin-right: 50px; }
        body{color: #767676;background: #fff;font-family: 'Open Sans',sans-serif;}
        #header { position: fixed; left: 0px; top: -20px; right: 0px; height: 375px; }
        #footer { position: fixed; left: 0px; bottom: -100px; right: 0px; height: 50px; text-align:right; font-size: 8px;}
        #footer .page:after { content: counter(page, upper-roman); }
        #content{ }   
        </style>
    </head>

    <body>
    <header id="header">
        <p>
            <img src="{{ asset('images/escudo-edomexhor.jpg') }}" alt="EDOMEX" width="190px" height="50px" border="0" align="left"/>
            <img src="{{ asset('images/escudos-japem-edomexmazo.jpg') }}" alt="JAPEM" width="175px" height="50px" border="0" align="right"/>
        </p>
        <br><br><br>
        <p style="border:0; font-family:'HelveticaNeueLT Std'; font-size:8px; text-align:center;">
            “2019. Año del centésimo  aniversario luctuoso de Emiliano Zapata Salazar. El Caudillo del Sur”
        </p>
    </header>
  
    <section id="content">
        <table class="table table-hover table-striped" align="center" width="100%"> 
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            
            @foreach($regprogdil as $program)
            <tr>
                <td style="border:0; text-align:right;">Toluca de Lerdo, México a {{date('d')}} de {{strftime("%B")}} de {{date('Y')}}
                <br>
                MANDAMIENTO ESCRITO No. JAP/<b>{{$program->visita_folio}}/{{date('Y')}}</b>
                <br>
                No. DE REGISTRO <b> 
                @foreach($regiap as $iap)
                        @if($iap->iap_id == $program->iap_id)
                            {{Trim($iap->iap_regcons)}}
                        @endif
                @endforeach
                </b>     
                </td>
            </tr>
            
            <tr>
                <td style="border:0; text-align:left;vertical-align: middle;">
                    <p align="justify">
                    <b>{{$program->visita_auditor4}}</b>; Secretario Ejecutivo de la Junta de Asistencia Privada del Estado de México, en el ejercicio de las funciones y atribuciones que me confiere el Artículo 24  Fracciones II y III de la Ley de Instituciones de Asistencia Privada del Estado de México, con fundamento en el artículo 16 de la Constitución Política de los Estados Unidos Mexicanos y los artículos 64 inciso a) fracciones II, III, IV, V, X, XII, XIII y XIV,  inciso b) fracción IV, 72, 73, 74, 92, 93, fracciones I, II, III, IV, 94 y 95 de la Ley de Instituciones de Asistencia Privada del Estado de México y 128 del Código de Procedimientos Administrativos del Estado de México, por este medio, le informo a usted que la Institución de Asistencia Privada que representa, será verificada el día <b>{{$program->dia_id}}</b> de <b> 
                    @foreach($regmeses as $mes)
                        @if($mes->mes_id == $program->mes_id)
                            {{$mes->mes_desc}}
                        @endif
                    @endforeach
                    </b>
                    del <b>{{$program->periodo_id}}</b>; por lo que deberá permitir el acceso al siguiente personal: <b>{{$program->visita_spub2}}</b>, adscrito a la Junta de Asistencia Privada del Estado de México, quien se identifica con gafete, que cuenta con fotografía al frente expedido y firmado autógrafamente por el Secretario Ejecutivo de esta Junta respectivamente, en las instalaciones de la institución denominada: 
                    <b> {{$program->iap_id}} </b>, I.A.P. que se ubica en: <b>{{$program->visita_dom}}</b>, con el objeto de  revisar los CRITERIOS DE VERIFICACIÓN, el CUMPLIMIENTO DEL OBJETO SOCIAL mismo que se describe a continuación: <b>{{$program->visita_obj}}</b> y TODA AQUELLA DOCUMENTACIÓN QUE SUSTENTEN LAS ACTIVIDADES ASISTENCIALES REALIZADAS POR LA INSTITUCIÓN, con fundamento en el artículo 22, fracción VII y articulo 64, fracción III, de la Ley de Instituciones de Asistencia Privada del Estado de México. No omito comentar, que en caso de existir alguna manifestación al respecto; los visitados, su representante o la persona que  atienda la verificación, podrá formular sus observaciones en el acto de la diligencia y ofrecer pruebas en relación a los hechos u omisiones contenidos en el acta de verificación o bien hacer uso de ese derecho, por escrito, dentro del término de los tres días siguientes a la fecha en que se hubiera levantado el acta; lo anterior con fundamento en el artículo 128 fracción X del Código de Procedimientos Administrativos del Estado de México.
                    <br><br>
                    Agradeciendo la atención brindada al presente, me despido de usted.
                    </p>
                </td>
            </tr>
            
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr><td style="border:0;text-align:center;">A T E N T A M E N T E</td></tr>
            <tr><td style="border:0;"></td></tr>
            <tr>
                <td style="border:0;text-align:center;"><b>{{$program->visita_auditor4}}</b>
                    <br>NOMBRE Y FIRMA DEL SECRETARIO EJECUTIVO DE LA JAPEM
                </td>
            </tr>

            <tr><td style="border:0;"></td></tr> 
            <tr><td style="border:0;"></td></tr>

            <tr>
                <td style="border:0; text-align:left;font-size: 7px;">c.c.p. <b>{{$program->visita_auditor2}}</b>
                <br><b>{{$program->visita_spub}}</b>
                </td>
            </tr>
            @endforeach 
        </table>
    </section>

    <footer id="footer">
        <table class="table table-hover table-striped" align="center" width="100%">
            <tr>
                <td style="border:0; text-align:right;">
                    <b>SECRETARIA DE DESARROLLO SOCIAL</b><br>JUNTA DE ASISTENCIA PRIVADA DEL ESTADO DE MÉXICO
                </td>
            </tr>
        </table>
    </footer>    
    </body>
@endsection

@section('javascrpt')
<!-- link de referencia de este ejmplo   http://www.ertomy.es/2018/07/generando-pdfs-con-laravel-5/ -->
<!-- si el PDF tiene varias páginas entonces hay que meter numeración de las paginas. 
     Para ello tendremos que poner el siguiente código en la plantilla: -->
<script type="text/php">
    $text = 'Página {PAGE_NUM} de {PAGE_COUNT}';
    $font = Font_Metrics::get_font("sans-serif");
    $pdf->page_text(493, 800, $text, $font, 7);
</script>
@endsection