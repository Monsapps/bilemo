<?php

namespace App\Controller;

use App\Entity\User;
use App\Service\UserService;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Request\ParamFetcherInterface;
use FOS\RestBundle\View\View;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Nelmio\ApiDocBundle\Annotation\Model;
use Nelmio\ApiDocBundle\Annotation\Security;
use OpenApi\Annotations as OA;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class UserController extends AbstractController
{
    /**
     * @Rest\Get("/users", name="user_list")
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
     *      description="Get the list of all products.",
     *      @OA\JsonContent(
     *        type="array",
     *        @OA\Items(ref=@Model(type=App\Entity\User::class))
     *     )
     * )
     * @OA\Tag(name="Users")
     */
    public function userList(ParamFetcherInterface $paramFetch, UserService $userService): View
    {
        //TODO add client

        $users = $userService->getUserList($paramFetch);

        return new View($users);
    }

    /**
     * @Rest\Get("/users/{id}", name="user_details")
     * @Rest\View(serializerGroups={"ClientView"})
     * 
     * @OA\Response(
     *      response=200,
     *      description="Get user details."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when user not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="int")
     *  )
     * @OA\Tag(name="Users")
     */
    public function userDetails(User $user): View
    {
        //TODO check user roles and User parent
        return new View($user);
    }

    /**
     * @Rest\Post("/users", name="user_post")
     * @Rest\View(serializerGroups={"ClientView"})
     * 
     * @OA\Response(
     *      response=201,
     *      description="User added successfully."
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
     *                          description="User email.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * 
     * @OA\Tag(name="Users")
     */
    public function userPost(UserService $userService, Request $request): View
    {
        // TODO test perms
        $data = json_decode($request->getContent(), true);

        $user = $userService->addUser($data);

        return new View(
            $user,
            Response::HTTP_CREATED,
            [
                'Location' => $this->generateUrl(
                    'user_details',
                    [
                        'id' => $user->getId(),
                        UrlGeneratorInterface::ABSOLUTE_PATH
                    ])
            ]);
    }

    /**
     * @Rest\Patch("/users/{id}", name="user_patch")
     * @Rest\View(serializerGroups={"Details"})
     * 
     * @OA\Response(
     *      response=202,
     *      description="User updated successfully."
     * )
     * 
     * @OA\Response(
     *      response=400,
     *      description="Required field not filled."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when user not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="id",
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
     *                          description="Updated user email.",
     *                          type="string"
     *                      )
     *                  )
     *         )
     * )
     * @OA\Tag(name="Users")
     */
    public function userPatch(User $user, Request $request, UserService $userService): View
    {
        // TODO test perms
        $data = json_decode($request->getContent(), true);

        $user = $userService->editUser($user, $data);

        return new View(
            $user,
            Response::HTTP_OK);
    }

    /**
     * @Rest\Delete("/users/{id}", name="user_delete")
     * 
     * @OA\Response(
     *      response=204,
     *      description="User removed successfully."
     * )
     * 
     * @OA\Response(
     *      response=404,
     *      description="Returned when user not exist."
     * )
     * 
     * @OA\Parameter(
     *     name="id",
     *     in="query",
     *     description="The unique identifier of the user.",
     *     @OA\Schema(type="int")
     *  )
     * @OA\Tag(name="Users")
     */
    public function userDelete(User $user, UserService $userService)
    {
        // TODO test perms
        $userService->deleteUser($user);

        return new View('', Response::HTTP_NO_CONTENT);
    }
}