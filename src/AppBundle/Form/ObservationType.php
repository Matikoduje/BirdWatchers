<?php

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\All;
use Symfony\Component\Validator\Constraints\File;
use Symfony\Component\Validator\Constraints\Image;
use Symfony\Component\Validator\Constraints\NotBlank;
use AppBundle\Form\ImageBirdType;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'name',
                'label' => 'Wybierz gatunek:',
                'required' => true
            ))
            ->add('observationDate', DateType::class, array(
                'widget' => 'choice',
                'required' => true
            ))
            ->add('location', TextType::class, array(
                'label' => 'Miejsce obserwacji'
            ))
            ->add('state', EntityType::class, array(
                'label' => 'Województwo',
                'required' => true,
                'class' => 'AppBundle\Entity\State',
                'choice_label' => 'name'
            ))
            ->add('latitude', NumberType::class, array(
                'label' => 'Szerokość geograficzna',
                'required' => true
            ))
            ->add('longitude', NumberType::class, array(
                'label' => 'Długość geograficzna',
                'required' => true
            ))
            ->add('description', TextType::class, array(
                'label' => 'Opis',
                'required' => true
            ))
            ->add('images', FileType::class, array(
                'label' => 'Zdjęcie (plik jpg)',
                'required' => true,
                'multiple' => true,
                'data_class' => null,
                'mapped' => false,
                'constraints' => array(
                    new All(array(
                        new Image(array(
                            'maxSize' => '500k',
                            'maxSizeMessage' => 'Zdjęcie nie może być większe niż 500kb',
                            'minHeight' => 400,
                            'minWidth' => 400,
                            'minWidthMessage' => 'Zdjęcie musi mieć większą rozdzielczość niż 400x400 px',
                            'minHeightMessage' => 'Zdjęcie musi mieć większą rozdzielczość niż 400x400 px',
                            'mimeTypes' => 'image/jpeg',
                            'mimeTypesMessage' => 'Zdjęcie musi być w formacie jpeg',
                            'disallowEmptyMessage' => 'Proszę wybrać przynajmniej jedno zdjęcie'
                        ))
                    ))
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Zapisz'
            ));
    }

    public function getName()
    {
        return 'observation';
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\Observation'
        ));
    }
}