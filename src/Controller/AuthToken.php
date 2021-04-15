<?php


namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\JsonResponse;

/**
 * Class AuthToken
 *
 * Endpoint for token provider
 */
class AuthToken
{
    public const TOKEN = "MpWUMGLUeg6FQQr6CHi7S8n9tfapY2bc";

    /**
     * @Route("/auth/token")
     */
    public function execute()
    {
        return new JsonResponse(['token' => self::TOKEN]);
    }
}