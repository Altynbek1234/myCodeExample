<?php

namespace Site\ClientsBundle\Controller;

use Admin\ReferenceBundle\Entity\ReferenceClientStatus;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Site\ClientsBundle\Entity\Clients;
use Site\ClientsBundle\Entity\ClientsIdentityCard;
use Site\ClientsBundle\Entity\ClientsIdentityCardHistory;
use Site\ClientsBundle\Form\ClientsIdentityCardType;

/**
 * ClientsIdentityCard controller.
 *
 * @Route("/clients/identitycard")
 */
class ClientsIdentityCardController extends Controller
{
    /**
     * Lists all ClientsIdentityCard entities.
     * 
     * @Route("/{id}", name="clients_identitycard", requirements={"id" = "\d+"})
     */
    public function indexAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);
        if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
            throw $this->createNotFoundException('Unable to find Clients entity.');
        }
        
        $entities = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->findBy(array('client'=> $id));
       
        return $this->render('@SiteClients/ClientsIdentityCard/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Finds and displays a ClientsIdentityCard entity.
     *
     * @Route("/{id}/show", name="clients_identitycard_show", requirements={"id" = "\d+"})
     */
    public function showAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClientsIdentityCard entity.');
        }
        if ($entity->getClient()->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE) {
            throw $this->createNotFoundException('Unable to find Clients entity.');
        }

        $deleteForm = $this->createDeleteForm($id);

        return $this->render('@SiteClients/ClientsIdentityCard/show.html.twig', array(
            'entity'      => $entity,
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Displays a form to create a new ClientsIdentityCard entity.
     *
     * @Route("/new", name="clients_identitycard_new")
     */
    public function newAction()
    {
        $entity = new ClientsIdentityCard();
        $form   = $this->createForm(ClientsIdentityCardType::class, $entity);

        return $this->render('@SiteClients/ClientsIdentityCard/new.html.twig', array(
            'entity'=> $entity,
            'form'=> $form->createView(),
        ));
    }
    
    /**
     * Displays a form to create a new ClientsIdentityCard entity by Client.
     *
     * @Route("/new/{id}", name="clients_identitycard_add")
     */
    public function addAction($id)
    {
        $em = $this->getDoctrine()->getManager();
        $client = $em->getRepository('SiteClientsBundle:Clients')->find($id);
        
        $entity = new ClientsIdentityCard();
        $entity->setClient($client);
        
        $form = $this->createForm(ClientsIdentityCard::class, $entity);

        return $this->render('@SiteClients/ClientsIdentityCard/new.html.twig', array(
            'entity'=> $entity,
            'form'=> $form->createView(),
        ));
    }

    /**
     * Creates a new ClientsIdentityCard entity.
     *
     * @Route("/create", name="clients_identitycard_create")
     * @Method("POST")
     */
    public function createAction(Request $request)
    {
        $entity  = new ClientsIdentityCard();
        $form = $this->createForm(ClientsIdentityCardType::class, $entity);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->persist($entity);
            $em->flush();

            return $this->redirect($this->generateUrl('clients_identitycard_show', array('id' => $entity->getId())));
        }

        return $this->render('@SiteClients/ClientsIdentityCard/new.html.twig', array(
            'entity' => $entity,
            'form'   => $form->createView(),
        ));
    }

    /**
     * Displays a form to edit an existing ClientsIdentityCard entity.
     *
     * @Route("/{id}/edit", name="clients_identitycard_edit", requirements={"id" = "\d+"})
     */
    public function editAction($id)
    {
        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClientsIdentityCard entity.');
        }
        if ($entity->getClient()->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE) {
            throw $this->createNotFoundException('Unable to find Clients entity.');
        }

        $editForm = $this->createForm(ClientsIdentityCardType::class, $entity);
        $deleteForm = $this->createDeleteForm($id);

        return $this->render('@SiteClients/ClientsIdentityCard/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Edits an existing ClientsIdentityCard entity.
     *
     * @Route("/{id}/update", name="clients_identitycard_update", requirements={"id" = "\d+"})
     * @Method("POST")
     */
    public function updateAction(Request $request, $id)
    {
        $user = $this->getUser();
        if (!$user) {
            return $this->redirect($this->generateUrl('security_login'));
        }

        $em = $this->getDoctrine()->getManager();

        $entity = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->find($id);

        if (!$entity) {
            throw $this->createNotFoundException('Unable to find ClientsIdentityCard entity.');
        }
        if ($entity->getClient()->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE) {
            throw $this->createNotFoundException('Unable to find Clients entity.');
        }

        $identityCard = clone $entity;

        $deleteForm = $this->createDeleteForm($id);
        $editForm = $this->createForm(ClientsIdentityCardType::class, $entity);
        $editForm->handleRequest($request);

        if ($editForm->isValid()) {
            $em->persist($entity);
            $em->flush();

            $this->addHistoryIdentityCard($identityCard, $user, 'Документ отредактирован');

            return $this->redirect($this->generateUrl('clients_show', array('id' => $entity->getClient()->getId())));
        }

        return $this->render('@SiteClients/ClientsIdentityCard/edit.html.twig', array(
            'entity'      => $entity,
            'edit_form'   => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a ClientsIdentityCard entity.
     *
     * @Route("/{id}/delete", name="clients_identitycard_delete", requirements={"id" = "\d+"})
     * @Method("POST")
     */
    public function deleteAction(Request $request, $id)
    {
        $form = $this->createDeleteForm($id);
        $form->handleRequest($request);

        if ($form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $entity = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->find($id);
            $clientId = $entity->getClient()->getId();

            if (!$entity) {
                throw $this->createNotFoundException('Unable to find ClientsIdentityCard entity.');
            }
            if ($entity->getClient()->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE) {
                throw $this->createNotFoundException('Unable to find Clients entity.');
            }

            $em->remove($entity);
            $em->flush();
        }

        return $this->redirect($this->generateUrl('clients_identitycard', ['id'=> $clientId]));
    }
    
    /**
     * All 
     * 
     * @Route("/all", name="clients_identitycard_all")
     */
    public function allAction()
    {
        $em = $this->getDoctrine()->getManager();
        
        $entities = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->findAll();

        return $this->render('@SiteClients/ClientsIdentityCard/index.html.twig', array(
            'entities' => $entities,
        ));
    }

    /**
     * Добавляем историю IdentityCards
     *
     * @param object $identityCard
     * @param object $user
     * @param string $msg
     */
    private function addHistoryIdentityCard($identityCard, $user, $msg = null)
    {
        $em = $this->getDoctrine()->getManager();
        $new_IdentityCard = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->find($identityCard->getId());

        if ($identityCard != $new_IdentityCard) {
            // Add client identitycard history
            $history = new ClientsIdentityCardHistory();
            $history->setClient($new_IdentityCard->getClient());
            $history->setNote($msg);
            $history->setUser($user);
            $identityCard_arr = array(
                'referenceDocumentType' => $new_IdentityCard->getReferenceDocumentType()->getId(),
                'number' => $new_IdentityCard->getNumber(),
                'date_issue' => $new_IdentityCard->getDateIssue(),
                'department' => $new_IdentityCard->getDepartment(),
                'validity' => $new_IdentityCard->getValidity(),
                'referenceCountry' => $new_IdentityCard->getReferenceCountry()->getId(),
                'referencePassportStatus' => $new_IdentityCard->getReferencePasportStatus()->getId()
            );

            $history->setData($identityCard_arr);
            $em->persist($history);
            $em->flush();
        }
    }

    private function createDeleteForm($id)
    {
        return $this->createFormBuilder(array('id' => $id))
            ->add('id', HiddenType::class)
            ->getForm()
        ;
    }
}
