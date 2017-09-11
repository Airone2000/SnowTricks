<?php

namespace AppBundle\Repository\Trick;

use Doctrine\ORM\Tools\Pagination\Paginator;

/**
 * CommentRepository
 *
 * This class was generated by the Doctrine ORM. Add your own custom
 * repository methods below.
 */
class CommentRepository extends \Doctrine\ORM\EntityRepository
{

    public function getPaginatedComments($first = 0, $maxResults = 10)
    {

        $query = $this->createQueryBuilder('c')
            ->setFirstResult($first * $maxResults)
            ->setMaxResults($maxResults)
            ->orderBy('c.id', 'desc');


        $result = new Paginator($query, false);
        return $result;
    }

}