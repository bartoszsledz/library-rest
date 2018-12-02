<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 21.11.2018 20:06
 */

namespace App\Controller;

use App\Entity\User;
use App\Enums\User as UserEnum;
use App\Exceptions\BadRequestException;
use App\Helpers\RandomGenerator;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Swagger\Annotations as SWG;

/**
 * Class UsersController
 *
 * @package App\Controller
 */
class UsersController extends BaseController
{

    /**
     * @Route("/api/users", methods={"POST"}, name="users_create")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @SWG\Response(
     *     response=200,
     *     description="New user was created."
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Bad request."
     * )
     * @SWG\Post(
     *     @SWG\Parameter(
     *         name="Request Body",
     *         in="body",
     *         description="JSON Payload",
     *         required=true,
     *         format="application/json",
     *         @SWG\Schema(
     *             type="object",
     *             @SWG\Property(property="username", type="string", example="user123"),
     *             @SWG\Property(property="email", type="string", example="user123@gmail.com"),
     *             @SWG\Property(property="password", type="string", example="*************")
     *         )
     *     )
     * )
     *
     * @return JsonResponse
     * @throws BadRequestException
     */
    public function create(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): JsonResponse
    {
        $data = $this->validateRequest($request, 'user_create');

        $user = new User($data);
        $user->setPublicId(RandomGenerator::generateUniqueInteger(User::getLengthUnique()));
        $user->setToken(RandomGenerator::generateAuthToken());
        $user->setRoles([UserEnum::ROLE_USER]);
        $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse(json_encode(['status' => 'Created']), Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/api/users/auth", methods={"POST"}, name="users_auth")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return JsonResponse
     * @throws \Doctrine\ORM\NonUniqueResultException
     * @throws BadRequestException
     */
    public function auth(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): JsonResponse
    {
        $data = $this->validateRequest($request, 'user_auth');

        /** @var ObjectManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->loadUserByUsername($data['username']);

        if ($user !== null && $userPasswordEncoder->isPasswordValid($user, $data['password'])) {
            $user->setToken(RandomGenerator::generateAuthToken());

            $this->get('session')->set($user->getToken(), $user);

            $entityManager->persist($user);
            $entityManager->flush();

            return new JsonResponse(json_encode(['token' => $user->getToken()]), Response::HTTP_OK, [], true);
        }

        throw new BadRequestException('Bad login or password.');
    }

}