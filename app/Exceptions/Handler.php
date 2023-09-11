<?php
namespace App\Exceptions;

use App\Models\SystemError;
use ErrorException;
use Exception;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Database\QueryException;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Illuminate\Session\TokenMismatchException;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Laravel\Socialite\Two\InvalidStateException;
use PayPal\Exception\PayPalConnectionException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class Handler extends ExceptionHandler
{

    /**
     * A list of the exception types that should not be reported.
     *
     * @var array
     */
    protected $dontReport = [
        '\Illuminate\Session\TokenMismatchException',
        'Symfony\Component\HttpKernel\Exception\HttpException'
    ];

    /**
     * Report or log an exception.
     *
     * This is a great spot to send exceptions to Sentry, Bugsnag, etc.
     *
     * @param \Exception $e
     * @return void
     */
    public function report(Exception $e)
    {
        // Custom DB logging
        if (($e instanceof NotFoundHttpException) || ($e instanceof TokenMismatchException) || ($e instanceof InvalidStateException)) {
            parent::report($e);
        } else {
            $trace = $e->getTrace();
            $trace = array_pop($trace);
            $trace = ! empty($trace['args']) ? $trace['args'] : null;
            if ($e instanceof QueryException) {
                $message = 'DB error: ' . $e->getMessage() ?:
                    str_replace(base_path(), '', $e->getFile()) . ' (' . $e->getLine() . ')';
            } elseif (is_null($trace)) {
                $message = $e->getMessage() ?:
                    str_replace(base_path(), '', $e->getFile()) . ' (' . $e->getLine() . ')';
            } elseif ($e instanceof ErrorException) {
                $message = 'Fatal error: ' . $e->getMessage() ?:
                    str_replace(base_path(), '', $e->getFile()) . ' (' . $e->getLine() . ')';
            } elseif ($e instanceof PayPalConnectionException) {
                $message = 'PayPal exception: ' . $e->getMessage();
            } else {
                //$message = 'URL: ' . $trace[0]->url() . ' Error: ';
                $message = $e->getMessage() ?:
                    str_replace(base_path(), '', $e->getFile()) . ' (' . $e->getLine() . ')';
            }

            $systemError = new SystemError();
            $systemError->user_id = Auth::check() ? Auth::getUser()->id : 0;
            $systemError->error = $message;
            if ($e instanceof PayPalConnectionException) {
                $systemError->stack_trace = json_decode($e->getData(), true);
            } else {
                $systemError->stack_trace = $e->getTraceAsString();
            }
            $systemError->ip_address = inet_pton(Request::getClientIp());
            $systemError->save();

            // PLUS keep file logging

            parent::report($e);
        }
    }

    /**
     * Render an exception into an HTTP response.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Exception               $e
     * @return \Illuminate\Http\Response
     */
    public function render($request, Exception $e)
    {
        if ($this->isHttpException($e)) {
            return $this->renderHttpException($e);
        } elseif (($e instanceof TokenMismatchException) || ($e instanceof InvalidStateException)) {
            return Redirect::to('/')
                ->withErrors('Session time has expired. Try again.')
                ->withInput($request->except('_token'));
        } elseif (($e instanceof ModelNotFoundException) || ($e instanceof ErrorException)) {
            return response()->view("errors.404", [], 404);
        } elseif ($e instanceof PayPalConnectionException) {
            return redirect()
                ->route('cart')
                ->with('errorPay', 'Error');
        } elseif ($e instanceof QueryException) {
            return response()->view("errors.db");
        } elseif ($e instanceof InvalidStateException) {
            return response()->view("errors.social");
        } else {
            return response()->view("errors.404", [], 404);
        }

        return parent::render($request, $e);
    }

}