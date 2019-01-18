<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:23
 */

namespace App\Controller;

use App\Entity\Book;
use App\Entity\Review;
use App\Exceptions\BadRequestException;
use App\Exceptions\NotFoundException;
use App\Repository\BookRepository;
use App\Repository\ReviewRepository;
use Doctrine\ORM\EntityManager;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Borrow;
use App\Exceptions\GeneralException;
use Doctrine\ORM\ORMException;
use Swagger\Annotations as SWG;
use App\Entity\History;
use App\Repository\BorrowRepository;
use DateTime;

/**
 * Class BookController
 *
 * @package App\Controller
 */
class BookController extends BaseController
{

    /**
     * @Route("/api/books", methods={"POST"}, name="book_add")
     * @IsGranted("ROLE_ADMIN")
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
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws NotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function delete(Request $request): JsonResponse
    {
        $bookPublicId = (int)$request->get('id');

        /** @var EntityManager $entityManager */
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
     * @IsGranted("ROLE_ADMIN")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws BadRequestException
     * @throws NotFoundException
     * @throws \Doctrine\ORM\ORMException
     * @throws \Doctrine\ORM\OptimisticLockException
     */
    public function edit(Request $request): JsonResponse
    {
        $data = $this->validateRequest($request, 'book_edit');

        $bookPublicId = (int)$request->get('id');

        /** @var EntityManager $entityManager */
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

    /**
     * @Route("/api/books/{id}", methods={"GET"}, requirements={"id"="\d+"}, name="book_get_one")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function getBook(Request $request): JsonResponse
    {
        $bookPublicId = (int)$request->get('id');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $book = $bookRepository->getByPublicId($bookPublicId);

        if ($book === null) {
            throw new NotFoundException();
        }

        $response = [
            'id' => $book->getPublicId(),
            'isbn' => $book->getISBN(),
            'author' => $book->getAuthor(),
            'title' => $book->getTitle(),
            'description' => $book->getDescription(),
        ];

        return new JsonResponse(json_encode($response), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/books", methods={"GET"},name="book_get_all")
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function getAllBook(Request $request): JsonResponse
    {
        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $books = $bookRepository->findAll();

        $response = [];

        /** @var Book $book */
        foreach ($books as $book) {
            $response[] = [
                'id' => $book->getPublicId(),
                'isbn' => $book->getISBN(),
                'author' => $book->getAuthor(),
                'title' => $book->getTitle(),
                'description' => $book->getDescription(),
            ];
        }

        return new JsonResponse(json_encode($response), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/books/{id}/borrow", methods={"POST"}, requirements={"id"="\d+"}, name="books_boorow")
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
     * @throws BadRequestException
     * @throws \App\Exceptions\NotFoundException
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws GeneralException
     */
    public function borrowBook(Request $request): JsonResponse
    {
        $bookId = (int)$request->get('id');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        $book = $bookRepository->getByPublicId($bookId);

        if (!$book->getAvailable()) {
            throw new BadRequestException('Cannot borrow this book.');
        }

        $book->setAvailable(false);
        $borrow = new Borrow();
        $borrow->setUser($this->getUser());
        $borrow->setBook($book);

        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->persist($book);
            $entityManager->persist($borrow);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (ORMException $e) {
            $entityManager->getConnection()->rollBack();
            throw new GeneralException();
        }

        return new JsonResponse(json_encode(['Borrow Id' => $borrow->getPublicId()]), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/books/{id}/return", methods={"POST"}, requirements={"id"="\d+"}, name="books_return")
     *
     * @SWG\Response(
     *     response=200,
     *     description="Correctly return book."
     * )
     * @SWG\Response(
     *     response=404,
     *     description="Not found borrow."
     * )
     *
     * @param Request $request
     * @return JsonResponse
     * @throws \App\Exceptions\NotFoundException
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws GeneralException
     */
    public function returnBook(Request $request): JsonResponse
    {
        $borrowPublicId = (int)$request->get('id');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var BorrowRepository $borrowRepository */
        $borrowRepository = $entityManager->getRepository(Borrow::class);

        $borrow = $borrowRepository->getByPublicId($borrowPublicId);

        $book = $borrow->getBook();
        $book->setAvailable(true);

        $history = new History([
            'book' => $book,
            'user' => $borrow->getUser(),
            'dateBorrow' => $borrow->getCreated(),
            'dateReturn' => new DateTime(),
        ]);

        $entityManager->getConnection()->beginTransaction();
        try {
            $entityManager->remove($borrow);
            $entityManager->persist($history);
            $entityManager->flush();
            $entityManager->getConnection()->commit();
        } catch (ORMException $e) {
            $entityManager->getConnection()->rollBack();
            throw new GeneralException();
        }

        return new JsonResponse(json_encode([]), Response::HTTP_OK, [], true);
    }

    /**
     * @Route("/api/books/{id}/reviews", methods={"GET"}, name="books_get_reviews")
     *
     * @param Request $request
     *
     * @return JsonResponse
     * @throws NotFoundException
     */
    public function getReviews(Request $request): JsonResponse
    {
        $bookPublicId = (int)$request->get('id');

        /** @var EntityManager $entityManager */
        $entityManager = $this->getDoctrine()->getManager();

        /** @var ReviewRepository $reviewRepository */
        $reviewRepository = $entityManager->getRepository(Review::class);

        /** @var BookRepository $bookRepository */
        $bookRepository = $entityManager->getRepository(Book::class);

        /* @var $paginator \Knp\Component\Pager\Paginator */
        $paginator = $this->get('knp_paginator');

        $book = $bookRepository->getByPublicId($bookPublicId);

        /** @var AbstractPagination $pagination */
        $pagination = $paginator->paginate(
            $reviewRepository->getAllReviewsForBookQuery($book),
            $request->query->getInt('page', 1),
            10
        );

        $response = [];

        /** @var Review $review */
        foreach ($pagination->getItems() as $review) {
            $response[] = [
                'publicId' => $review->getPublicId(),
                'userEmail' => $review->getUser()->getEmail(),
                'bookId' => $review->getBook()->getPublicId(),
                'comment' => $review->getComment(),
                'stars' => $review->getStars()
            ];
        }

        return new JsonResponse($this->paginate($response, $pagination), Response::HTTP_OK, [], true);
    }

}