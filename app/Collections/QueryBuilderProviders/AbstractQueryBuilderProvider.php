<?php
declare(strict_types = 1);

namespace App\Collections\QueryBuilderProviders;

use App\OAuth\OAuthAuthorizer;
use Doctrine\ORM\EntityManager;
use Hashids\Hashids;
use Illuminate\Routing\Route;

abstract class AbstractQueryBuilderProvider implements QueryBuilderProviderInterface
{
    /** @var EntityManager */
    protected $entityManager;

    /** @var Route */
    protected $route;

    /** @var Hashids  */
    protected $idHasher;

    /**
     * AbstractQueryBuilderProvider constructor.
     * @param EntityManager $entityManager
     * @param Route $route
     * @param Hashids $hashids
     */
    public function __construct(
        EntityManager $entityManager,
        Route $route,
        Hashids $hashids
    ) {
        $this->entityManager = $entityManager;
        $this->route = $route;
        $this->idHasher = $hashids;
    }

    public function hashToId(string $hash) : int
    {
        return $this->idHasher->decode($hash)[0];
    }
}