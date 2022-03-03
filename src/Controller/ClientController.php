<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\CacheService;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ClientController extends AbstractController
{
    /**
     * @Rest\Get("/clients", name="client_list")
     * 
     * @Rest\QueryParam(
     *      name="keyword",
     *      requirements="[a-zA-Z0-9]+",
     *      nullable=true,
     *      description="The keyword search for."
     * )
     * 
     * @Rest\QueryParam(
     *      name="order",
     *      requirements="(asc|desc)",
     *      default="asc",
     *      description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      default="15",
     *      description="Max users per page."
     * )
     * @Rest\QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      default="1",
     *      description="The page number."
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Get the list of all clients.",
     *      @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class))
     *     )
     * )
     * @OA\Tag(name="Clients")
     */
    public function clientList(ParamFetcherInterface $paramFetch, UserService $userService, CacheService $cache): Response
    {

        $users = $userService->getUserList($paramFetch, $this->getUser());

        $this->denyAccessUnlessGranted('get_client', $users);

        return $cache->getResponse($users);
    }

    /**
     * @Rest\Get("/clients/{id}", name="client_details")
     * 
     * @OA\Response(
     *      response=200,
     *      description="Get client details."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when client not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * @OA\Tag(name="Clients")
     */
    public function clientDetails(User $user, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('get_client_details', $user);
        return $cache->getResponse($user, ['ClientView']);
    }

    /**
     * @Rest\Post("/clients", name="client_post")
     * 
     * @OA\Response(
     *      response=201,
     *      description="Client added successfully."
     * )
     * 
     * @OA\Response(
     *      response=400,
     *      description="Required field not filled."
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
     *                          property="email",
     *                          description="Client email.",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          description="Client password.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * 
     * @OA\Tag(name="Clients")
     */
    public function clientPost(UserService $userService, Request $request, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('post_client', new User());

        $data = json_decode($request->getContent(), true);

        $user = $userService->addUser($data, $this->getUser(), ['ROLE_CLIENT']);

        return $cache->getResponse(
            $user,
            ['ClientView'],
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'client_details',
                    [
                        'id' => $user->getId(),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    ])
            ]);
    }

    /**
     * @Rest\Patch("/clients/{id}", name="client_patch")
     * 
     * @OA\Response(
     *      response=202,
     *      description="Client updated successfully."
     * )
     * 
     * @OA\Response(
     *      response=400,
     *      description="Required field not filled."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when client not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\RequestBody(
     *       description="Input data format",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      type="object",
     *                      @OA\Property(
     *                          property="username",
     *                          description="Updated username.",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          description="Updated client email.",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          description="Updated client password.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * @OA\Tag(name="Clients")
     */
    public function clientPatch(User $user, Request $request, UserService $userService, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('patch_client', $user);

        $data = json_decode($request->getContent(), true);

        $user = $userService->editUser($user, $data);

        return $cache->getResponse(
            $user,
            ['Details'],
            Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/clients/{id}", name="client_delete")
     * 
     * @OA\Response(
     *      response=204,
     *      description="Client removed successfully."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when client not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * @OA\Tag(name="Clients")
     */
    public function clientDelete(User $user, UserService $userService, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('delete_client', $user);

        $userService->deleteUser($user);

        return $cache->getResponse('', ['Default'], Response::HTTP_NO_CONTENT);
    }

    /**
     * @Rest\Get("/clients/{id}/users", name="client_user_list")
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @Rest\QueryParam(
     *      name="keyword",
     *      requirements="[a-zA-Z0-9]+",
     *      nullable=true,
     *      description="The keyword search for."
     * )
     * 
     * @Rest\QueryParam(
     *      name="order",
     *      requirements="(asc|desc)",
     *      default="asc",
     *      description="Sort order (asc or desc)"
     * )
     * @Rest\QueryParam(
     *      name="limit",
     *      requirements="\d+",
     *      default="15",
     *      description="Max users per page."
     * )
     * @Rest\QueryParam(
     *      name="page",
     *      requirements="\d+",
     *      default="1",
     *      description="The page number."
     * )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Get the list of all clients.",
     *      @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class))
     *     )
     * )
     * @OA\Tag(name="Clients")
     */
    public function clientUserList(User $user, ParamFetcherInterface $paramFetch, UserService $userService, CacheService $cache): Response
    {
        $users = $userService->getUserList($paramFetch, $user);

        $this->denyAccessUnlessGranted('get_client', $users);

        return $cache->getResponse($users);
    }

    /**
     * @Rest\Get("/clients/{client_id}/users/{user_id}", name="client_user_details")
     * 
     * @ParamConverter("client", options={"id" = "client_id"})
     * 
     * @OA\Parameter(
     *     name="client_id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\Response(
     *      response=200,
     *      description="Get the list of all clients.",
     *      @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class))
     *     )
     * )
     * @OA\Tag(name="Clients")
     */
    public function clientUserDetails(User $client, User $user, UserService $userService, CacheService $cache): Response
    {
        $user = $userService->getUserDetails($user, $client);

        $this->denyAccessUnlessGranted('post_client', $user);
        return $cache->getResponse($user);
    }

    /**
     * @Rest\Post("/clients/{id}/users", name="client_user_post")
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\Response(
     *      response=201,
     *      description="Client user added successfully."
     * )
     * 
     * @OA\Response(
     *      response=400,
     *      description="Required field not filled."
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
     *                          property="email",
     *                          description="Client user email.",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          description="Client user password.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * 
     * @OA\Tag(name="Clients")
     */
    public function clientUserPost(User $client, UserService $userService, Request $request, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('post_client', new User());

        $data = json_decode($request->getContent(), true);

        $user = $userService->addUser($data, $client);

        return $cache->getResponse(
            $user,
            ['ClientView'],
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'client_user_details',
                    [
                        'client_id' => $client->getId(),
                        'user_id' => $user->getId(),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    ])
            ]);
    }

        /**
     * @Rest\Patch("/clients/{client_id}/users/{user_id}", name="client_user_patch")
     * 
     * @ParamConverter("client", options={"id" = "client_id"})
     * 
     * @OA\Response(
     *      response=202,
     *      description="Client user updated successfully."
     * )
     * 
     * @OA\Response(
     *      response=400,
     *      description="Required field not filled."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when client or user not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="client_id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\RequestBody(
     *       description="Input data format",
     *          @OA\MediaType(
     *              mediaType="application/json",
     *                  @OA\Schema(
     *                      type="object",
     *                      @OA\Property(
     *                          property="username",
     *                          description="Updated username.",
     *                          type="string",
     *                      ),
     *                      @OA\Property(
     *                          property="email",
     *                          description="Updated client user email.",
     *                          type="string"
     *                      ),
     *                      @OA\Property(
     *                          property="password",
     *                          description="Updated client user password.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * @OA\Tag(name="Clients")
     */
    public function clientUserPatch(User $user, User $client, Request $request, UserService $userService, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('post_client', $user);

        $data = json_decode($request->getContent(), true);

        $user = $userService->editUser($user, $data, $client);

        return $cache->getResponse(
            $user,
            ['Details'],
            Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/clients/{client_id}/users/{user_id}", name="client_user_delete")
     * 
     * @ParamConverter("client", options={"id" = "client_id"})
     * 
     * @OA\Response(
     *      response=204,
     *      description="Client user removed successfully."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when client or user not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="client_id",
     *     in="query",
     *     description="The unique identifier of the client.",
     *     @OA\Schema(type="int")
     *  )
     * 
     * @OA\Parameter(
     *     name="user_id",
     *     in="query",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="int")
     *  )
     * @OA\Tag(name="Clients")
     */
    public function clientUserDelete(User $user, User $client, UserService $userService, CacheService $cache): Response
    {
        $this->denyAccessUnlessGranted('post_client', $user);

        $userService->deleteUser($user, $client);

        return $cache->getResponse('', ['Default'], Response::HTTP_NO_CONTENT);
    }
}