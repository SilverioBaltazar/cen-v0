<?php
/*

 RED DE PRODUCTOS Y SERVICIOS               _ _           _       
    
    Blog:       https://
    Ayuda:      https://
    Contacto:   https://
    
    Copyright (c) 2020 Ing. Silverio Baltazar Barrientos Zarate
    Licenciado bajo la licencia MIT
    
    El texto de arriba debe ser incluido en cualquier redistribucion
*/ ?>
<?php






namespace App\Http\Controllers;

//use App\ProductoModel;
use App\ProductoModel; 
use Illuminate\Http\Request;

class ProductosController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    /**
    public function actionverBancos(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        $regbancos = regBancoModel::select('BANCO_ID','BANCO_DESC', 'BANCO_STATUS','BANCO_FECREG')
            ->orderBy('BANCO_ID','ASC')
            ->paginate(30);
        if($regbancos->count() <= 0){
            toastr()->error('No existen registros de Bancos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoRubro');
        }
        return view('sicinar.bancos.verBancos',compact('nombre','usuario','regbancos'));

    }

    **/
    public function index(){
        $nombre       = session()->get('userlog');
        $pass         = session()->get('passlog');
        if($nombre == NULL AND $pass == NULL){
            return view('sicinar.login.expirada');
        }
        $usuario      = session()->get('usuario');
        $rango        = session()->get('rango');
        $dep          = session()->get('dep');        
        $ip           = session()->get('ip');

        //$productos = ProductoModel::select('codigo_barras', 'descripcion', 'precio_compra', 'precio_venta', 'existencia')
        $productos = ProductoModel::all();
            //->orderBy('codigo_barras','ASC')
            //->paginate(30);
        if($productos->count() <= 0){
            toastr()->error('No existen productos.','Lo siento!',['positionClass' => 'toast-bottom-right']);
            //return redirect()->route('nuevoRubro');
        }
        return view('sicinar.productos.verProductos',compact('nombre','usuario','productos'));
    }

    /**public function index()
    {
        return view("sicinar.productos.productos_index", ["productos" => ProductoModel::all()]);
    }
    **/

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view("sicinar.productos.productos_create");
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $producto = new ProductoModel($request->input());
        $producto->saveOrFail();
        return redirect()->route("sicinar.productos.index")->with("mensaje", "Producto guardado");
    }

    /**
     * Display the specified resource.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        return view("sicinar.productos.productos_edit", ["producto" => $producto,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param \Illuminate\Http\Request $request
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        $producto->fill($request->input());
        $producto->saveOrFail();
        return redirect()->route("sicinar.productos.index")->with("mensaje", "Producto actualizado");
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param \App\Producto $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        $producto->delete();
        return redirect()->route("sicinar.productos.index")->with("mensaje", "Producto eliminado");
    }
}
