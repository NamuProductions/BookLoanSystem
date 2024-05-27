<?php

namespace App\Action\Admin;


class AddNewBookAction
{

    public function __construct($bookRepository)
    {
    }

    public function __invoke(string $title, string $author, string $year, string $idNumber)
    {
    }
}