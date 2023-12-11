<?php

namespace Site\ClientsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;


class ClientsType extends AbstractType
{
	public function buildForm(FormBuilderInterface $builder, array $options)
	{
		$builder
			->add('lastname', null, [
				'label' => 'Фамилия'
			])
			->add('firstname', null, [
				'label' => 'Имя'
			])
			->add('secondname', null, [
				'label' => 'Отчество'
			])
			->add('referenceClientType', null, [
				'label' => 'Тип',
				'required' => true
			])
			->add('birthdate', DateType::class, array(
				'label' => 'Дата рождения',
				'widget' => 'single_text',
				'required' => false,
				'format' => 'd.MM.y',
				'attr' => ['class' => 'datepicker']
			))
			->add('referenceGender', null, [
				'label' => 'Пол'
			])
			->add('address', null, [
				'label' => 'Адрес'
			])
			->add('phone', null, [
				'label' => 'Телефон'
			])
			->add('smsPhone', null, [
				'label' => 'Номер для уведомлений об оплате (в формате 996ХХХХХХХХХ)'
			])
			->add('notSetSmsPhone', CheckboxType::class, [
				'mapped' => false,
				'label' => 'Нет номера для уведомлений об оплате',
				'required' => false
			])
			->add('identityCard', \Site\ClientsBundle\Form\ClientsIdentityCardType::class, [
				'data_class' => 'Site\ClientsBundle\Entity\ClientsIdentityCard'
			])
			->add('clientsChannels', ChoiceType::class, [
				'label' => false,
				'choices' => $options['channelChoices'],
				'multiple' => true,
				'mapped' => false,
				'data_class' => null,
				'expanded' => true,
				'data' => $options['clientChannels'],
				'attr' => [
					'class' => 'checkbox',
				]
			])
			->add('otherChannel', TextareaType::class, [
				'mapped' => false,
				'label' => false,
				'required' => false,
				'data' => $options['otherChannel']
			])
			->add('inn', null, [
				'label' => 'ИНН'
			])
			->add('referenceFamily', null, [
				'label' => 'Семейное положение'
			])
			->add('email')
			->add('note', null, [
				'label' => 'Примечание'
			])
			->add('bussiness', null, [
				'label' => 'Вид деятельности'
			])
			->add('referenceClientStatus', null, [
				'label' => 'Статус',
				'required' => true
			])
			->add('enabled', CheckboxType::class, [
				'label' => 'Разрешен',
				'required' => false
			])

			->add('referenceLegalForm', null, [
				'label' => 'Орг.-правовая форма',
				'attr' => ['class' => 'legal']
			])
			->add('legal_name', null, [
				'label' => 'Наименование',
				'attr' => array('class' => 'legal')
			])
			->add('legal_inn', null, [
				'label' => 'ИНН',
				'attr' => ['class' => 'legal']
			])
			->add('okpo', null, [
				'label' => 'ОКПО',
				'attr' => ['class' => 'legal']
			])
			->add('bank_account', null, [
				'label' => 'Банковские реквизиты',
				'attr' => ['class' => 'legal']
			])
			->add('tax', null, [
				'label' => 'Налоговая',
				'attr' => ['class' => 'legal']
			]);
	}

	public function configureOptions(OptionsResolver $resolver)
	{
		$resolver->setDefaults([
			'data_class' => 'Site\ClientsBundle\Entity\Clients',
			'channelChoices' => [],
			'clientChannels' => [],
			'otherChannel' => ''
		]);
	}

	public function getBlockPrefix()
	{
		return 'site_clientsbundle_clientstype';
	}

}
