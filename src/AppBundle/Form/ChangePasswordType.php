<?php

namespace AppBundle\Form;


use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class ChangePasswordType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła muszą być takie same',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => true,
                'first_options' => array(
                    'label' => 'Hasło:'
                ),
                'second_options' => array(
                    'label' => 'Powtórz hasło:'
                ),
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Proszę poprawnie wprowadzić hasło'
                    )),
                    new Regex(array(
                        'pattern' => "/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{5,15})\S$/",
                        'match' => true,
                        'message' => 'Hasło powinno składać się z co najmniej jednej liczby,
                               jednej małej oraz jednej dużej litery. Długość od 5 do 15 znaków'
                    ))
                )
            ))
            ->add('save', SubmitType::class, array(
               'label' => 'Zmień hasło'
            ));
    }

    public function getName()
    {
        return 'user';
    }

    public function setDefaultOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => 'AppBundle\Entity\User'
        ));
    }
}