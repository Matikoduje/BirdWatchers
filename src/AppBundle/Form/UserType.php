<?php

namespace AppBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

class UserType extends AbstractType
{

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('login', TextType::class, array(
                'label' => 'Login:',
                'required' => 'true',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Proszę wprowadzić poprawny login'
                    )),
                    new Length(array(
                        'min' => 5,
                        'max' => 15,
                        'minMessage' => 'Login powienien posiadać od 5 do 15 znaków',
                        'maxMessage' => 'Login powienien posiadać od 5 do 15 znaków'
                    ))
                )
            ))
            ->add('email', EmailType::class, array(
                'label' => 'E-mail:',
                'required' => 'true',
                'constraints' => array(
                    new NotBlank(array(
                        'message' => 'Proszę wprowadzić poprawny adres e-mail'
                    )),
                    new Email(array(
                        'message' => 'Podany adres e-mail nie jest poprawny'
                    )),
                    new Length(array(
                        'max' => 50,
                        'maxMessage' => 'Email powinien być krótszy niż 50 znaków'
                    ))
                )
            ))
            ->add('plainPassword', RepeatedType::class, array(
                'type' => PasswordType::class,
                'invalid_message' => 'Hasła muszą być takie same',
                'options' => array('attr' => array('class' => 'password-field')),
                'required' => 'true',
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
                    new Length(array(
                        'min' => 5,
                        'max' => 15,
                        'minMessage' => 'Hasło powienno posiadać od 5 do 15 znaków',
                        'maxMessage' => 'Hasło powienno posiadać od 5 do 15 znaków'
                    )),
                    new Regex(array(
                        'pattern' => '/^((?=\S*?[A-Z])(?=\S*?[a-z])(?=\S*?[0-9]).{5,15})\S$/',
                        'match' => true,
                        'message' => 'Hasło powinno składać się z co najmniej jednej liczby, jednej małej oraz jednej dużej litery'
                    ))
                )
            ))
            ->add('save', SubmitType::class, array(
                'label' => 'Zapisz'
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