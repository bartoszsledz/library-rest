<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:23
 */

namespace App\Controller;

use App\Entity\Book;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Annotation\CheckRequest;
use App\Repository\BookRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class BookController
 *
 * @package App\Controller
 */
class BookController extends BaseController
{

    /**
     * @Route("/api/books", methods={"POST"}, name="book_add")
     * @CheckRequest
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws \App\Exceptions\BadRequestException
     */
    public function add(Request $request): JsonResponse
    {
        $data = $this->validateRequest($request, 'book_add');

        $book = new Book($data);
        $book->setAvailable(true);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($book);
        $entityManager->flush();

        return new JsonResponse(json_encode(['Id' => $book->getPublicId()]), Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/api/books/{id}", methods={"DELETE"}, requirements={"id"="\d+"}, name="book_delete")
     * @CheckRequest
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function delete(Request $request): JsonResponse
    {
        $bookPublicId = (int)$request->get('id');

        /** @var ObjectManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $book = $bookRepository->getByPublicId($bookPublicId);

        if ($book === null) {
            throw new NotFoundException();
        }

        $entityManager->remove($book);
        $entityManager->flush();

        return new JsonResponse(json_encode(['status' => 'Deleted']), Response::HTTP_CREATED, [], true);
    }

    /**
     * @Route("/api/books/{id}", methods={"PUT"}, requirements={"id"="\d+"}, name="book_edit")
     * @CheckRequest
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws NotFoundException
     * @throws BadRequestException
     */
    public function edit(Request $request): JsonResponse
    {
        $data = $this->validateRequest($request, 'book_edit');

        $bookPublicId = (int)$request->get('id');

        /** @var ObjectManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $book = $bookRepository->getByPublicId($bookPublicId);

        if ($book === null) {
            throw new NotFoundException();
        }

        $book->setData($data);

        $entityManager->flush();

        return new JsonResponse(json_encode(['Id' => $book->getPublicId()]), Response::HTTP_CREATED, [], true);
    }

}