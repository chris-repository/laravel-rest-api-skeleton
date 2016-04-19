<?php

namespace App\Http\Controllers;

use App\Http\Requests\ParsedRequest;
use App\Http\Response\BasicCollectionResponseGenerator;
use App\Http\Response\FractalItemResponseBuilder;
use App\Http\Response\ResponseGenerator;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\EntityRepository;
use Hashids\Hashids;
use Illuminate\Contracts\Foundation\Application;
use Illuminate\Routing\Controller;
use Illuminate\Http\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception as HttpException;

abstract class ApiController extends Controller
{
    /**
     * @var EntityManager
     */
    protected $entityManager;

    /**
     * @var Application
     */
    private $app;

    public function __construct(
        EntityManager $entityManager,
        Application $app
    ) {
        $this->entityManager = $entityManager;
        $this->app = $app;
    }

    public function getEntityRepository($className) : EntityRepository
    {
        return $this->entityManager->getRepository($className);
    }

    public function getItemResponseBuilder() : FractalItemResponseBuilder
    {
        return $this->app->make(FractalItemResponseBuilder::class);
    }

    public function getListResponse(ParsedRequest $parsedRequest, string $filterProcessorClass) : Response
    {
        return $this->getResponseGenerator($filterProcessorClass)
            ->generateCollectionResponse($parsedRequest);
    }

    public function getResponseGenerator(string $className) : ResponseGenerator
    {
        return $this->app->make(ResponseGenerator::class, [$className]);
    }

    public function getBasicListResponse(array $list) : Response
    {
        return $this->app->make(BasicCollectionResponseGenerator::class)->generateCollectionResponse($list);
    }

    public function getStoreResponse($object, $location) : Response
    {
        $response = $this->getItemResponseBuilder()->build($object)
            ->setStatusCode(Response::HTTP_CREATED);

        $response->headers->add(['Location' => $location]);

        return $response;
    }

    public function getItemResponse($object) : Response
    {
        if (is_null($object)) {
            return $this->notFound();
        }

        return $this->getItemResponseBuilder()->build($object);
    }

    /**
     * @param $hash
     * @return int
     */
    public function hashToId($hash)
    {
        $hashIds = $this->app->make(Hashids::class);

        try {
            return $hashIds->decode($hash)[0];
        } catch (\ErrorException $ex) {
            $this->notFound();
        }

        return null;
    }

    public function idToHash($id)
    {
        $hashIds = $this->app->make(Hashids::class);

        return $hashIds->encode($id);
    }

    public function getSuccessResponse(string $message = 'Request Successful') : JsonResponse
    {
        return new JsonResponse(['success' => ['message' => $message]]);
    }

    public function forbidden($message = 'Access Denied')
    {
        throw new HttpException\AccessDeniedHttpException($message);
    }

    public function conflict($message = 'Conflict')
    {
        throw new HttpException\ConflictHttpException($message);
    }

    public function badRequest($message = 'Bad Request')
    {
        throw new HttpException\BadRequestHttpException($message);
    }

    public function notFound($message = 'Not Found')
    {
        throw new HttpException\NotFoundHttpException($message);
    }

    public function methodNotAllowed($message = 'Method Not Allowed')
    {
        throw new HttpException\MethodNotAllowedHttpException([], $message);
    }
}
