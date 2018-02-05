<?php

namespace App\Middleware;
use Illuminate\Support\Str;

class VerifyCsrfToken
{
    public static function getInstance($request,$response){
        $method = $request->getMethod();
        if ( !in_array($method, ['HEAD', 'GET', 'OPTIONS'])) {
            $token = $request->getRequestParam('_token');
            if ( !$token) {
                $response->writeJson(500, 'The token is miss!', 'fail');
                $response->end();
            }
            if ( !hash_equals($token, $request->session()->get('_token'))) {
                $response->writeJson(500, 'The token is verified fail!', 'fail');
                $response->end();
            }
        } else {
            $session_token = $request->session()->get('_token');
            if ( !$session_token) {
                $response->session()->set('_token', Str::random(40));
            }
        }
    }
}