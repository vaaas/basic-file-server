<?php

class Request {
    function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = $_SERVER['REQUEST_URI'];
        $this->headers = getallheaders();
    }

    public function header(string $x): ?string {
        return array_key_exists($x, $this->headers)
            ? $this->headers[$x]
            : null;
    }
}
