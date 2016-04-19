<?php
declare(strict_types = 1);

namespace App\Http\Response;


use App\Transformers\TransformerProvider;
use Doctrine\ORM\Query;
use Illuminate\Http\Response;
use League\Fractal\Manager;

abstract class AbstractFractalResponseBuilder
{
    const RESOURCE_KEY = 'data';
    const PARAM_INCLUDES = 'include';
    /**
     * @var Response
     */
    protected $response;

    /**
     * @var Manager
     */
    protected $manager;

    /**
     * @var TransformerProvider
     */
    protected $transformerProvider;


    /**
     * AbstractFractalResponseBuilder constructor.
     * @param Response $response
     * @param Manager $manager
     * @param TransformerProvider $transformerProvider
     */
    public function __construct(
        Response $response,
        Manager $manager,
        TransformerProvider $transformerProvider
    ) {
        $this->response = $response;
        $this->manager = $manager;
        $this->transformerProvider = $transformerProvider;
    }

    public function getManager() : Manager
    {
        return $this->manager;
    }
}