<?php
declare(strict_types=1);

namespace \Domain\ValueObject\ISBN.php;

class ISBN
{
private string $value;

public function __construct(string $value)
{
if (!$this->isValidISBN($value)) {
throw new InvalidArgumentException("Invalid ISBN value");
}
$this->value = $value;
}

private function isValidISBN(string $isbn): bool
{
    // TODO: aplicar la lógica
return true;
}

public function __toString(): string
{
return $this->value;
}
}
