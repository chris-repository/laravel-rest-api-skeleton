<?php


namespace App\Utility;


use Doctrine\ORM\EntityManager;

interface EntityManagerAwareInterface
{
    /**
     * @return EntityManager
     */
    public function getEntityManager();

    /**
     * @param EntityManager $em
     * @return $this;
     */
    public function setEntityManager(EntityManager $em);

}