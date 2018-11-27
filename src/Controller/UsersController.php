<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 21.11.2018 20:06
 */

namespace App\Controller;

use App\Entity\User;
use App\Exceptions\BadRequestException;
use App\Helpers\RandomGenerator;
use App\Repository\UserRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

/**
 * Class UsersController
 *
 * @Route("/api/users")
 * @package App\Controller
 */
class UsersController extends BaseController
{

    /**
     * @Route("/hello", methods={"GET"}, name="users_index")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        return new JsonResponse(['test' => 'test1'], 200, []);
    }

    /**
     * @Route("/create", methods={"POST"}, name="users_create")
     *
     * @param Request $request
     * @param UserPasswordEncoderInterface $userPasswordEncoder
     *
     * @return JsonResponse
     * @throws BadRequestException
     */
    public function create(Request $request, UserPasswordEncoderInterface $userPasswordEncoder): JsonResponse
    {
        $data = $this->validateRequest($request, 'users_create');

        $user = new User($data);
        $user->setPublicId(RandomGenerator::generateUniqueInteger(User::LENGTH_UNIQUE));
        $user->setToken(RandomGenerator::generateAuthToken());
        $user->setRoles([User::ROLE_USER]);
        $user->setPassword($userPasswordEncoder->encodePassword($user, $user->getPassword()));

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return new JsonResponse([], Response::HTTP_CREATED, []);
    }

    /**
     * @Route("/auth", methods={"POST"}, name="users_auth")
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

        $data = $this->validateRequest($request, 'users_auth');

        /** @var ObjectManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var UserRepository $userRepository */
        $userRepository = $entityManager->getRepository(User::class);

        $user = $userRepository->loadUserByUsername($data['username']);

        if ($user !== null && $userPasswordEncoder->isPasswordValid($user, $data['password'])) {
            return new JsonResponse(['token' => $user->getToken()], Response::HTTP_OK, []);
        }

        return new JsonResponse(['details' => 'Bad login or password.'], Response::HTTP_BAD_REQUEST);
    }

}