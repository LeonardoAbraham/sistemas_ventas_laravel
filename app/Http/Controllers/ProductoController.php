<?php

namespace App\Http\Controllers;

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

        return $productos;
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        $producto = Producto::with(['user:id,email,name'])->find($id);
        return $producto;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
        //delete from producto where id = $id
        Producto::destroy($id);
        return \response()->json(['res' => true, 'message'=>'eliminado correctamente']);
    }
}
