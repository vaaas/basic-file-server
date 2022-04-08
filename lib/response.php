<?php

class Response {
    function __construct(int $status, array $headers, string $body) {
        $this->status = $status;
        $this->headers = $headers;
        $this->body = $body;
    }

    function serve(): void {
        http_response_code($this->status);
        foreach ($this->headers as $k=>$v) header($k . ': ' . $v);
        echo $this->body;
    }
}

class JSONResponse extends Response {
    function __construct(object|array $x, int $status=200) {
        parent::__construct($status, ['Content-Type' => 'application/json'], json_encode($x));
    }
}

class BlobResponse extends Response {
    function __construct(string $pathname, int $status=200) {
        parent::__construct($status, ['Content-Type' => 'application/octet-stream'], $pathname);
    }

    function serve(): void {
        $chunk_size = 1024*4;
        $handle = fopen($this->body, 'rb');

        http_response_code($this->status);
        foreach ($this->headers as $k=>$v) header($k . ': ' . $v);

        while (!feof($handle)) {
            echo fread($handle, $chunk_size);
            ob_flush();
            flush();
        }

        fclose($handle);
    }
}

class EphemeralBlobResponse extends BlobResponse {
    function serve(): void {
        parent::serve();
        unlink($this->body);
    }
}

class Unauthorized extends Response {
    function __construct(string $message='Unauthorized') {
        parent::__construct(401, ['Content-type' => 'text/plain'], $message);
    }
}

class NotFound extends Response {
    function __construct(string $message='Not Found') {
        parent::__construct(404, ['Content-Type' => 'text/plain'], $message);
    }
}

class MethodNotAllowed extends Response {
    function __construct(string $message='Method Not Allowed') {
        parent::__construct(405, ['Content-Type' => 'text/plain'], $message);
    }
}

class InternalServerError extends Response {
    function __construct(string $message='Internal Server Error') {
        parent::__construct(500, ['Content-Type' => 'text/plain'], $message);
    }
}
