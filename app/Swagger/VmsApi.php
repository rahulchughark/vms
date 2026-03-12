<?php

namespace App\Swagger;

/**
 * @OA\Info(
 *     title="VMS API",
 *     version="1.0.0",
 *     description="Internal Visitor Management System API"
 * )
 *
 * @OA\SecurityScheme(
 *     securityScheme="bearerAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT"
 * )
 */
class VmsApi {}
