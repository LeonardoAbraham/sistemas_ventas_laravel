<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use App\Models\Producto;
use Illuminate\Http\Request;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    //listar y buscar dentro de una tabla: Producto
    public function index( Request $request )
    {
        //select * from producto(ORM, Laravel ... ELOQUENT)
        //$productos = Producto::all(); //trae todos los datos de un producto

        //personalizado cuando queremos traer información que no es propia de la tabla Producto, en este caso se agrega la informacion del usuario.
        //$productos = Producto::with(['user:id,email,name'])->paginate(10);


        //select * from producto where nombre like '%par%'
        $productos = Producto::with(['user:id,email,name'])
                                ->whereCodigo($request->txtBuscar) //->where('codigo', '=', $request->txtBuscar)
                                ->orWhere('nombre', 'like', "%{$request->txtBuscar}%")
                                ->paginate(5);

        //return $productos;
        return \response()->json($productos,200);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    //insertar nuevos registros
    public function store(CreateProductoRequest $request)
    {
        //insert into productos values(.....$request)
        $input = $request->all();
        $input['user_id'] = 1; //usuario autenticado
        $producto = Producto::create($input);
        return \response()->json(['res' => true, 'message'=>'insertado correctamente'], 200);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

     //recoger un solo registro de la base de datos
    public function show($id)
    {
        //select * from prducto where id=$id
        $producto = Producto::with(['user:id,email,name'])->findOrFail($id);
        //return $producto;
        return \response()->json($producto,200);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // para modificar registros
    public function update(UpdateProductoRequest $request, $id)
    {
        //update productos set nombre = $request ..... where id = $id
        $input = $request->all();
        $producto = Producto::find($id);
        $producto->update($input);

        return \response()->json(['res' => true, 'message'=>'modificado correctamente', 200]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    //metodo para eliminar registros
    public function destroy($id)
    {
        try {
            //delete from producto where id = $id
            Producto::destroy($id);
            return \response()->json(['res' => true, 'message'=>'eliminado correctamente'], 200);
        } catch (\Exception $e) {
            return \response()->json(['res' => false, 'message'=> $e->getMessage()], 200);
        }


    }

    //incrementar likes de productos
    public function setLike($id){
        $producto = Producto::find($id);
        $producto->like = $producto->like + 1;
        $producto->save();

        return \response()->json(['res' => true, 'message'=>'mas un like'], 200);
    }

    public function setDislike($id){
        $producto = Producto::find($id);
        $producto->dislike = $producto->dislike + 1;
        $producto->save();

        return \response()->json(['res' => true, 'message'=>'mas un dislike'], 200);
    }

    public function setImagen(Request $request, $id){
        $producto = Producto::find($id);
        $producto->url_imagen = $this->cargarImagen($request->imagen, $id);
        $producto->save();

        return \response()->json(['res' => true, 'message'=>'imagen cargada correctamente'], 200);
    }

    private function cargarImagen($file, $id){
        $nombreArchivo = time() . "_{$id}." . $file->getClientOriginalExtension();
        $file->move(\public_path('imagenes'), $nombreArchivo);
        return $nombreArchivo;
    }
}
