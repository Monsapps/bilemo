<?php

namespace App\Controller;

use FOS\RestBundle\Controller\Annotations as Rest;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;

class LoginController
{
    /**
     * @Rest\Post("/login", name="api_login_check")
     * @OA\Response(
     *      response=200,
     *      description="Loging successfully.",
     *      content={
     *          @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      type="object",
     *                      @OA\Property(
     *                          property="token",
     *                          description="JWT token, reusable until its ttl has expired (3600 seconds by default).",
     *                          type="string"
     *                      )
     *                  )
     *         )
     *      }
     * )
     * 
     * @OA\Response(
     *      response=401,
     *      description="Invalid credential."
     * )
     * 
     * @OA\RequestBody(
     *       description="Input data format",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      type="object",
     *                      @OA\Property(
     *                          property="username",
     *                          description="Username.",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          description="Password.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * @OA\Tag(name="Authentication")
     * @Security(name=null)
     */
    public function login()
    {
    }
}
