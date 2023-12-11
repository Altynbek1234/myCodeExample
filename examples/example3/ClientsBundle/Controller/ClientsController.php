<?php

namespace Site\ClientsBundle\Controller;

use Admin\ReferenceBundle\Entity\ReferenceClientStatus;
use Admin\ChannelBundle\Entity\Channel;
use Site\BlanksBundle\Service\CurrentLombard;
use Site\ClientsBundle\Entity\ClientsFiu;
use Site\ClientsBundle\Entity\ClientsFiuHistory;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Routing\Annotation\Route;
use Site\ClientsBundle\Entity\Clients;
use Site\ClientsBundle\Entity\ClientsIdentityCardHistory;
use Site\ClientsBundle\Form\ClientsType;
use Pagerfanta\Pagerfanta;
use Pagerfanta\Adapter\ArrayAdapter;
use Site\ClientsBundle\Entity\ClientsChannel;
use Symfony\Component\HttpFoundation\Response;


/**
 * Clients controller.
 *
 * @Route("/clients")
 */
class ClientsController extends AbstractController
{

	/**
	 * Lists all Clients entities.
	 *
	 * @Route("/", name="clients")
	 */
	public function indexAction()
	{
		$em = $this->getDoctrine()->getManager();

		$statuses = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
			->createQueryBuilder('cs')
			->where('cs.id != :notActiveStatus')
			->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
			->getQuery()->getResult();

		$types = $em->getRepository('AdminReferenceBundle:ReferenceClientType')->findAll();

		$request = $this->get('request_stack')->getCurrentRequest();
		$pathurl = $request->getPathInfo();
		$session = $request->getSession();
		$currentPage = $request->query->get('page', 1);

		if ($request->request->get('limit')) {
			$session->set('limit', $request->request->get('limit'));
		}
		$limit = $session->get('limit', $request->request->get('limit', 20));

		if ($request->request->get('searchclient')) {
			$pos = explode(' ', $request->request->get('searchclient'));
			$last_name = $pos[0];
			$session->set('searchclient', $request->request->get('searchclient'));
			$session->set('lastname', $last_name);
		}
		if ($request->request->get('filterclient')) {
			$session->set('filterclient', $request->request->get('filterclient'));
		}
		if ($request->request->get('filterclienttype')) {
			$session->set('filterclienttype', $request->request->get('filterclienttype'));
		}
		if ($request->request->get('orderclient')) {
			$session->set('orderclient', $request->request->get('orderclient'));
		}
		if ($request->request->get('orderdirclient')) {
			$session->set('orderdirclient', $request->request->get('orderdirclient'));
		}
		if ($request->request->get('date_to_client')) {
			$session->set('date_to_client', $request->request->get('date_to_client'));
		}
		if ($request->request->get('date_from_client')) {
			$session->set('date_from_client', $request->request->get('date_from_client'));
		}
		if ($request->request->get('resetbuttonclient')) {
			$last_name = null;
			$session->set('searchclient', null);
			$session->set('filterclient', null);
			$session->set('filterclienttype', null);
			$session->remove('date_to_client');
			$session->remove('date_from_client');
			$session->set('orderclient', 'c.lastname');
			$session->set('orderdirclient', 'DESC');
		}

		$orderdir = $session->get('orderdirclient', $request->request->get('orderdirclient', 'DESC'));
		$order = $session->get('orderclient', $request->request->get('orderclient', 'c.lastname'));
		$filter = $session->get('filterclient', $request->request->get('filterclient', 'all'));
		$filtertype = $session->get('filterclienttype', $request->request->get('filterclienttype', 'all'));
		$search = $session->get('searchclient', $request->request->get('searchclient'));
		$last_name = $session->get('lastname', null);

		if ($filter != 'fiu') {

			$repository = $this->getDoctrine()
				->getRepository('SiteClientsBundle:Clients');

			$query = $repository->createQueryBuilder('c')
				->leftJoin('c.identityCard', 'i')
				->leftJoin('c.historyStatus', 'h')
				->addOrderBy('h.date_add')
				->groupBy('c.id')
				->where('lower(c.firstname) LIKE lower(:value)')
				->orwhere('lower(c.lastname) LIKE lower(:lastname)')
				->orwhere('lower(c.secondname) LIKE lower(:value)')
				->orwhere('c.inn LIKE :value')
				->andwhere('c.referenceClientStatus != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->setParameter('value', $search . '%')
				->setParameter('lastname', $last_name . '%')
				->orderBy($order, $orderdir);
			if (isset($pos[1])) {
				$first_name = $pos[1];
				$query->andwhere('lower(c.firstname) LIKE lower(:firstname)')
					->setParameter('firstname', $first_name . '%');
				if (isset($pos[2])) {
					$second_name = $pos[2];
					$query->andwhere('lower(c.secondname) LIKE lower(:secondname)')
						->setParameter('secondname', $second_name . '%');
				}
			}

			if ($filter && $filter != "all") {
				$query = $query->andwhere('c.referenceClientStatus = :filterclient')
					->setParameter('filterclient', (int)$filter);
			}

			if ($filtertype && $filtertype != "all") {
				$query = $query->andwhere('c.referenceClientType = :filterclienttype')
					->setParameter('filterclienttype', (int)$filtertype);
			}

			$query
				->andwhere('h.date_add >= :date_from')
				->setParameter('date_from', date('Y-m-d', strtotime($session->get('date_from_client', date('Y-m')))));

			$query
				->andwhere('h.date_add <= :date_to')
				->setParameter('date_to', date('Y-m-d', strtotime($session->get('date_to_client', date('Y-m-d')))) . ' 23:59:59');
		} else {
			$repository = $this->getDoctrine()
				->getRepository('SiteClientsBundle:ClientsFiu');

			$query = $repository->createQueryBuilder('c')
				->where('lower(c.firstname) LIKE lower(:value)')
				->orwhere('lower(c.lastname) LIKE lower(:lastname)')
				->orwhere('lower(c.secondname) LIKE lower(:value)')
				->setParameter('value', $search . '%')
				->setParameter('lastname', $last_name . '%')
				->orderBy($order, $orderdir);
			if (isset($pos[1])) {
				$first_name = $pos[1];
				$query->andwhere('lower(c.firstname) LIKE lower(:firstname)')
					->setParameter('firstname', $first_name . '%');
				if (isset($pos[2])) {
					$second_name = $pos[2];
					$query->andwhere('lower(c.secondname) LIKE lower(:secondname)')
						->setParameter('secondname', $second_name . '%');
				}
			}
		}
		$queryCount = clone $query;
		$query->setFirstResult($limit * ($currentPage - 1))->setMaxResults($limit);
		$entities = $query->getQuery()->getResult();
		$entitiesCount = $queryCount->getQuery()->getResult();
		$sum = sizeof($queryCount->select('c.id')->getQuery()->getResult());

		$query = $repository->createQueryBuilder('c')
			->select('COUNT(c.id) as countAll')->getQuery()->getResult();
		$countAll = $query[0]['countAll'];
		$adapter = new ArrayAdapter($queryCount->select('c.id')->getQuery()->getResult());
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage($limit); // 10 by default
		$pagerfanta->setCurrentPage($currentPage); // 1 by default
		$pagerfanta->getNbPages();
		$pagerfanta->haveToPaginate(); // whether the number of results if higher than the max per page
		if ($pathurl == '/clients/') {
			return $this->render(
				'@SiteClients/Clients/index.html.twig',
				array(
					'entities' => $entities,
					'statuses' => $statuses,
					'types' => $types,
					'filter' => $filter,
					'filtertype' => $filtertype,
					'orderdir' => $orderdir,
					'order' => $order,
					'pagerfanta' => $pagerfanta,
					'sum' => $sum,
					'countAll' => $countAll
				)
			);
		} else {
			$dateTo = date('d.m.Y', strtotime($session->get('date_to_client', date('Y-m-d'))));
			$dateFrom = date(
				'd.m.Y',
				strtotime($session->get('date_from_client', date('Y-m-d', strtotime(date('Y-m')))))
			);

			return $this->render(
				'@SiteClients/Clients/pdf.html.twig',
				array(
					'entities' => $entitiesCount,
					'sum' => $sum,
					'dateTo' => $dateTo,
					'dateFrom' => $dateFrom,
					'countAll' => $countAll
				)
			);
		}
	}

	/**
	 * Lists all old Clients entities.
	 *
	 * @Route("/old", name="clients_old")
	 */
	public function indexOldAction()
	{
		$em = $this->getDoctrine()->getManager();
		$year = date('Y') - 1;
		$access = $this->getLombard();
		$lombardNow = $access['lombardNow'];
		$lombard = $access['lombard'];

		$date_to = date("Y-m-d", strtotime("-3 months"));

		$statuses = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
			->createQueryBuilder('cs')
			->where('cs.id != :notActiveStatus')
			->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
			->getQuery()->getResult();

		$types = $em->getRepository('AdminReferenceBundle:ReferenceClientType')->findAll();

		$request = $this->get('request_stack')->getCurrentRequest();
		$pathurl = $request->getPathInfo();
		$session = $request->getSession();
		$currentPage = $request->query->get('page', 1);

		if ($request->request->get('limit')) {
			$session->set('limit', $request->request->get('limit'));
		}
		$limit = $session->get('limit', $request->request->get('limit', 20));

		if ($request->request->get('cache_lombard')) {
			$session->set('cache_lombard', $request->request->get('cache_lombard'));
		}
		if ($request->request->get('filterclient')) {
			$session->set('filterclient', $request->request->get('filterclient'));
		}
		if ($request->request->get('filterclienttype')) {
			$session->set('filterclienttype', $request->request->get('filterclienttype'));
		}
		if ($request->request->get('orderclient')) {
			$session->set('orderclient', $request->request->get('orderclient'));
		}
		if ($request->request->get('orderdirclient')) {
			$session->set('orderdirclient', $request->request->get('orderdirclient'));
		}
		if ($request->request->get('resetbuttonclient')) {
			$session->set('filterclient', null);
			$session->set('filterclienttype', null);
			$session->set('orderclient', 'c.lastname');
			$session->set('orderdirclient', 'DESC');
		}

		$orderdir = $session->get('orderdirclient', $request->request->get('orderdirclient', 'DESC'));
		$order = $session->get('orderclient', $request->request->get('orderclient', 'c.lastname'));
		$filter = $session->get('filterclient', $request->request->get('filterclient', 'all'));
		$filtertype = $session->get('filterclienttype', $request->request->get('filterclienttype', 'all'));

		$activeClients = $this->getDoctrine()->getManager()
			->getRepository('SiteClientsBundle:Clients')
			->createQueryBuilder('c')
			->join('c.blanksPladges', 'bp')
			->where('c.referenceClientStatus != :notActiveStatus')
			->andWhere('bp.lombard = :lombardId')
			->andWhere('bp.date >= :dateTo')
			->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
			->setParameter('lombardId', $lombardNow->getId())
			->setParameter('dateTo', $date_to)
			->groupBy('c.id')
			->getQuery()->getResult();
		$clientIds = [];
		foreach ($activeClients as $activeClient) {
			$clientIds[] = $activeClient->getId();
		}

		$query = $this->getDoctrine()->getManager()
			->getRepository('SiteClientsBundle:Clients')
			->createQueryBuilder('c')
			->join('c.blanksPladges', 'bp')
			->where('bp.lombard = :lombardId')
			->andWhere('c.referenceClientStatus != :notActiveStatus')
			->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
			->setParameter('lombardId', $lombardNow->getId());
		if (\count($clientIds) !== 0) {
			$query
				->andWhere('c.id NOT IN (:clientIds)')
				->setParameter('clientIds', $clientIds);
		}
		$query
			->groupBy('c.id')
			->orderBy($order, $orderdir);

		if ($filter && $filter != "all") {
			$query = $query->andwhere('c.referenceClientStatus = :filterclient')
				->setParameter('filterclient', (int)$filter);
		}

		if ($filtertype && $filtertype != "all") {
			$query = $query->andwhere('c.referenceClientType = :filterclienttype')
				->setParameter('filterclienttype', (int)$filtertype);
		}
		$queryCount = clone $query;
		$entitiesCount = $queryCount->getQuery()->getResult();
		$countAll = count($entitiesCount);

		$adapter = new ArrayAdapter($queryCount->select('c.id')->getQuery()->getResult());
		$pagerfanta = new Pagerfanta($adapter);
		$pagerfanta->setMaxPerPage($limit); // 10 by default
		$pagerfanta->setCurrentPage($currentPage); // 1 by default
		$pagerfanta->getNbPages();
		$pagerfanta->haveToPaginate(); // whether the number of results if higher than the max per page
		$query->setFirstResult($limit * ($currentPage - 1))->setMaxResults($limit);
		$entities = $query->getQuery()->getResult();
		if ($pathurl == '/clients/old') {
			return $this->render(
				'@SiteClients/Clients/indexOld.html.twig',
				array(
					'entities' => $entities,
					'statuses' => $statuses,
					'types' => $types,
					'filter' => $filter,
					'filtertype' => $filtertype,
					'orderdir' => $orderdir,
					'order' => $order,
					'pagerfanta' => $pagerfanta,
					'countAll' => $countAll,
					'lombards' => $lombard
				)
			);
		} else {
			$dateTo = date('d.m.Y', strtotime($session->get('date_to_client_old', $year . '-' . date('m') . '-' . date('d'))));
			$dateFrom = date(
				'd.m.Y',
				strtotime($session->get('date_from_client_old', date('Y-m-d', strtotime($year . '-' . date('m') . '-01'))))
			);

			return $this->render(
				'@SiteClients/Clients/pdfOld.html.twig',
				array(
					'entities' => $entitiesCount,
					'dateTo' => $dateTo,
					'dateFrom' => $dateFrom,
					'countAll' => $countAll
				)
			);
		}
	}

	/**
	 * @Route("/{id}/show", name="clients_show")
	 * @param $id
	 * @param Request $request
	 * @return Response
	 */
	public function showAction($id, Request $request)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);

		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}

		$statuses = $em->getRepository('SiteClientsBundle:ClientsStatusHistory')
			->findBy(array('client' => $id), array('date_add' => 'DESC'));

		$identityCards = $em->getRepository('SiteClientsBundle:ClientsIdentityCardHistory')
			->findBy(array('client' => $id), array('date_add' => 'DESC'));

		$referenceDocumentType = $em->getRepository('AdminReferenceBundle:ReferenceDocumentType')->findAll();
		$referenceCountry = $em->getRepository('AdminReferenceBundle:ReferenceCountry')->findAll();
		$view = $request->query->get('view', 'index');



		return $this->render('@SiteClients/Clients/show.html.twig', array(
			'entity' => $entity,
			'statuses' => $statuses,
			'identityCards' => $identityCards,
			'referenceDocumentType' => $referenceDocumentType,
			'referenceCountry' => $referenceCountry,
			'view' => $view
		));
	}

	/**
	 * Печать карточки клиета pdf
	 *
	 * @Route("/pdfShow/{id}", name="clients_pdfShow")
	 */
	public function pdfShowAction($id)
	{
		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);
		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}

		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4',
			'default_font_size' => 11,
			'default_font' => '',
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_top' => 7,
			'margin_bottom' => 7,
			'margin_header' => 10,
			'margin_footer' => 10,
			'orientation' => 'P'
		]);

		$mpdf->list_indent_first_level = 0;
		$html = $this->render("@SiteClients/Clients/showPdf.html.twig", array('entity' => $entity,));
		$content = $html->getContent();
		$mpdf->WriteHTML($content);
		$datenow = date('d-m-Y H:i:s');
		$mpdf->Output($entity . ' (' . $datenow . ').pdf', 'I');
	}

	/**
	 * Finds and displays a Clients entity.
	 *
	 * @Route("/{id}/showPdf", name="clients_show_pdf")
	 */
	public function showPdfAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);

		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}

		return $this->render('@SiteClients/Clients/showPdf.html.twig', array(
			'entity' => $entity,
		));
	}

	/**
	 * Displays a form to create a new Clients entity.
	 *
	 * @Route("/new", name="clients_new")
	 */
	public function newAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new Clients();
		$referenceClientType = $em->getRepository('AdminReferenceBundle:ReferenceClientType')->find(1);
		$entity->setReferenceClientType($referenceClientType);
		$referenceChannels = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->findAll();
		$channelForm = [];
		$otherChannel = [];
		foreach ($referenceChannels as $referenceChannel) {
			if ($referenceChannel->getId() != $referenceChannel->getOtherId()) {
				$channelForm[$referenceChannel->getName()] = $referenceChannel->getId();
			} else{
				$otherChannel[$referenceChannel->getName()] = $referenceChannel->getId();
			}
		};
		$channelForm = array_merge($channelForm, $otherChannel);
		$referenceDocumentType = $em->getRepository('AdminReferenceBundle:ReferenceDocumentType')->find(1);
		$identityCard = new \Site\ClientsBundle\Entity\ClientsIdentityCard();
		$identityCard->setReferenceDocumentType($referenceDocumentType);
		$referenceCountry = $em->getRepository('AdminReferenceBundle:ReferenceCountry')->find(1);
		$identityCard->setReferenceCountry($referenceCountry);
		$entity->setIdentityCard($identityCard);
		$form = $this->createForm(ClientsType::class, $entity, [
			'channelChoices' => $channelForm,
		]);
		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			$referenceClientStatus = $em
				->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		} else {
			$referenceClientStatus = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != 4')
				->andwhere('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		}

		return $this->render('@SiteClients/Clients/new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'referenceClientStatus' => $referenceClientStatus
		));
	}

	/**
	 * Creates a new Clients entity.
	 *
	 * @Route("/create", name="clients_create")
	 * @Method("POST")
	 */
	public function createAction(Request $request)
	{
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		if (!$user) {
			return $this->redirect($this->generateUrl('security_login'));
		}
		$entity = new Clients();
		$referenceChannels = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->findAll();
		$channelForm = [];
		$otherChannel = [];
		foreach ($referenceChannels as $referenceChannel) {
			if ($referenceChannel->getId() != $referenceChannel->getOtherId()) {
				$channelForm[$referenceChannel->getName()] = $referenceChannel->getId();
			} else {
				$otherChannel[$referenceChannel->getName()] = $referenceChannel->getId();
			}
		};
		$channelForm = array_merge($channelForm, $otherChannel);
		$form = $this->createForm(ClientsType::class, $entity, ['channelChoices' => $channelForm]);
		$form->handleRequest($request);
		$em = $this->getDoctrine()->getManager();

		if ($this->checkClientFiu($entity) === false) {
			$error = new FormError('Операции по данному клиенту невозможны, так как он найден в санкционных списках');
			$form->addError($error);
		}

		if ($form->isValid()) {
			$formclient = $request->request->get('site_clientsbundle_clientstype');
			if (isset($formclient['enabled']) and $formclient['enabled'] == 1 and $formclient['referenceClientStatus'] == 4) {
				$entity->setEnabled(1);
			} else {
				$entity->setEnabled(0);
			}
			$em->persist($entity);
			$identityCard = $entity->getIdentityCard();
			$identityCard->setClient($entity);
			$referencePasportStatus = $em->getRepository('AdminReferenceBundle:ReferencePasportStatus')->find(1);
			$identityCard->setReferencePasportStatus($referencePasportStatus);

			$em->persist($identityCard);

			$staff = $em->getRepository('AdminStaffBundle:Staff')->findOneBy(
				array('user' => $user->getId())
			);

			$historyService = $this->get('client_history');
			$historyService->addHistoryStatus($entity, 'Клиент добавлен', $user);
			$historyService->addSmsPhoneHistory($entity, $user, true);

			$this->addHistoryIdentityCard($identityCard, $user, 'Документ добавлен');

			foreach($request->get('site_clientsbundle_clientstype')['clientsChannels'] as $value){
				$channel = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->find($value);
				$clientChannel = new ClientsChannel();
				$clientChannel->setClient($entity);
				$clientChannel->setChannel($channel);
				if($channel->getOtherId() === $channel->getId()){
					$clientChannel->setOther($request->get('site_clientsbundle_clientstype')['otherChannel']);
				}
				$em->persist($clientChannel);
			}

			$entity->setLombard($staff->getLombard());
			
			$em->flush();

			return $this->redirect($this->generateUrl('clients_show', array('id' => $entity->getId())));
		}

		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			$referenceClientStatus = $em
				->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		} else {
			$referenceClientStatus = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id NOT IN (:statusIds)')
				->setParameter('statusIds', [ReferenceClientStatus::NOT_ACTIVE, ReferenceClientStatus::IN_BLACK_LIST])
				->getQuery()->getResult();
		}

		return $this->render('@SiteClients/Clients/new.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'referenceClientStatus' => $referenceClientStatus,
		));
	}

	/**
	 * Displays a form to create a new Clients entity.
	 *
	 * @Route("/newblanks", name="clients_new_blanks")
	 */
	public function newBlanksAction()
	{
		$em = $this->getDoctrine()->getManager();
		$entity = new Clients();
		$referenceClientType = $em->getRepository('AdminReferenceBundle:ReferenceClientType')->find(1);
		$entity->setReferenceClientType($referenceClientType);
		$referenceChannels = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->findAll();
		$channelForm = [];
		$otherChannel = [];
		foreach ($referenceChannels as $referenceChannel) {
			if ($referenceChannel->getId() != $referenceChannel->getOtherId()) {
				$channelForm[$referenceChannel->getName()] = $referenceChannel->getId();
			} else {
				$otherChannel[$referenceChannel->getName()] = $referenceChannel->getId();
			}
		};
		$channelForm = array_merge($channelForm, $otherChannel);
		$referenceDocumentType = $em->getRepository('AdminReferenceBundle:ReferenceDocumentType')->find(1);
		$identityCard = new \Site\ClientsBundle\Entity\ClientsIdentityCard();
		$identityCard->setReferenceDocumentType($referenceDocumentType);
		$referenceCountry = $em->getRepository('AdminReferenceBundle:ReferenceCountry')->find(1);
		$identityCard->setReferenceCountry($referenceCountry);
		$entity->setIdentityCard($identityCard);
		$form = $this->createForm(ClientsType::class, $entity, ['channelChoices' => $channelForm]);
		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			$referenceClientStatus = $em
				->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		} else {
			$referenceClientStatus = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id NOT IN (:statusIds)')
				->setParameter('statusIds', [ReferenceClientStatus::NOT_ACTIVE, ReferenceClientStatus::IN_BLACK_LIST])
				->getQuery()->getResult();
		}

		return $this->render('@SiteClients/Clients/newBlanks.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'referenceClientStatus' => $referenceClientStatus
		));
	}

	/**
	 * Creates a new Clients entity.
	 *
	 * @Route("/createblanks", name="clients_create_blanks")
	 * @Method("POST")
	 */
	public function createBlanksAction(Request $request)
	{	
		$em = $this->getDoctrine()->getManager();
		$user = $this->getUser();
		if (!$user) {
			return $this->redirect($this->generateUrl('security_login'));
		}
		$entity = new Clients();
		$referenceChannels = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->findAll();
		$channelForm = [];
		$otherChannel = [];
		foreach ($referenceChannels as $referenceChannel) {
			if ($referenceChannel->getId() != $referenceChannel->getOtherId()) {
				$channelForm[$referenceChannel->getName()] = $referenceChannel->getId();
			} else {
				$otherChannel[$referenceChannel->getName()] = $referenceChannel->getId();
			}
		};
		$channelForm = array_merge($channelForm, $otherChannel);
		$form = $this->createForm(ClientsType::class, $entity, ['channelChoices' => $channelForm]);
		$form->handleRequest($request);
		$em = $this->getDoctrine()->getManager();

		$staff = $em->getRepository('AdminStaffBundle:Staff')->findOneBy(
			array('user' => $user->getId())
		);

		if ($this->checkClientFiu($entity) === false) {
			$error = new FormError('Операции по данному клиенту невозможны, так как он найден в санкционных списках');
			$form->addError($error);
		}

		if ($form->isValid()) {
			$formclient = $request->request->get('site_clientsbundle_clientstype');
			if (isset($formclient['enabled']) and $formclient['enabled'] == 1 and $formclient['referenceClientStatus'] == 4) {
				$entity->setEnabled(1);
			} else {
				$entity->setEnabled(0);
			}
			$em->persist($entity);
			
			$identityCard = $entity->getIdentityCard();
			$identityCard->setClient($entity);
			$referencePasportStatus = $em->getRepository('AdminReferenceBundle:ReferencePasportStatus')->find(1);
			$identityCard->setReferencePasportStatus($referencePasportStatus);
			$em->persist($identityCard);

			$historyService = $this->get('client_history');
			$historyService->addHistoryStatus($entity, 'Клиент добавлен', $user);
			$historyService->addSmsPhoneHistory($entity, $user, true);
			foreach ($request->get('site_clientsbundle_clientstype')['clientsChannels'] as $value) {
				$channel = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->find($value);
				$clientChannel = new ClientsChannel();
				$clientChannel->setClient($entity);
				$clientChannel->setChannel($channel);
				if ($channel->getOtherId() === $channel->getId()) {
					$clientChannel->setOther($request->get('site_clientsbundle_clientstype')['otherChannel']);
				}
				$em->persist($clientChannel);
			}

			$entity->setLombard($staff->getLombard());

			$em->flush();

			return $this->render('@SiteClients/Clients/newBlanks.html.twig', array(
				'entity' => $entity
			));
		}

		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			$referenceClientStatus = $em
				->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		} else {
			$referenceClientStatus = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id NOT IN (:statusIds)')
				->setParameter('statusIds', [ReferenceClientStatus::NOT_ACTIVE, ReferenceClientStatus::IN_BLACK_LIST])
				->getQuery()->getResult();
		}

		return $this->render('@SiteClients/Clients/newBlanks.html.twig', array(
			'entity' => $entity,
			'form' => $form->createView(),
			'referenceClientStatus' => $referenceClientStatus
		));
	}

	/**
	 * Displays a form to edit an existing Clients entity.
	 *
	 * @Route("/{id}/edit", name="clients_edit")
	 */
	public function editAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);
		$referenceChannels = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->findAll();
		$channelForm = [];
		$otherChannel = [];
		foreach ($referenceChannels as $referenceChannel) {
			if ($referenceChannel->getId() != $referenceChannel->getOtherId()) {
				$channelForm[$referenceChannel->getName()] = $referenceChannel->getId();
			} else {
				$otherChannel[$referenceChannel->getName()] = $referenceChannel->getId();
			}
		};
		$channelForm = array_merge($channelForm, $otherChannel);
		$otherChannel = '';
		$clientChannels = [];
		foreach($entity->getClientsChannels() as $clientChannel){
			$clientChannels[$clientChannel->getChannel()->getName()] = $clientChannel->getChannel()->getId();
			if($clientChannel->getChannel()->getId() == $clientChannel->getChannel()->getOtherId()){
				$otherChannel = $clientChannel->getOther();
			}
		};
		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}

		$editForm = $this->createForm(ClientsType::class, $entity, [
			'channelChoices' => $channelForm,
			'clientChannels' => $clientChannels,
			'otherChannel' => $otherChannel
		]);
		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			$referenceClientStatus = $em
				->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		} else {
			$referenceClientStatus = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != 4')
				->andwhere('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		}

		return $this->render('@SiteClients/Clients/edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'referenceClientStatus' => $referenceClientStatus
		));
	}

	/**
	 * Edits an existing Clients entity.
	 *
	 * @Route("/{id}/update", name="clients_update")
	 * @Method("POST")
	 */
	public function updateAction(Request $request, $id)
	{
		$user = $this->getUser();
		if (!$user) {
			return $this->redirect($this->generateUrl('security_login'));
		}

		$em = $this->getDoctrine()->getManager();
		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);
		$referenceChannels = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->findAll();
		$channelForm = [];
		$otherChannel = [];
		foreach ($referenceChannels as $referenceChannel) {
			if ($referenceChannel->getId() != $referenceChannel->getOtherId()) {
				$channelForm[$referenceChannel->getName()] = $referenceChannel->getId();
			} else {
				$otherChannel[$referenceChannel->getName()] = $referenceChannel->getId();
			}
		};
		$channelForm = array_merge($channelForm, $otherChannel);
		$staff = $em->getRepository('AdminStaffBundle:Staff')->findOneBy(
			array('user' => $user->getId())
		);

		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}

		$old_identityCard = clone $entity->getIdentityCard();

		$editForm = $this->createForm(ClientsType::class, $entity, ['channelChoices' => $channelForm]);
		$editForm->handleRequest($request);

		if ($this->checkClientFiu($entity, false) === false) {
			$error = new FormError('Операции по данному клиенту невозможны, так как он найден в санкционных списках');
			$editForm->addError($error);
		}

		if ($editForm->isValid()) {
			$form = $request->request->get('site_clientsbundle_clientstype');
			if (isset($form['enabled']) and $form['enabled'] == 1 and $form['referenceClientStatus'] == 4) {
				$entity->setEnabled(1);
			} else {
				$entity->setEnabled(0);
			}
			$em->persist($entity);

			$identityCard = $entity->getIdentityCard();
			$identityCard->setClient($entity);
			$em->persist($identityCard);

			$historyService = $this->get('client_history');
			$historyService->addHistoryStatus($entity, 'Клиент отредактирован', $user);
			$historyService->addSmsPhoneHistory($entity, $user);

			$this->addEditHistoryIdentityCard($old_identityCard, $user, 'Документ отредактирован');
			if($entity->getClientsChannels() != null){
				foreach ($entity->getClientsChannels() as $clientChannel) {
					$em->remove($clientChannel);
				};
			};
			foreach ($request->get('site_clientsbundle_clientstype')['clientsChannels'] as $value) {
				$channel = $em->getRepository('AdminReferenceBundle:ReferenceChannel')->find($value);
				$clientChannel = new ClientsChannel();
				$clientChannel->setClient($entity);
				$clientChannel->setChannel($channel);
				if ($channel->getOtherId() === $channel->getId()) {
					$clientChannel->setOther($request->get('site_clientsbundle_clientstype')['otherChannel']);
				}
				$em->persist($clientChannel);
			}


			$em->flush();

			return $this->redirect($this->generateUrl('clients_show', array('id' => $id)));
		}

		if (in_array('ROLE_ADMIN', $this->getUser()->getRoles())) {
			$referenceClientStatus = $em
				->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		} else {
			$referenceClientStatus = $em->getRepository('AdminReferenceBundle:ReferenceClientStatus')
				->createQueryBuilder('cs')
				->where('cs.id != 4')
				->andwhere('cs.id != :notActiveStatus')
				->setParameter('notActiveStatus', ReferenceClientStatus::NOT_ACTIVE)
				->getQuery()->getResult();
		}

		return $this->render('@SiteClients/Clients/edit.html.twig', array(
			'entity' => $entity,
			'edit_form' => $editForm->createView(),
			'referenceClientStatus' => $referenceClientStatus,
		));
	}

	/**
	 * Deletes a Clients entity.
	 *
	 * @Route("/{id}/delete", name="clients_delete")
	 * @Method("POST")
	 */
	public function deleteAction(Request $request, $id)
	{
		$form = $this->createFormBuilder(array('id' => $id))
			->add('id', HiddenType::class)
			->getForm();
		$form->handleRequest($request);

		if ($form->isValid()) {
			$em = $this->getDoctrine()->getManager();
			$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);

			if (!$entity) {
				throw $this->createNotFoundException('Unable to find Clients entity.');
			}

			$em->remove($entity);
			$em->flush();
		}

		return $this->redirect($this->generateUrl('clients'));
	}

	/**
	 * Инфо о клиенте
	 *
	 * @Route("/{id}/info", name="clients_info", requirements={"id" = "\d+"})
	 */
	public function infoAction($id)
	{
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);

		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}

		return $this->render('@SiteClients/Clients/info.html.twig', array(
			'entity' => $entity,
		));
	}

	/**
	 * Проверка клиента
	 *
	 * @Route("/{id}/check", name="clients_check", requirements={"id" = "\d+"})
	 */
	public function checkAction($id)
	{
		$check = false;
		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository('SiteClientsBundle:Clients')->find($id);

		if (!$entity or ($entity and $entity->getReferenceClientStatus()->getId() == ReferenceClientStatus::NOT_ACTIVE)) {
			throw $this->createNotFoundException('Unable to find Clients entity.');
		}
		if ($entity->getReferenceClientStatus()->getId() != 4) {
			$check = true;
		} else {
			if ($entity->getEnabled() == 1) {
				$check = true;
			}
		}

		$response = new Response(json_encode(array('check' => $check)));

		return $response;
	}

	/**
	 * Добавляем историю IdentityCards
	 *
	 * @param object $identityCard
	 * @param object $user
	 * @param string $msg
	 */
	private function addEditHistoryIdentityCard($identityCard, $user, $msg = null)
	{
		$em = $this->getDoctrine()->getManager();
		$new_IdentityCard = $em->getRepository('SiteClientsBundle:ClientsIdentityCard')->find($identityCard->getId());

		if ($identityCard != $new_IdentityCard) {
			$this->addHistoryIdentityCard($new_IdentityCard, $user, $msg);
		}
	}

	private function addHistoryIdentityCard($identityCard, $user, $msg = null)
	{
		$em = $this->getDoctrine()->getManager();
		// Add client identitycard history
		$history = new ClientsIdentityCardHistory();
		$history->setClient($identityCard->getClient());
		$history->setNote($msg);
		$history->setUser($user);
		$identityCard_arr = array(
			'referenceDocumentType' => $identityCard->getReferenceDocumentType()->getId(),
			'number' => $identityCard->getNumber(),
			'date_issue' => $identityCard->getDateIssue(),
			'department' => $identityCard->getDepartment(),
			'validity' => $identityCard->getValidity(),
			'referenceCountry' => $identityCard->getReferenceCountry()->getId(),
			'referencePassportStatus' => $identityCard->getReferencePasportStatus()->getId()
		);

		$history->setData($identityCard_arr);
		$em->persist($history);
	}

	/**
	 * Печать списка клиентов в pdf
	 * @Route("/pdf", name="clients_pdf")
	 */
	public function pdfAction(Request $request)
	{
		$user = $this->getUser();
		if (!$user) {
			return $this->redirect($this->generateUrl('security_login'));
		};

		$mpdf = new \Mpdf\Mpdf([
			'mode' => 'utf-8',
			'format' => 'A4-L',
			'default_font_size' => 11,
			'default_font' => '',
			'margin_left' => 10,
			'margin_right' => 10,
			'margin_top' => 7,
			'margin_bottom' => 7,
			'margin_header' => 10,
			'margin_footer' => 10,
			'orientation' => 'P'
		]);

		$mpdf->list_indent_first_level = 0;
		$view = $request->query->get('view', 'index');
		if ($view == 'old') {
			$html = $this->indexOldAction()->getContent();
		} else {
			$html = $this->indexAction()->getContent();
		}
		$mpdf->WriteHTML($html);

		/* формируем pdf */
		$datenow = date('d-m-Y H:i:s');
		$mpdf->Output('Клиенты (' . $datenow . ').pdf', 'I');
	}

	/**
	 * Проверка клиента
	 *
	 * @Route("/{id}/client/fiu", name="clients_client_fiu", requirements={"id" = "\d+"})
	 */
	public function clientFIUAction($id): JsonResponse
	{
		if ($this->getUser() === null) {
			return $this->redirect($this->generateUrl('security_login'));
		}

		$em = $this->getDoctrine()->getManager();

		$entity = $em->getRepository(ClientsFiu::class)->find($id);

		$clientFiuHistory = new ClientsFiuHistory();
		$clientFiuHistory->setSecondname($entity->getSecondname());
		$clientFiuHistory->setFirstname($entity->getFirstname());
		$clientFiuHistory->setLastname($entity->getLastname());
		$clientFiuHistory->setOsnov($entity->getOsnov());
		$clientFiuHistory->setBirthdate($entity->getBirthdate());
		$clientFiuHistory->setAddress($entity->getAddress());
		$clientFiuHistory->setNote($entity->getNote());

		$lombard = $this->getLombard();

		$clientFiuHistory->setUser($this->getUser());
		$clientFiuHistory->setLombard($lombard['lombardNow']);

		$em->persist($clientFiuHistory);
		$em->flush();

		return new JsonResponse(['success' => true]);
	}

	/**
	 * Получение Ломбарда, проверка доступа
	 */
	private function getLombard()
	{
		$user = $this->getUser();
		if (!$user) {
			return $this->redirect($this->generateUrl('security_login'));
		};

		$request = $this->get('request_stack')->getCurrentRequest();
		$session = $request->getSession();
		$em = $this->getDoctrine()->getManager();
		$staff = $em->getRepository('AdminStaffBundle:Staff')->findOneBy(array('user' => $user));
		$admin = false;
		$lombard = array();
		if (in_array('ROLE_ADMIN', $user->getRoles())) {
			$admin = true;
		}
		if ($request->request->get('cache_lombard')) {
			$session->set('cache_lombard', $request->request->get('cache_lombard'));
		}
		if (in_array('ROLE_ADMIN', $user->getRoles()) || in_array('ROLE_DIRECTOR', $user->getRoles())) {

			$lombard = $em->getRepository('AdminLombardBundle:Lombard')->findBy(array(), array('id' => 'ASC'));
			if ($session->get('cache_lombard')) {
				$lombardNow = $em->getRepository('AdminLombardBundle:Lombard')->find($session->get('cache_lombard'));
			} else {
				$lombardNow = $lombard[0];
			}
		} else if (in_array('ROLE_REGION', $user->getRoles())) {
			$lombard = $em->getRepository('AdminLombardBundle:Lombard')->findBy(array('referenceRegion' => $staff->getReferenceRegionsIds()), array('id' => 'ASC'));
			if ($session->get('cache_lombard')) {
				$lombardNow = $em->getRepository('AdminLombardBundle:Lombard')->find($session->get('cache_lombard'));
			} else {
				$lombardNow = $lombard[0];
			}
		} else {
			$lombardNow = $staff->getLombard();
		}

		return array('lombardNow' => $lombardNow, 'admin' => $admin, 'lombard' => $lombard, 'staff' => $staff);
	}

	private function checkClientFiu(Clients $client, bool $save = true): bool
	{
		$em = $this->getDoctrine()->getManager();

		$qb = $em->getRepository('SiteClientsBundle:ClientsFiu')
			->createQueryBuilder('cf')
			->where('lower(cf.lastname) LIKE lower(:lastname)')
			->andWhere('lower(cf.firstname) LIKE lower(:firstname)')
			->setParameter('lastname', $client->getLastname())
			->setParameter('firstname', $client->getFirstname());

		if (!empty($client->getSecondname())) {
			$qb->andWhere('lower(cf.secondname) LIKE lower(:secondname)')
				->setParameter('secondname', $client->getSecondname());
		}

		$clientsFiu = $qb->getQuery()->getResult();
		if ($save === true) {
			foreach ($clientsFiu as $clientFiu) {
				$this->clientFIUAction($clientFiu->getId());
			}
		}

		return count($clientsFiu) === 0;
	}

}
