<?php

namespace AppBundle\Form;


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
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 15,
                        'minMessage' => 'Imię nie powinno być krótsze niż 3 znaki',
                        'maxMessage' => 'Imię nie powinno być dłuższe niż 15 znaków'
                    ))
                )
            ))
            ->add('surname', TextType::class, array(
                'label' => 'Imię',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Nazwisko nie powinno być krótsze niż 3 znaki',
                        'maxMessage' => 'Nazwisko nie powinno być dłuższe niż 20 znaków'
                    ))
                )
            ))
            ->add('city', TextType::class, array(
                'label' => 'Miejscowość',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 31,
                        'minMessage' => 'Miejscowosć nie powinna mieć mniej niż 3 znaki',
                        'maxMessage' => 'Miejscowość nie powinna mieć więcej niż 31 znaków'
                    ))
                )
            ))
            ->add('state', TextType::class, array(
                'label' => 'Województwo',
                'required' => false,
                'constraints' => array(
                    new Length(array(
                        'min' => 3,
                        'max' => 20,
                        'minMessage' => 'Nazwisko nie powinno być krótsze niż 3 znaki',
                        'maxMessage' => 'Nazwisko nie powinno być dłuższe niż 20 znaków'
                    ))
                )
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