<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:28
 */

namespace App\Controller;

use App\Annotation\CheckRequest;
use App\Entity\Book;
use App\Entity\Borrow;
use App\Exceptions\BadRequestException;
use App\Repository\BookRepository;
use App\Repository\BorrowRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Swagger\Annotations as SWG;

/**
 * Class BorrowController
 *
 * @package App\Controller
 */
class BorrowController extends BaseController
{

    /**
     * @Route("/api/borrow", methods={"POST"}, name="boorow_book")
     * @CheckRequest
     *
     * @SWG\Response(
     *     response=200,
     *     description="Correctly borrow the book."
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found book to borrow."
     * )
     * @SWG\Response(
     *     response=400,
     *     description="Cannot borrow this book."
     * )
     * @SWG\Response(
     *     response=401,
     *     description="Unauthorized."
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
     *             @SWG\Property(property="bookId", type="integer", example="12345678911")
     *         )
     *     )
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exceptions\NotFoundException
     * @throws \App\Exceptions\BadRequestException
     */
    public function borrowBook(Request $request): JsonResponse
    {
        $data = $this->validateRequest($request, 'borrow_book');

        /** @var ObjectManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $book = $bookRepository->getByPublicId($data['bookId']);

        if (!$book->getAvailable()) {
            throw new BadRequestException('Cannot borrow this book.');
        }

        $book->setAvailable(false);

        $borrow = new Borrow();
        $borrow->setUser($this->getUser());
        $borrow->setBook($book);

        $entityManager->merge($borrow);
        $entityManager->flush();

        return new JsonResponse(json_encode(['Id' => $borrow->getPublicId()]), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/borrow/{id}", methods={"DELETE"}, requirements={"id"="\d+"}, name="borrow_delete")
     * @CheckRequest
     *
     * @SWG\Response(
     *     response=200,
     *     description="Correctly remove borrow."
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found borrow."
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exceptions\NotFoundException
     */
    public function delete(Request $request): JsonResponse
    {
        $borrowPublicId = (int)$request->get('id');

        /** @var ObjectManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BorrowRepository $borrowRepository */
        $borrowRepository = $entityManager->getRepository(Borrow::class);

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $borrow = $borrowRepository->getByPublicId($borrowPublicId);

        $book = $bookRepository->getById($borrow->getBook()->getId());
        $book->setAvailable(true);

        $entityManager->remove($borrow);
        $entityManager->flush();

        return new JsonResponse(json_encode([]), Response::HTTP_OK, [], true);
    }

}