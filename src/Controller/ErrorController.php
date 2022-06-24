<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\BadRequestHttpException;

class ErrorController
{

  public function show(\Throwable $exception): Response
  {
    if ($exception instanceof NotFoundHttpException) {
      return new JsonResponse([
        "error" => "not found"
      ], 404);
    }
    if ($exception instanceof BadRequestHttpException) {
      return new JsonResponse([
        "error" => $exception->getMessage()
      ], 400);
    }
    
    return new JsonResponse([
      "error" => $exception->getMessage(),
      $exception->getLine(),
      $exception->getTrace(),
    ], 500);
  }
}
