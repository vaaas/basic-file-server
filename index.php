<?php

require_once 'lib/response.php';
require_once 'lib/request.php';

function generateRandomString(int $length = 10): string {
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++)
        $randomString .= $characters[rand(0, $charactersLength - 1)];
    return $randomString;
}

function route(Request $request): Response {
    try {
        if ($request->method !== 'GET') return new MethodNotAllowed();
        if (!authenticate($request)) return new Unauthorized();
        if ($request->url === '/') return new Response(200, ['Content-Type' => 'text/plain' ], 'You are authenticated');
        $pathname = 'conf' . $request->url;
        if (!file_exists($pathname)) return new NotFound();
        if (is_dir($pathname)) return handle_directory($pathname);
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

function handle_directory(string $x): Response {
    $name = './tars/' . generateRandomString() . '.tar';
    exec("tar cf {$name} -C {$x} .");
    return new EphemeralBlobResponse($name);
}

function main() {
    $request = new Request();
    $response = route($request);
    $response->serve();
}

main();
