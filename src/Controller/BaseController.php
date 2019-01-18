<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 27.11.18
 * Time: 18:51
 */

namespace App\Controller;

use App\Exceptions\BadRequestException;
use Knp\Component\Pager\Pagination\AbstractPagination;
use Opis\JsonSchema\{Validator, ValidationResult, Schema};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\{Request};

/**
 * Class BaseController
 *
 * @package App\Controller
 */
abstract class BaseController extends Controller
{

    /**
     * @param Request $request
     * @param string $schema
     *
     * @throws BadRequestException
     *
     * @return array
     */
    protected function validateRequest(Request $request, string $schema): array
    {
        $data = json_decode($request->getContent());
        $schema = Schema::fromJsonString(file_get_contents(__DIR__ . '/../Resources/Schemas/' . $schema . '.json'));

        $validator = new Validator();

        /** @var ValidationResult $result */
        $result = $validator->schemaValidation($data, $schema, -1);

        if ($result->isValid()) {
            return (array)$data;
        }

        throw new BadRequestException($result->getErrors());
    }

    /**
     * @param array $response
     * @param AbstractPagination $pagination
     * @return false|string
     */
    protected function paginate(array $response, AbstractPagination $pagination)
    {
        $tmp['data'] = $response;
//        $response['pagination']['currentPageNumber'] = $pagination->getCurrentPageNumber();
//        $response['pagination']['itemNumberPerPage'] = $pagination->getItemNumberPerPage();
//        $response['pagination']['totalItemCount'] = $pagination->getTotalItemCount();

        return json_encode($response);
    }
}