<?php

require_once 'lib/response.php';
require_once 'lib/request.php';
$conf = require('./conf.php');

function route(Request $request): Response {
    try {
        if ($request->method !== 'GET') return new MethodNotAllowed();
        if (!authenticate($request)) return new Unauthenticated();
        if ($request->url === '/') return new Response(200, ['Content-Type' => 'text/plain' ], 'You are authenticated');
        $pathname = 'conf' . $request->url;
        if (!file_exists($pathname)) return new NotFound();
        if (is_dir($pathname)) return handle_directory($pathname);
        else return handle_file($pathname);
    } catch (\Throwable $e) { return handle_error($e); }
}

function authenticate(Request $request): bool {
    global $conf;
    $x = $request->header('Authorization');
    if (!$x) return false;
    if (strlen($x) < 7) return false;
    $x = base64_decode(substr($x, 6), true);
    if (!$x) return false;
    $x = substr($x, 0, -1);
    return in_array($x, $conf['auth']['tokens']);
}

function handle_error(\Throwable $e): Response {
    error_log($e->getMessage() . "\n" .$e->getTraceAsString());
    return new InternalServerError($e->getMessage());
}

function handle_file(string $x): Response {
    return new JSONResponse(["Handling a file"], 400);
}

function handle_directory(string $x): Response {
    return new JSONResponse(["Can't handle directories yet"], 400);
}

function main() {
    $request = new Request();
    $response = route($request);
    $response->serve();
}

main();