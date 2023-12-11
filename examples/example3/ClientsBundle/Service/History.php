<?php

namespace Site\ClientsBundle\Service;

use Admin\UserBundle\Entity\User;
use Site\ClientsBundle\Entity\Clients;
use Site\ClientsBundle\Entity\ClientsSmsPhoneHistory;
use Site\ClientsBundle\Entity\ClientsStatusHistory;
use Symfony\Component\DependencyInjection\Container;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;

class History
{
    /**
     * @var Container
     */
    private $container;
    /**
     * @var Request
     */
    private $request;

    /**
     * @return \Doctrine\Bundle\DoctrineBundle\Registry|mixed|object|Container|null
     * @throws \Exception
     */
    private function getDoctrine(){
        return $this->container->get('doctrine');
    }

    public function __construct(Container $container, RequestStack $requestStack)
    {
       $this->container = $container;
       $this->request = $requestStack->getCurrentRequest();
    }

    private function getLastClientStatus($id)
    {
        $last_status = 0;
        $em = $this->getDoctrine()->getManager();
        $repostitory = $em->getRepository('SiteClientsBundle:ClientsStatusHistory');

        $query = $repostitory->createQueryBuilder('h')
            ->where('h.client = :id')
            ->setParameter('id', $id)
            ->setMaxResults('1')
            ->orderBy('h.date_add', 'DESC')
            ->getQuery();

        $result = $query->getResult();
        if ($result) {
            $resultFirst = $result[0];
            $last_status = $resultFirst->getReferenceClientStatus()->getId();
        }
        return $last_status;
    }

    /**
     * @param $id
     * @return string|null
     * @throws \Exception
     */
    private function getLastSmsPhone($id)
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository('SiteClientsBundle:ClientsSmsPhoneHistory');

        $query = $repository->createQueryBuilder('h')
            ->where('h.client = :id')
            ->setParameter('id', $id)
            ->setMaxResults(1)
            ->orderBy('h.date_add', 'DESC')
            ->getQuery();

        $result = $query->getResult();
        if ($result) {
            /** @var ClientsSmsPhoneHistory $resultFirst */
            $resultFirst = $result[0];
            return $resultFirst->getPhone();
        }

        return null;
    }

    /**
     * Добавляем историю статусов
     *
     * @param $entity
     * @param null $msg
     * @param $user
     * @throws \Exception
     */
    public function addHistoryStatus($entity, $msg = null, $user)
    {
        $status = $entity->getReferenceClientStatus()->getId();
        $last_status = $this->getLastClientStatus($entity->getId());
        if ($status != $last_status or $last_status == 0) {
            // Add client status history
            $history = new ClientsStatusHistory();
            $history->setClient($entity);
            $history->setNote($msg);
            $history->setUser($user);
            $history->setReferenceClientStatus($entity->getReferenceClientStatus());
            $em = $this->getDoctrine()->getManager();
            $em->persist($history);
        }
    }

    /**
     * Добавление истории изменения номера для смс
     *
     * @param Clients $clients
     * @param User $user
     * @param bool $isFirst
     * @return void
     * @throws \Exception
     */
    public function addSmsPhoneHistory(Clients $clients, User $user, bool $isFirst = false)
    {
        $lastSmsPhone = $this->getLastSmsPhone($clients->getId());

        if ($isFirst === true || ($lastSmsPhone !== $clients->getSmsPhone())) {
            $history = new ClientsSmsPhoneHistory();
            $history->setUser($user);
            $history->setClient($clients);
            $history->setPhone($clients->getSmsPhone());

            $em = $this->getDoctrine()->getManager();
            $em->persist($history);
        }
    }

}