<?php

namespace App\Services\Contracts;

interface NewsApiServiceInterface {
    public function fetchNewsFromAPI(): void;
}