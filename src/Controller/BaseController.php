<?php
/**
 * Created by PhpStorm.
 * User: bs
 * Date: 27.11.18
 * Time: 18:51
 */

namespace App\Controller;

use App\Exceptions\BadRequestException;
use Opis\JsonSchema\{Validator, ValidationResult, Schema};
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\{Request};

/**
 * Class BaseController
 *
 * @package App\Controller
 */
abstract class BaseController extends AbstractController
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

}