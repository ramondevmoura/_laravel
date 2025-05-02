<?php

namespace App\Http\Middleware;

use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken as Middleware;

class CustomVerifyCsrfToken extends Middleware
{
    /**
     * URIs que não precisam de verificação CSRF.
     *
     * @var array<int, string>
     */
    protected $except = [
        '/send-via-backend',
        '/enviar-mensagem',
        '/mensagens'
    ];
}
