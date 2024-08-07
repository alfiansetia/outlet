<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, ValidatesRequests;

    public function not_found(string $message = 'Data Not Found!')
    {
        return response()->json(['message' => $message], 404);
    }

    public function server_eror(string $message = 'Server Error!')
    {
        return response()->json(['message' => $message], 500);
    }

    public function unauthorize(string $message = 'Unauthorize!')
    {
        return response()->json(['message' => $message], 403);
    }

    public function response(mixed $data, string $message = '', int $code = 200)
    {
        return response()->json([
            'message' => $message,
            'data' => $data
        ], $code);
    }
}
