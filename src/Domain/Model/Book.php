<?php

namespace App\Domain\Model;

readonly class Book
{
    protected string $title;
    protected string $author;
    protected string $year;
    protected string $idNumber;

    public function construct(

) {}

    public function getTitle(): string
    {
        return $this->title;
    }
    public function getAuthor(): string
    {
        return $this->author;
    }
    public function getYear(): string
    {
        return $this->year;
    }
    public function getIdNumber(): string
    {
        return $this->idNumber;
    }
}