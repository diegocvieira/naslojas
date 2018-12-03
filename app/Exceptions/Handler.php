<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Mail;
use Illuminate\Auth\AuthenticationException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Report or log an exception.
     *
     * @param  \Exception  $exception
     * @return void
     */
    public function report(Exception $exception)
    {
        /*if(!($exception instanceof HttpException) && !($exception instanceof AuthenticationException)) {
            $error['message'] = $exception->getMessage();
            $error['file'] = $exception->getFile();
            $error['line'] = $exception->getLine();

            Mail::send('emails.phperror', ['error' => $error], function($message) {
                $message->to('dvdiegovieiradv@gmail.com')->subject('GENERAL ERROR: - ' . date('d/m/Y').' ' . date('H:i').'h');
            });
        }*/

        parent::report($exception);
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Exception  $exception
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $exception)
    {
        return parent::render($request, $exception);
    }

    protected function unauthenticated($request, AuthenticationException $exception)
    {
        if($request->expectsJson()) {
            return response()->json(['error' => 'Unauthenticated.'], 401);
        }

        // Check request and redirect to appropriate login route
        if ($request->is('loja/admin/*')) {
            return redirect()->guest('loja/login');
        } else if ($request->is('cliente/admin/*')) {
            return redirect()->guest('cliente/login');
        } else {
            return redirect()->guest('/');
        }
    }
}
