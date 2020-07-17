<?php

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/
 
Route::get('/', function () {
    return view('sicinar.login.loginInicio');
});
    // *********** ver link 
    //Route::put('post/{id}', function ($id) {     //escribir validacones })->middleware('auth', 'role:admin');
    
    //************** ¿Cómo validamos multiples usuarios con Middleware? ******//
    //Route::group(['middleware' => ['auth', 'role:employee|manager']], function () {   
    //Route::get('/admin/home', 'AdminController@index')->name('home'); }); 

    Route::group(['prefix' => 'control-interno'], function() {
    
    Route::post('menu', 'usersController@actionLogin')->name('login');
    Route::get('status-sesion/expirada', 'usersController@actionExpirada')->name('expirada');
    Route::get('status-sesion/terminada', 'usersController@actionCerrarSesion')->name('terminada');

    //Route::get('/home', 'HomeController@index')->name('home');
    //Route::get('/admin-home', 'HomeController@index')->middleware('AuthAdmin');       

    // BACK OFFICE DEL SISTEMA
    //Route::get('BackOffice/usuarios'                ,'usuariosController@actionNuevoUsuario')->name('nuevoUsuario');
    //Route::post('BackOffice/usuarios/alta'          ,'usuariosController@actionAltaUsuario')->name('altaUsuario');
    //Route::get('BackOffice/usuarios/todos'          ,'usuariosController@actionVerUsuario')->name('verUsuarios');
    //Route::get('BackOffice/usuarios/{id}/editar'    ,'usuariosController@actionEditarUsuario')->name('editarUsuario');
    //Route::put('BackOffice/usuarios/{id}/actualizar','usuariosController@actionActualizarUsuario')->name('actualizarUsuario');
    //Route::get('BackOffice/usuarios/{id}/Borrar'    ,'usuariosController@actionBorrarUsuario')->name('borrarUsuario'); 

    Route::get('users/ver/todos'        ,'usersController@actionVerUser')->name('verUser');
    Route::get('users/nuevo'            ,'usersController@actionNuevoUser')->name('nuevoUser');
    Route::post('users/nuevo/alta'      ,'usersController@actionAltaUser')->name('altaUser');    
    Route::get('users/{id}/editar/user' ,'usersController@actionEditarUser')->name('editarUser');
    Route::put('users/{id}/actualizar'  ,'usersController@actionActualizarUser')->name('actualizarUser');
    Route::get('users/{id}/Borrar'      ,'usersController@actionBorrarUser')->name('borrarUser');    

    //Catalogos
    //Procesos
    Route::get('proceso/nuevo'      ,'procesosController@actionNuevoProceso')->name('nuevoProceso');
    Route::post('proceso/nuevo/alta','procesosController@actionAltaNuevoProceso')->name('AltaNuevoProceso');
    Route::get('proceso/ver/todos'  ,'procesosController@actionVerProceso')->name('verProceso');
    Route::get('proceso/{id}/editar/proceso','procesosController@actionEditarProceso')->name('editarProceso');
    Route::put('proceso/{id}/actualizar'    ,'procesosController@actionActualizarProceso')->name('actualizarProceso');
    Route::get('proceso/{id}/Borrar','procesosController@actionBorrarProceso')->name('borrarProceso');    
    Route::get('proceso/excel'      ,'procesosController@exportCatProcesosExcel')->name('downloadprocesos');
    Route::get('proceso/pdf'        ,'procesosController@exportCatProcesosPdf')->name('catprocesosPDF');
    //Funciones de procesos
    Route::get('funcion/nuevo'      ,'funcionesController@actionNuevaFuncion')->name('nuevaFuncion');
    Route::post('funcion/nuevo/alta','funcionesController@actionAltaNuevaFuncion')->name('AltaNuevaFuncion');
    Route::get('funcion/ver/todos'  ,'funcionesController@actionVerFuncion')->name('verFuncion');
    Route::get('funcion/{id}/editar/funcion','funcionesController@actionEditarFuncion')->name('editarFuncion');
    Route::put('funcion/{id}/actualizar'    ,'funcionesController@actionActualizarFuncion')->name('actualizarFuncion');
    Route::get('funcion/{id}/Borrar','funcionesController@actionBorrarFuncion')->name('borrarFuncion');    
    Route::get('funcion/excel'      ,'funcionesController@exportCatFuncionesExcel')->name('downloadfunciones');
    Route::get('funcion/pdf'        ,'funcionesController@exportCatFuncionesPdf')->name('catfuncionesPDF');    
    //Actividades
    Route::get('actividad/nuevo'      ,'trxController@actionNuevaTrx')->name('nuevaTrx');
    Route::post('actividad/nuevo/alta','trxController@actionAltaNuevaTrx')->name('AltaNuevaTrx');
    Route::get('actividad/ver/todos'  ,'trxController@actionVerTrx')->name('verTrx');
    Route::get('actividad/{id}/editar/actividad','trxController@actionEditarTrx')->name('editarTrx');
    Route::put('actividad/{id}/actualizar'      ,'trxController@actionActualizarTrx')->name('actualizarTrx');
    Route::get('actividad/{id}/Borrar','trxController@actionBorrarTrx')->name('borrarTrx');    
    Route::get('actividad/excel'      ,'trxController@exportCatTrxExcel')->name('downloadtrx');
    Route::get('actividad/pdf'        ,'trxController@exportCatTrxPdf')->name('cattrxPDF');

    Route::get('numeralia/ver/graficaiBitacora' ,'trxController@Bitacora')->name('bitacora'); 

    //Bancos
    Route::get('bano/nuevo'      ,'bancosController@actionNuevoBanco')->name('nuevoBanco');
    Route::post('bano/nuevo/alta','bancosController@actionAltaNuevoBanco')->name('AltaNuevoBanco');
    Route::get('bano/ver/todos'  ,'bancosController@actionVerBancos')->name('verBancos');
    Route::get('bano/{id}/editar/banco','bancosController@actionEditarBanco')->name('editarBanco');
    Route::put('bano/{id}/actualizar'  ,'bancosController@actionActualizarBanco')->name('actualizarBanco');
    Route::get('bano/{id}/Borrar','bancosController@actionBorrarBanco')->name('borrarBanco');    
    Route::get('bano/excel'      ,'bancosController@exportCatBancosExcel')->name('downloadbancos');
    Route::get('bano/pdf'        ,'bancosController@exportCatBancosPdf')->name('catbancosPDF');    
    //Formas de pago
    Route::get('fpago/nuevo'      ,'formaspagoController@actionNuevoFormapago')->name('nuevoFormapago');
    Route::post('fpago/nuevo/alta','formaspagoController@actionAltaNuevoFormapago')->name('AltaNuevoFormapago');
    Route::get('fpago/ver/todos'  ,'formaspagoController@actionVerFormaspago')->name('verFormaspago');
    Route::get('fpago/{id}/editar/fpago','formaspagoController@actionEditarFormapago')->name('editarFormapago');
    Route::put('fpago/{id}/actualizar'  ,'formaspagoController@actionActualizarFormapago')->name('actualizarFormapago');
    Route::get('fpago/{id}/Borrar','formaspagoController@actionBorrarFormapago')->name('borrarFormapago');    
    Route::get('fpago/excel'      ,'formaspagoController@exportCatFormaspagoExcel')->name('downloadformaspago');
    Route::get('fpago/pdf'        ,'formaspagoController@exportCatFormaspagoPdf')->name('catformaspagoPDF');    
    //Tipos de crédito
    Route::get('tcred/nuevo'      ,'tiposcreditoController@actionNuevoTipocredito')->name('nuevoTipocredito');
    Route::post('tcred/nuevo/alta','tiposcreditoController@actionAltaNuevoTipocredito')->name('AltaNuevoTipocredito');
    Route::get('tcred/ver/todos'  ,'tiposcreditoController@actionVerTiposcredito')->name('verTiposcredito');
    Route::get('tcred/{id}/editar/tcred','tiposcreditoController@actionEditarTipocredito')->name('editarTipocredito');
    Route::put('tcred/{id}/actualizar'  ,'tiposcreditoController@actionActualizarTipocredito')->name('actualizarTipocredito');
    Route::get('tcred/{id}/Borrar','tiposcreditoController@actionBorrarTipocredito')->name('borrarTipocredito');    
    Route::get('tcred/excel'      ,'tiposcreditoController@exportCatTipocreditoExcel')->name('downloadtipocredito');
    Route::get('tcred/pdf'        ,'tiposcreditoController@exportCatTipocreditoPdf')->name('cattipocreditoPDF');    

    //Recursos humanos
    //Empleados
    Route::get('empleado/nuevo'           ,'empleadosController@actionNuevoEmpleado')->name('nuevoEmpleado');
    Route::post('empleado/nuevo/alta'     ,'empleadosController@actionAltaNuevoEmpleado')->name('AltaNuevoEmpleado');
    Route::get('empleado/ver/todas'       ,'empleadosController@actionVerEmpleados')->name('verEmpleados');
    Route::get('empleado/buscar/todas'    ,'empleadosController@actionBuscarEmpleado')->name('buscarEmpleado');    
    Route::get('empleado/{id}/editar/empl','empleadosController@actionEditarEmpleado')->name('editarEmpleado');
    Route::put('empleado/{id}/actualizar' ,'empleadosController@actionActualizarEmpleado')->name('actualizarEmpleado');
    Route::get('empleado/{id}/editar/cli1','empleadosController1@actionEditarEmpleado1')->name('editarEmpleado1');
    Route::put('empleado/{id}/actualizar1','empleadosController1@actionActualizarEmpleado1')->name('actualizarEmpleado1');        
    Route::get('empleado/{id}/Borrar'     ,'empleadosController@actionBorrarEmpleado')->name('borrarEmpleado');
    Route::get('empleado/excel'           ,'empleadosController@actionExportEmpleadosExcel')->name('ExportEmpleadosExcel');
    Route::get('empleado/pdf'             ,'empleadosController@actionExportEmpleadosPdf')->name('ExportEmpleadosPdf');

    //Ventas
    //Productos
    Route::get('producto/nuevo'           ,'productosController@actionNuevoProducto')->name('nuevoProducto');
    Route::post('producto/nuevo/alta'     ,'productosController@actionAltaNuevoProducto')->name('AltaNuevoProducto');
    Route::get('producto/ver/todos'       ,'productosController@actionVerProductos')->name('verProductos');
    Route::get('producto/buscar/todas'    ,'productosController@actionBuscarProducto')->name('buscarProducto');    
    Route::get('producto/{id}/editar/prod','productosController@actionEditarProducto')->name('editarProducto');
    Route::put('producto/{id}/actualizar' ,'productosController@actionActualizarProducto')->name('actualizarProducto');
    Route::get('producto/{id}/editar/pro1','productosController1@actionEditarProducto1')->name('editarProducto1');
    Route::put('producto/{id}/actualizar1','productosController1@actionActualizarProducto1')->name('actualizarProducto1');        
    Route::get('producto/{id}/Borrar'     ,'productosController@actionBorrarProducto')->name('borrarProducto');
    Route::get('producto/excel'           ,'productosController@actionExportProductosExcel')->name('ExportProductosExcel');
    Route::get('producto/pdf'             ,'productosController@actionExportProductosPdf')->name('ExportProductosPdf');    

    //Clientes
    Route::get('cliente/nuevo'           ,'clientesController@actionNuevoCliente')->name('nuevoCliente');
    Route::post('cliente/nuevo/alta'     ,'clientesController@actionAltaNuevoCliente')->name('AltaNuevoCliente');
    Route::get('cliente/ver/todas'       ,'clientesController@actionVerClientes')->name('verClientes');
    Route::get('cliente/buscar/todas'    ,'clientesController@actionBuscarCliente')->name('buscarCliente');    
    Route::get('cliente/{id}/editar/clie','clientesController@actionEditarCliente')->name('editarCliente');
    Route::put('cliente/{id}/actualizar' ,'clientesController@actionActualizarCliente')->name('actualizarCliente');
    Route::get('cliente/{id}/editar/cli1','clientesController1@actionEditarCliente1')->name('editarCliente1');
    Route::put('cliente/{id}/actualizar1','clientesController1@actionActualizarCliente1')->name('actualizarCliente1');        
    Route::get('cliente/{id}/Borrar'     ,'clientesController@actionBorrarCliente')->name('borrarCliente');
    Route::get('cliente/excel'           ,'clientesController@actionExportClientesExcel')->name('ExportClientesExcel');
    Route::get('cliente/pdf'             ,'clientesController@actionExportClientesPdf')->name('ExportClientesPdf');

    //Ventas
    Route::get('/vender',                 'VenderController@venderProducto')->name('venderProducto');
    Route::post('/productoDeVenta',       'VenderController@agregarProductoVenta')->name('agregarProductoVenta');
    Route::delete('/productoDeVenta',     'VenderController@quitarProductoDeVenta')->name('quitarProductoDeVenta');
    Route::post('/terminarOCancelarVenta','VenderController@terminarOCancelarVenta')->name('terminarOCancelarVenta');

    Route::get('/{id}/editar/ventacliente','VenderController@actionEditarventaCliente')->name('editarventaCliente');
    Route::put('/{id}/actualizar'         ,'VenderController@actionActualizarventaCliente')->name('actualizarventaCliente');

    //Ventas
    Route::get('facturar/nuevo'             ,'facturasController@actionNuevaFactura')->name('nuevaFactura');
    Route::post('facturar/nuevo/alta'       ,'facturasController@actionAltaNuevaFactura')->name('AltaNuevaFactura');
    Route::get('facturar/ver/todos'         ,'facturasController@actionVerFacturas')->name('verFacturas');
    Route::get('facturar/buscar/todos'      ,'facturasController@actionBuscarFactura')->name('buscarFactura');    
    Route::get('facturar/{id}/editar/factu' ,'facturasController@actionEditarFactura')->name('editarFactura');
    Route::put('facturar/{id}/actualizar'   ,'facturasController@actionActualizarFactura')->name('actualizarFactura');
    Route::get('facturar/{id}/{id2}/Borrar' ,'facturasController@actionBorrarFactura')->name('borrarFactura');
    Route::get('facturar/excel'             ,'facturasController@exportFacturasExcel')->name('facturasExcel');
    //Route::get('facturar/pdf'               ,'reciboController@exportReciboPdf')->name('reciboPdf');
    Route::get('numeralia/ver/filtrov1'   ,'facturasController@actionVentasxmes')->name('ventasxmes');    
    Route::post('numeralia/ver/graficav1' ,'facturasController@actionGraficaventasxmes')->name('graficaventasxmes');
    Route::get('numeralia/ver/filtrov2'   ,'facturasController@actionVentasxfpago')->name('ventasxfpago');        
    Route::post('numeralia/ver/graficav2' ,'facturasController@actionGraficaventaxfpago')->name('graficaventaxfpago');
    Route::get('numeralia/ver/filtrov3'   ,'facturasController@actionVentasxcli')->name('ventasxcli');    
    Route::post('numeralia/ver/graficav3' ,'facturasController@actionGraficaventasxcli')->name('graficaventasxcli');
    Route::get('numeralia/ver/filtrov4'   ,'facturasController@actionVentasxemp')->name('ventasxemp');        
    Route::post('numeralia/ver/graficav4' ,'facturasController@actionGraficaventaxemp')->name('graficaventaxemp');        
    Route::get('numeralia/ver/filtrov5'   ,'facturasController@actionVentasxmpio')->name('ventasxmpio');        
    Route::post('numeralia/ver/graficav5' ,'facturasController@actionGraficaventaxmpio')->name('graficaventaxmpio');    
    
    
    Route::get('facturaprods/{id}/{id2}/nuevoproducto','facturasController@actionNuevafactProducto')->name('nuevafactProducto');
    Route::post('facturaprods/nuevo/altaproducto'     ,'facturasController@actionAltaNuevafactProducto')->name('altanuevafactProducto');
    Route::get('facturaprods/{id}/{id2}/{id3}/editar/partida','facturasController@actionEditarfactProducto')->name('editarfactProducto');
    Route::put('facturaprods/{id}/{id2}/{id3}/actualizarpart','facturasController@actionActualizarfactProducto')->name('actualizarfactProducto');
    Route::get('facturaprods/{id}/{id2}/{id3}/Borrarpartida' ,'facturasController@actionBorrarfactProducto')->name('borrarfactProducto');
    Route::get('facturaprods/{id}/{id2}/ver/todaspartidas'   ,'facturasController@actionVerfactProductos')->name('verfactProductos');
    Route::get('facturaprods/pdf/{id}/{id2}/{id3}'           ,'facturasController@actionExportFacturaPdf')->name('ExportFacturaPdf');

    //Route::get('facturaprods/{id}/{id2}/{id3}/editar1/carga'   ,'reciboController@actionEditarCarga1')->name('editarCarga1');
    //Route::put('facturaprods/{id}/{id2}/{id3}/actualizarcarga1','reciboController@actionActualizarCarga1')->name('actualizarCarga1');


    //Crédito y cobranza
    //Aportaciones monetarias
    Route::get('aportaciones/nueva'            ,'aportacionesController@actionNuevaApor')->name('nuevaApor');
    Route::post('aportaciones/nueva/alta'      ,'aportacionesController@actionAltaNuevaApor')->name('AltaNuevaApor');
    Route::get('aportaciones/ver/todas'        ,'aportacionesController@actionVerApor')->name('verApor');
    Route::get('aportaciones/buscar/todas'     ,'aportacionesController@actionBuscarApor')->name('buscarApor');
    Route::get('aportaciones/{id}/editar/iaps' ,'aportacionesController@actionEditarApor')->name('editarApor');
    Route::put('aportaciones/{id}/actualizar'  ,'aportacionesController@actionActualizarApor')->name('actualizarApor');
    Route::get('aportaciones/{id}/editar/apor1','aportacionesController1@actionEditarApor1')->name('editarApor1');
    Route::put('aportaciones/{id}/actualizar1' ,'aportacionesController1@actionActualizarApor1')->name('actualizarApor1');    
    Route::get('aportaciones/{id}/Borrar'      ,'aportacionesController@actionBorrarApor')->name('borrarApor');
    //Route::get('aportaciones/excel'           ,'aportacionesController@exportAporExcel')->name('aporExcel');
    //Route::get('aportaciones/pdf'             ,'aportacionesController@exportAporPdf')->name('aporPDF');    

    Route::get('numeralia/ver/apfiltro1'   ,'aportacionesController@actionCobranzaxmes')->name('cobranzaxmes');    
    Route::post('numeralia/ver/apgrafica1' ,'aportacionesController@actionGraficacobranzaxmes')->name('graficacobranzaxmes');
    Route::get('numeralia/ver/apfiltro5'   ,'aportacionesController@actionCobranzaxmpio')->name('cobranzaxmpio');        
    Route::post('numeralia/ver/apgrafica5' ,'aportacionesController@actionGraficacobranzaxmpio')->name('graficacobranzaxmpio');    

    Route::get('cobranza/reporte/filtrarrepcob' ,'facturasController@actionCobranzaFacturas')->name('cobranzafacturas');
    Route::post('cobranza/reporte/generarrepcob','facturasController@actionVerCobranzaFacturas')->name('vercobranzafacturas');
    
    Route::get('placas/{id}/editar/placa1','placas1Controller@actionEditarPlaca1')->name('editarPlaca1');
    Route::put('placas/{id}/actualizar1'  ,'placas1Controller@actionActualizarPlaca1')->name('actualizarPlaca1'); 

    //Formatos de comprobación de combustible
    //Recibo de bitacora para descarga de combustible
    Route::get('recibo/nuevo'             ,'reciboController@actionNuevoRecibo')->name('nuevoRecibo');
    Route::post('recibo/nuevo/alta'       ,'reciboController@actionAltaNuevoRecibo')->name('AltaNuevoRecibo');
    Route::get('recibo/ver/todos'         ,'reciboController@actionVerRecibos')->name('verRecibos');
    Route::get('recibo/buscar/todos'      ,'reciboController@actionBuscarRecibo')->name('buscarRecibo');    
    Route::get('recibo/{id}/editar/recibo','reciboController@actionEditarRecibo')->name('editarRecibo');
    Route::put('recibo/{id}/actualizar'   ,'reciboController@actionActualizarRecibo')->name('actualizarRecibo');
    Route::get('recibo/{id}/Borrar'       ,'reciboController@actionBorrarRecibo')->name('borrarRecibo');
    Route::get('recibo/excel'             ,'reciboController@exportReciboExcel')->name('reciboExcel');
    //Route::get('recibo/pdf'               ,'reciboController@exportReciboPdf')->name('reciboPdf');
    
    Route::get('recibo/{id}/editar/recibo11','recibo11Controller@actionEditarRecibo11')->name('editarRecibo11');
    Route::put('recibo/{id}/actualizar11'   ,'recibo11Controller@actionActualizarRecibo11')->name('actualizarRecibo11');
    Route::get('recibo/{id}/editar/recibo21','recibo21Controller@actionEditarRecibo21')->name('editarRecibo21');
    Route::put('recibo/{id}/actualizar21'   ,'recibo21Controller@actionActualizarRecibo21')->name('actualizarRecibo21');

    Route::get('reciboc/{id}/{id2}/nuevac','reciboController@actionNuevaCarga')->name('nuevaCarga');
    Route::post('reciboc/nuevo/altac'     ,'reciboController@actionAltaNuevaCarga')->name('AltaNuevaCarga');
    Route::get('reciboc/{id}/{id2}/{id3}/editar/carga','reciboController@actionEditarCarga')->name('editarCarga');
    Route::put('reciboc/{id}/{id2}/{id3}/actualizarc' ,'reciboController@actionActualizarCarga')->name('actualizarCarga');
    Route::get('reciboc/{id}/{id2}/{id3}/Borrardt'    ,'reciboController@actionBorrarCarga')->name('borrarCarga');
    Route::get('reciboc/{id}/{id2}/ver/todasc'        ,'reciboController@actionVerCargas')->name('verCargas');
    Route::get('reciboc/pdf/{id}/{id2}/{id3}'   ,'reciboController@actionExportReciboPdf')->name('ExportReciboPdf');

    Route::get('reciboc/{id}/{id2}/{id3}/editar1/carga'   ,'reciboController@actionEditarCarga1')->name('editarCarga1');
    Route::put('reciboc/{id}/{id2}/{id3}/actualizarcarga1','reciboController@actionActualizarCarga1')->name('actualizarCarga1');

    //Bitacora de rendimiento de combustible
    Route::get('bitacora/nuevo'          ,'bitarendiController@actionNuevaBitarendi')->name('nuevaBitarendi');
    Route::post('bitacora/nuevo/alta'    ,'bitarendiController@actionAltaNuevaBitarendi')->name('AltaNuevaBitarendi');
    Route::get('bitacora/ver/todos'      ,'bitarendiController@actionVerBitarendi')->name('verBitarendi');
    Route::get('bitacora/buscar/todos'   ,'bitarendiController@actionBuscarBitarendi')->name('buscarBitarendi');    
    Route::get('bitacora/{id}/editar/bitacora','bitarendiController@actionEditarBitarendi')->name('editarBitarendi');
    Route::put('bitacora/{id}/actualizar','bitarendiController@actionActualizarBitarendi')->name('actualizarBitarendi');
    Route::get('bitacora/{id}/Borrar'    ,'bitarendiController@actionBorrarBitarendi')->name('borrarBitarendi');
    Route::get('bitacora/excel'          ,'bitarendiController@exportBitarendiExcel')->name('bitarendiExcel');
    //Route::get('bitacora/pdf'          ,'bitarendiController@exportReciboPdf')->name('reciboPdf');
    
    Route::get('bitacora/{id}/editar/bita1','bitarendiController@actionEditarBitarendi1')->name('editarBitarendi1');
    Route::put('bitacora/{id}/actualizar1' ,'bitarendiController@actionActualizarBitarendi1')->name('actualizarBitarendi1');
    Route::get('bitacora/{id}/editar/bita2','bitarendiController@actionEditarBitarendi2')->name('editarBitarendi2');
    Route::put('bitacora/{id}/actualizar2' ,'bitarendiController@actionActualizarBitarendi2')->name('actualizarBitarendi2');

    Route::get('bitacorse/{id}/nuevose'     ,'bitarendiController@actionNuevoServicio')->name('nuevoServicio');
    Route::post('bitacorase/nuevo/altase'   ,'bitarendiController@actionAltaNuevoServicio')->name('AltaNuevoServicio');
    Route::get('bitacorase/{id}/{id2}/{id3}/editar/servicio','bitarendiController@actionEditarServicio')->name('editarServicio');
    Route::put('bitacorase/{id}/{id2}/{id3}/actualizarse','bitarendiController@actionActualizarServicio')->name('actualizarServicio');
    Route::get('bitacorase/{id}/{id2}/{id3}/Borrarse','bitarendiController@actionBorrarServicio')->name('borrarServicio');
    Route::get('bitacorase/{id}/{id2}/ver/todosse'   ,'bitarendiController@actionVerServicios')->name('verServicios');
    Route::get('bitacorase/pdf/{id}/{id2}/{id3}'     ,'bitarendiController@actionExportBitarendiPdf')->name('ExportBitarendiPdf');

    //Numeralia
    Route::get('numeralia/ver/graficaxmarca'    ,'placasController@GplacasxMarca')->name('gplacasxmarca');
    Route::get('numeralia/ver/graficaxtgasto'   ,'placasController@GplacasxTipog')->name('gplacasxtipog');
    Route::get('numeralia/ver/graficaxtoper'    ,'placasController@GplacasxTipoo')->name('gplacasxtipoo');    
    Route::get('numeralia/ver/graficaxtoper2'   ,'placasController@GplacasxTipoo2')->name('gplacasxtipoo2'); 

    //Route::get('numeralia/ver/mapas'         ,'iapsController@Mapas')->name('verMapas');        
    //Route::get('numeralia/ver/mapas2'        ,'iapsController@Mapas2')->name('verMapas2');        
    //Route::get('numeralia/ver/mapas3'        ,'iapsController@Mapas3')->name('verMapas3');    

    Route::get('Calendario/ver'         ,'calendarioController@actionVerCalendario')->name('vercalendario');
    Route::get('Calendario/evento/{mes}','calendarioController@actionVerCalendariomes')->name('vercalendariomes');

    Route::get('Calendario/evento'           ,'calendarioController@actionCalendario')->name('calendario');
    Route::get('Calendario/nuevoeevento'     ,'calendarioController@actionNuevaCita')->name('nuevaCita');
    Route::post('Calendario/nuevo/altaevento','calendarioController@actionAltaCita')->name('altaCita');
    Route::get('Calendario/editar/{id}'      ,'calendarioController@actionEditarCita')->name('editarcita');
    Route::put('Calendario/actualizar/{id}'  ,'calendarioController@actionActualizarCita')->name('actualizarCita');
    Route::get('Calendario/pdf'              ,'calendarioController@actionCalendarioPdf')->name('calendarioPdf');

});

