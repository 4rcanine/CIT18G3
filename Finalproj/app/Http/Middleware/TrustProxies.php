<?php

namespace App\Http\Middleware;

// Make sure to import the base class and the Request class
use Fideloper\Proxy\TrustProxies as Middleware; // Use this if Laravel < 5.6 or you installed Fideloper manually
// OR for modern Laravel (check your composer.json if unsure, but this is likely):
// use Illuminate\Http\Middleware\TrustProxies as Middleware;
use Illuminate\Http\Request;

class TrustProxies extends Middleware
{
    /**
     * The trusted proxies for this application.
     *
     * If you are using Codespaces or a similar proxy,
     * setting this to '*' is often the easiest solution.
     *
     * @var array<int, string>|string|null
     */
    protected $proxies = '*'; // <--- Trust all proxies

    /**
     * The headers that should be used to detect proxies.
     *
     * Usually you want to trust the standard forwarded headers.
     * HEADER_X_FORWARDED_ALL includes FOR, HOST, PORT, PROTO, PREFIX
     *
     * @var int
     */
    protected $headers = Request::HEADER_X_FORWARDED_ALL;
      // Or be more specific if needed:
      // protected $headers =
      //     Request::HEADER_X_FORWARDED_FOR |
      //     Request::HEADER_X_FORWARDED_HOST |
      //     Request::HEADER_X_FORWARDED_PORT |
      //     Request::HEADER_X_FORWARDED_PROTO |
      //     Request::HEADER_X_FORWARDED_AWS_ELB;
}