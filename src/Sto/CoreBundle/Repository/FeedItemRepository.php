<?php

namespace Sto\CoreBundle\Repository;

use Doctrine\ORM\EntityRepository;

/**
 * FeedItemRepository
 */
class FeedItemRepository extends EntityRepository
{
    public function getByMarks($dealMarks, $companyMarks)
    {
        $qb = $this->createQueryBuilder('f');

        if($companyMarks) {
            $qb
                ->leftJoin('f.company', 'c')
                ->leftJoin('c.autos', 'ca')
                ->where('c.allAuto = true OR ca IN (:companyMarks)')
                ->setParameter('companyMarks', $companyMarks)
            ;
        } else {
            $qb->where('f.company IS NULL');
        }

        if ($dealMarks) {
            $qb
                ->leftJoin('f.deal', 'd')
                ->leftJoin('d.auto', 'da')
                ->setParameter('dealMarks', $dealMarks)
            ;
            if($companyMarks) {
                $qb->orWhere('d.allAuto = true OR da IN (:dealMarks)');
            } else {
                $qb->andWhere('d.allAuto = true OR da IN (:dealMarks)');
            }
        } else {
            $qb->andWhere('f.deal IS NULL');
        }

        $qb->orderBy('f.createdAt', 'DESC');

        return $qb->getQuery();
    }
}
