<?php
declare(strict_types=1);

namespace App\Controller;

class Response
{

    public function __construct(private readonly string $body, private readonly int $statusCode = 200, private readonly array $headers = []) {}

    public function send(): void
    {
        foreach ($this->headers as $name => $value) {
            header("$name: $value");
        }
        http_response_code($this->statusCode);
        echo $this->body;
    }

    public function body(): string
    {
        return $this->body;
    }

    public function statusCode(): int
    {
        return $this->statusCode;
    }

    public function headers(): array
    {
        return $this->headers;
    }
}