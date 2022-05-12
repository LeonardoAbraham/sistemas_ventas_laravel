<?php

namespace App\Exceptions;


use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Auth\AuthenticationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array<int, class-string<Throwable>>
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        $this->renderable(function (ModelNotFoundException $e, $request){
            return response()->json(["res" => false, "error" => "Error de modelo"], 400);
        });

        $this->renderable(function ( HttpException $e, $request ) {
            return response()->json(['res' => false, "error"=>"Error de rutas"], 404);
        });

        $this->renderable(function (QueryException $e, $request){
            return response()->json(["res"=>false, "error"=>"Error de consulta BDD"], 500);
        });

        $this->renderable(function (AuthenticationException $e, $request){
            return response()->json(['res'=>false, "error"=>"Error de autenticación"], 401);
        });

        $this->renderable(function(AuthorizationException $e, $request){
            return response()->json(['res'=>false, "error"=>"Error de autorización, no tiene permisos."], 403);
        });

        $this->renderable(function(RouteNotFoundException $e, $request){
            return response()->json(['res'=>false, "error"=>"Error de ruta"], 404);
        });
    }
}
