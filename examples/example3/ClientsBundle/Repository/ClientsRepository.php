<?php

namespace Site\ClientsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ClientsRepository extends EntityRepository
{
	public function findAllClientsByGender($gender)
	{
		return $this->createQueryBuilder('c')
			->select(
				'l.id as lombard_id',
				'COUNT(l.id) as count'
			)
			->join('c.lombard', 'l')
			->where('substring(c.inn, 1, 1) = :gender')
			->orderBy('l.id', 'ASC')
			->setParameter('gender', $gender)
			->groupBy('l.id')
			->getQuery()
			->getResult();
	}

	public function findAllClientsByAges($ageFrom, $ageTo)
	{
		$nowYear = date('Y');
		$yearsFrom = $nowYear - $ageFrom;
		$yearsTo = $nowYear - $ageTo;
		return $this->createQueryBuilder('c')
			->select(
				'l.id as lombard_id',
				'COUNT(l.id) as count'
			)
			->join('c.lombard', 'l')
			->where('substring(c.inn, 6, 4) <= :yearsFrom')
			->andWhere('substring(c.inn, 6, 4) >= :yearsTo')
			->orderBy('l.id', 'ASC')
			->setParameters(
				[
					'yearsFrom' => $yearsFrom,
					'yearsTo' => $yearsTo,
				]
			)
			->groupBy('l.id')
			->getQuery()
			->getResult();
	}
}
