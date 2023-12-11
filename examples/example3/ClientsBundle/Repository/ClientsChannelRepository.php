<?php

namespace Site\ClientsBundle\Repository;

use Doctrine\ORM\EntityRepository;

class ClientsChannelRepository extends EntityRepository
{
	public function getClientsChannels($dateFrom, $dateTo)
	{
		return $this->createQueryBuilder('cc')
			->select('c.created_at', 'ch.name as channel', 'cc.other', 'l.name as lombard')
			->join('cc.client', 'c')
			->join('cc.channel', 'ch')
			->join('c.lombard', 'l')
			->where('c.created_at >= :dateFrom')
			->andWhere('c.created_at <= :dateTo')
			->setParameters(
				[
					'dateFrom' => $dateFrom,
					'dateTo' => $dateTo
				]
			)
			->getQuery()
			->getResult();
	}
}
