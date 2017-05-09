<?php

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ObservationType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('species', EntityType::class, array(
                'class' => 'AppBundle\Entity\Species',
                'choice_label' => 'name',
                'label' => 'Wybierz gatunek:'
            ))
//            ->add('observationDate', DateType::class, array(
//                'widget' => 'choice',
//            ))
            ->add('location', TextType::class, array(
                'label' => 'Miejsce obserwacji'
            ))
            ->add('state', TextType::class, array(
                'label' => 'Województwo'
            ))
            ->add('latitude', NumberType::class, array(
                'label' => 'Szerokość geograficzna'
            ))
            ->add('longitude', NumberType::class, array(
                'label' => 'Długość geograficzna'
            ))
            ->add('description', TextType::class, array(
                'label' => 'Opis'
            ))
            ->add('images', FileType::class, array(
                'label' => 'Zdjęcie (plik jpg)'
            ))
            ->add('save', ButtonType::class, array(
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