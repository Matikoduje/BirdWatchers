<?php

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Length;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Imię',
                'required' => false
            ))
            ->add('surname', TextType::class, array(
                'label' => 'Imię',
                'required' => false
            ))
            ->add('city', TextType::class, array(
                'label' => 'Miejscowość',
                'required' => false
            ))
            ->add('state', EntityType::class, array(
                'label' => 'Województwo',
                'required' => false,
                'class' => 'AppBundle\Entity\State',
                'choice_label' => 'name'
            ))
            ->add('profilePicture', FileType::class, array(
                'label' => 'Dodaj zdjęcie profilowe',
                'required' => false,
                'data_class' => null
            ))
            ->add('save', SubmitType::class, array(
               'label' => 'Zapisz',
            ));
    }

    public function getName()
    {
        return 'userProfile';
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\UserProfile'
        ));
    }
}