<?php

namespace Site\ClientsBundle\Controller;


use Admin\ReferenceBundle\Entity\ReferenceClientStatus;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Site\ClientsBundle\Entity\ClientsStatusHistory;

/**
 * ClientsStatusHistory controller.
 *
 * @Route("/clients/statushistory")
 */
class ClientsStatusHistoryController extends Controller
{
    /**
     * Lists all ClientsStatusHistory entities.
     *
     * @Route("/{id}", name="clients_statushistory")
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);
        if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
            throw $this->createNotFoundException('Unable to find Clients entity.');
        }

        //$entities = $em->getRepository('SiteClientsBundle:ClientsStatusHistory')->findAll();
        $entities = $em->getRepository('SiteClientsBundle:ClientsStatusHistory')
            ->findBy(array('client'=> $id), array('date_add' => 'DESC'));
        return $this->render('@SiteClients/ClientsStatusHistory/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ClientsStatusHistory entity.
     *
     * @Route("/{id}/show", name="clients_statushistory_show")
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteClientsBundle:ClientsStatusHistory')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClientsStatusHistory entity.');
        }
        if ($entity->getClient()->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE) {
            throw $this->createNotFoundException('Unable to find Clients entity.');
        }

        return $this->render('@SiteClients/ClientsStatusHistory/show.html.twig', array(
            'entity'      => $entity,
        ));
    }

}
