<?php

use Illuminate\Support\Facades\Log;
use Symfony\Component\HttpFoundation\Response;

function getSuccessMessage($data)
{
    return response([
        'code' => Response::HTTP_OK,
        'message'   => "Berhasil",
        'data'      => $data,
    ], Response::HTTP_OK);
}

function getThrowMessage($th)
{
    Log::error($th);
    $thCode = $th->getCode() ?? 500;
    $thCode = $thCode > 500 || $thCode < 100 ? 500 : $thCode;
    $message = $th->getMessage() ?? 'Terjadi kesalahan';
    $message = env('APP_DEBUG') ? $message : 'Terjadi kesalahan';

    return response()->json([
        'code'    => $thCode,
        'message' => $message,
        'data' => null,
    ], $thCode);
}

function getValidatedMessage($validator)
{
    $errorMessages = $validator->getMessageBag()->first();
    $errorCount = $validator->getMessageBag()->count();
    if($errorCount - 1){
        $errorMessages = $errorMessages . ' (and ' . ($errorCount - 1) . ' more error)';
    }
    return response()->json([
        'code'    => Response::HTTP_UNPROCESSABLE_ENTITY,
        'message' => $errorMessages,
        'data'    => ['errors' => $validator->errors()],
    ], Response::HTTP_UNPROCESSABLE_ENTITY);
}
