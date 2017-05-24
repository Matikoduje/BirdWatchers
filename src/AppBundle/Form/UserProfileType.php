<?php

namespace AppBundle\Form;


use AppBundle\Entity\UserProfile;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Image;

class UserProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('name', TextType::class, array(
                'label' => 'Imię:',
                'required' => false
            ))
            ->add('surname', TextType::class, array(
                'label' => 'Nazwisko',
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
            ->add('uploadFile', FileType::class, array(
                'label' => 'Dodaj zdjęcie profilowe',
                'required' => false,
                'constraints' => array(
                    new Image(array(
                        'maxSize' => '300k',
                        'maxSizeMessage' => 'Zdjęcie nie może być większe niż 300kb',
                        'minHeight' => 160,
                        'minWidth' => 160,
                        'minWidthMessage' => 'Zdjęcie musi mieć większą rozdzielczość niż 160x160 px',
                        'minHeightMessage' => 'Zdjęcie musi mieć większą rozdzielczość niż 160x160 px',
                        'maxHeight' => 300,
                        'maxWidth' => 300,
                        'maxWidthMessage' => 'Zdjęcie musi mieć mniejszą rozdzielczość niż 200x200 px',
                        'maxHeightMessage' => 'Zdjęcie musi mieć mniejszą rozdzielczość niż 200x200 px',
                        'mimeTypes' => 'image/jpeg',
                        'mimeTypesMessage' => 'Zdjęcie musi być w formacie jpeg'
                    ))
                )
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
            'data_class' => UserProfile::class
        ));
    }
}