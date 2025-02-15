<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class ApiException extends Exception
{
    public function render(Request $request): Response
    {
        return response()->json(data: [
            'error' => $this->getMessage(),
        ], status: 400);
    }
}
