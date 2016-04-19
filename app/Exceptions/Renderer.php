<?php

namespace App\Exceptions;

use Doctrine\ORM\EntityNotFoundException;
use Exception;
use Illuminate\Auth\Access\AuthorizationException;
use Illuminate\Contracts\Config\Repository;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Illuminate\Foundation\Validation\ValidationException;
use Illuminate\Http\Exception\HttpResponseException;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use League\OAuth2\Server\Exception\AccessDeniedException;
use League\OAuth2\Server\Exception\InvalidRequestException;
use Symfony\Component\HttpKernel\Exception\HttpException;

class Renderer
{
    const MESSAGE_BAD = 'Internal server error';
    const MESSAGE_FAILED_AUTHORIZE = 'You do not have the permission to do this';
    const MESSAGE_NOT_FOUND = 'The resource you are looking for could not be found';
    const MESSAGE_FAILED_VALIDATION = 'Failed to validate your request';
    const MESSAGE_MALFORMED_REQUEST = 'Malformed request';
    const MESSAGE = 'message';
    const CODE = 'code';
    const NAME = 'name';
    const TRACE = 'trace';
    /**
     * @var Response
     */
    protected $response;
    /**
     * @var bool
     */
    protected $debugMode;
    /**
     * @var array
     */
    protected $messages = [
        AuthorizationException::class   => [
            self::MESSAGE => self::MESSAGE_FAILED_AUTHORIZE,
            self::CODE    => Response::HTTP_FORBIDDEN,
        ],
        ModelNotFoundException::class   => [
            self::MESSAGE => self::MESSAGE_NOT_FOUND,
            self::CODE    => Response::HTTP_NOT_FOUND,
        ],
        EntityNotFoundException::class  => [
            self::MESSAGE => self::MESSAGE_NOT_FOUND,
            self::CODE    => Response::HTTP_NOT_FOUND,
        ],
        ValidationException::class      => [
            self::MESSAGE => self::MESSAGE_FAILED_VALIDATION,
            self::CODE    => Response::HTTP_BAD_REQUEST,
        ],
        InvalidRequestException::class  => [
            self::MESSAGE => self::MESSAGE_FAILED_AUTHORIZE,
            self::CODE    => Response::HTTP_FORBIDDEN,
        ],
        AccessDeniedException::class    => [
            self::MESSAGE => self::MESSAGE_FAILED_AUTHORIZE,
            self::CODE    => Response::HTTP_UNAUTHORIZED,
        ],
//        UnexpectedValueException::class => [
//            self::MESSAGE => self::MESSAGE_MALFORMED_REQUEST,
//            self::CODE    => Response::HTTP_BAD_REQUEST
//        ],
    ];

    /**
     * Renderer constructor.
     * @param Response $response
     * @param Repository $repository
     */
    public function __construct(Response $response, Repository $repository)
    {
        $this->response = $response;
        $this->debugMode = $repository->get('app.debug');
    }

    /**
     * @param Exception $exception
     * @param Request $request
     * @return Response
     */
    public function getExceptionResponse(Exception $exception, Request $request) : Response
    {
        if (array_key_exists(get_class($exception), $this->messages)) {
            return $this->standardException($exception);
        } elseif ($exception instanceof HttpException) {
            return $this->httpException($exception);
        } elseif ($exception instanceof HttpResponseException) {
            return $this->httpResponseException($exception);
        } else {
            return $this->badException($exception, $request);
        }
    }

    /**
     * @param Exception $exception
     * @return Response
     */
    public function standardException(Exception $exception) : Response
    {
        return $this->writeResponse(
            $this->messages[get_class($exception)],
            $this->messages[get_class($exception)][self::CODE]
        );
    }

    /**
     * @param array $content
     * @param int $statusCode
     * @return Response
     */
    public function writeResponse(array $content, int $statusCode) : Response
    {
        if (!empty($content)) {
            $this->response->setContent(['error' => $content]);
        }

        return $this->response->setStatusCode($statusCode);
    }

    /**
     * @param HttpException $exception
     * @return Response
     */
    public function httpException(HttpException $exception) : Response
    {
        if ($exception->getMessage()) {
            $content = [
                self::MESSAGE => $exception->getMessage(),
                self::CODE    => $exception->getStatusCode(),
            ];
        } else {
            $content = [];
        }

        return $this->writeResponse($content, $exception->getStatusCode());
    }

    public function httpResponseException(HttpResponseException $exception) : Response
    {
        $exceptionResponse = $exception->getResponse();
        $message = $exceptionResponse->getContent();
        if ($exceptionResponse instanceof JsonResponse) {
            $message = $exceptionResponse->getData();
        }
        return $this->writeResponse(
            [
                self::MESSAGE => $message,
                self::CODE    => $exceptionResponse->getStatusCode(),
            ],
            $exceptionResponse->getStatusCode()
        );
    }

    /**
     * @param Exception $exception
     * @param Request $request
     * @return Response
     */
    public function badException(Exception $exception, Request $request) : Response
    {
        if ($this->debugMode) {
            $content = $this->debugContents($exception, $request);
        } else {
            $content = [self::MESSAGE => self::MESSAGE_BAD];
        }

        return $this->writeResponse($content, Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    /**
     * @param Exception $exception
     * @param Request $request
     * @return mixed
     */
    public function debugContents(Exception $exception, Request $request) : array
    {
        return array(
            'request'   => $request->toArray(),
            'exception' => array(
                self::NAME    => get_class($exception),
                self::MESSAGE => $exception->getMessage(),
                self::TRACE   => $exception->getTrace(),
            ),
        );
    }
}