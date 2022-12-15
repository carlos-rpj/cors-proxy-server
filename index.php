<?php
define('API_URL', 'https://example.com/api');
define('BASE_PATH', ''); // The proxy path if is not in the root directory.

require 'vendor/autoload.php';

use Proxy\Proxy;
use Proxy\Adapter\Guzzle\GuzzleAdapter;
use Proxy\Filter\RemoveEncodingFilter;
use Laminas\Diactoros\ServerRequestFactory;
use Laminas\Diactoros\ServerRequest;

$request = ServerRequestFactory::fromGlobals();
$guzzle = new GuzzleHttp\Client();
$proxy = new Proxy(new GuzzleAdapter($guzzle));
$proxy->filter(new RemoveEncodingFilter());

try {
  // Forward the request and get the response.
  $response = $proxy
    ->forward($request)
    ->filter(function (ServerRequest $request, $response, $next) {

      $uri = $request->getUri();
      $path = str_replace(BASE_PATH, '', $uri->getPath());

      $request = $request
        // ->withHeader('Authorization', 'AUTHORIZATION_KEY')
        // ->withHeader('Another-Header', 'header-value')
        ->withUri($uri->withPath($path));
      
      // Call the next item in the middleware.
      $response = $next($request, $response);

      // Manipulate the response object.
      $response = $response
        ->withHeader('Access-Control-Allow-Origin', '*')
        ->withHeader('Access-Control-Allow-Methods', '*')
        ->withHeader('Access-Control-Allow-Methods', '*');

      return $response;
    })
    ->to(API_URL);

  // Output response to the browser.
  (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($response);
} catch(\GuzzleHttp\Exception\BadResponseException $e) {
  // Correct way to handle bad responses
  (new Laminas\HttpHandlerRunner\Emitter\SapiEmitter)->emit($e->getResponse());
}