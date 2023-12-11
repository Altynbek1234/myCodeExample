<?php

namespace Site\ClientsBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ClientsIdentityCardType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('referenceDocumentType', null, [
                'label'=> 'Вид документа'
            ])
            ->add('number', null, [
                'label'=> 'Номер документа'
            ])
            ->add('date_issue', DateType::class, [
                'label'=> 'Дата выдачи',
                'widget'=> 'single_text',
                'format'=> 'd.MM.y',
                'attr'=> ['class'=> 'datepicker']
            ])
            ->add('department', null, [
                'label'=> 'Орган выдавший документ'
            ])
            ->add('validity', DateType::class, [
                'required'=> false,
                'label'=> 'Срок действия',
                'widget'=> 'single_text',
                'format'=> 'd.MM.y',
                'attr'=> ['class'=> 'datepicker']
            ])
            ->add('referenceCountry', null, [
                'label'=> 'Гражданство'
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'Site\ClientsBundle\Entity\ClientsIdentityCard'
        ));
    }

    public function getBlockPrefix()
    {
        return 'site_clientsbundle_clientsidentitycardtype';
    }
}
