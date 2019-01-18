<?php
/**
 * @author: Bartosz Sledz <bartosz.sledz94@gmail.com>
 * @date: 24.11.2018 18:28
 */

namespace App\Controller;

use App\Entity\Borrow;
use App\Entity\History;
use App\Exceptions\GeneralException;
use App\Repository\BorrowRepository;
use DateTime;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMException;
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
     * @Route("/api/borrow/{id}/return", methods={"POST"}, requirements={"id"="\d+"}, name="borrow_return")
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
     * @throws \Doctrine\DBAL\ConnectionException
     * @throws GeneralException
     */
    public function delete(Request $request): JsonResponse
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

}