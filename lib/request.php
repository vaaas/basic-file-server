<?php

class Request {
    function __construct() {
        $this->method = $_SERVER['REQUEST_METHOD'];
        $this->url = array_key_exists('PATH_INFO', $_SERVER) ? $_SERVER['PATH_INFO'] : '/';
        $this->headers = getallheaders();
        $this->query = $_GET;
    }

    public function header(string $x): ?string {
        return array_key_exists($x, $this->headers)
            ? $this->headers[$x]
            : null;
    }

    public function get(string $x): ?string {
        return array_key_exists($x, $this->query)
            ? $this->query[$x]
            : null;
    }

    public function has(string $x): bool {
        return array_key_exists($x, $this->query);
    }
}
