<?php
/**
 * Created by PhpStorm.
 * User: mat
 * Date: 06.05.17
 * Time: 15:34
 */

namespace AppBundle\Form;


use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
                'label' => 'Wybierz gatunek'
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
            ->add('coordinates', TextType::class, array(
                'label' => 'Współrzędne'
            ))
            ->add('description', TextType::class, array(
                'label' => 'Opis'
            ))
            ->add('images', TextType::class, array(
                'label' => 'Zdjęcie'
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