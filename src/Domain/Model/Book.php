<?php

namespace App\Domain\Model;

readonly class Book
{
    public function __construct(
        private string $title,
        private string $author,
        private string $year,
        private string $idNumber
)
{
}

public
function getTitle(): string
{
    return $this->title;
}

public
function getAuthor(): string
{
    return $this->author;
}

public
function getYear(): string
{
    return $this->year;
}

public
function getIdNumber(): string
{
    return $this->idNumber;
}
}