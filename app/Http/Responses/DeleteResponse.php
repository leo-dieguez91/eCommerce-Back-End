<?php

namespace App\Http\Responses;

use Illuminate\Contracts\Support\Responsable;

class DeleteResponse implements Responsable
{
    public function toResponse($request)
    {
        return response()->noContent();
    }
} 