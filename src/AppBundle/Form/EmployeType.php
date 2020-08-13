<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;


class EmployeType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nom')->add('prenom')->add('situationFamiliale', ChoiceType::class, array(
            'label' => 'Situation Familiale',
            'choices' => array(
                'Celibataire' => 'Celibataire',
                'Mariée' => 'Mariée',
            ),
            'expanded' => true,
        ))->add('adresse')->add('dateEmbauche' , null ,['label' => 'Date d\'embauche '])->add('cin')->add('tel' , TelType::class)->add('email' , EmailType::class)->add('cnss')->add('idMarche' ,null,['label' => 'Marche Actuelle']);
    }/**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Employe'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'appbundle_employe';
    }


}
