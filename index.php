<?php

require_once 'lib/response.php';
require_once 'lib/request.php';

function route(Request $request): Response {
    try {
        if ($request->method !== 'GET') return new MethodNotAllowed();
        if (!authenticate($request)) return new Unauthorized();
        $pathname = getenv('ROOT') . $request->url;
        if (!file_exists($pathname)) return new NotFound();
        if (is_dir($pathname)) return handle_directory($request, $pathname);
        else return new BlobResponse($pathname);
    } catch (\Throwable $e) { return handle_error($e); }
}

function authenticate(Request $request): bool {
    $x = $request->header('Authorization');
    if (!$x) return false;
    if (strlen($x) < 7) return false;
    $x = base64_decode(substr($x, 6), true);
    if (!$x) return false;
    $x = substr($x, 0, -1);
    return $x === getenv('TOKEN');
}

function handle_error(\Throwable $e): Response {
    error_log($e->getMessage() . "\n" .$e->getTraceAsString());
    return new InternalServerError($e->getMessage());
}

function handle_directory(Request $request, string $x): Response {
    if ($request->has('tar'))
        return new PassthruBlobResponse("tar c -C {$x} .");
    else
        return new JSONResponse(scandir($x));
}

function main() {
    $request = new Request();
    $response = route($request);
    $response->serve();
}

main();
