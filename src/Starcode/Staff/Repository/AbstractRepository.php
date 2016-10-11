<?php

namespace Starcode\Staff\Repository;

use Doctrine\ORM\EntityRepository;

class AbstractRepository extends EntityRepository
{
    /**
     * Truncate entities
     */
    public function truncate()
    {
        $userMetadata = $this->getClassMetadata();
        $connection = $this->getEntityManager()->getConnection();
        $connection->query('TRUNCATE ' . $userMetadata->getTableName());
    }
}