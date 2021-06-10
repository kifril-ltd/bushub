<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\PasswordType;
use Symfony\Component\Form\Extension\Core\Type\RepeatedType;
use Symfony\Component\Form\Extension\Core\Type\TelType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\IsTrue;
use Symfony\Component\Validator\Constraints\Length;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Validator\Constraints\Type;

class UserFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('email', EmailType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите email'
                    ])
                ],
            ])
            ->add('firstName', TextType::class, [
                'label' => 'Имя',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите имя'
                    ])
                ],
            ])
            ->add('lastName', TextType::class, [
                'label' => 'Фамилия',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите фамилию'
                    ])
                ],
            ])
            ->add('patronymic', TextType::class, [
                'required' => false,
                'label' => 'Отчетсво',
            ])
            ->add('passportNumber', TextType::class, [
                'label' => 'Серия и номер паспорта',
                'constraints' => [
                    new NotBlank([
                        'message' => 'Введите номер паспорта'
                    ]),
                    new Length([
                        'min' => 10,
                        'max' => 10,
                        'exactMessage' => 'Серия и номер паспорта должы содержать ровно 10 цифр',
                    ]),
                    new Type([
                        'type' => 'digit',
                        'message' => 'Серия и номер паспорта должы содержать только цифры'
                    ]),
                ]
            ])
            ->add('phone', TelType::class, [
                'label' => 'Телефон',
                'required' => false,
                'constraints' => [
                    new Regex([
                        'pattern' => '/^(\+)?(\d{1,2})?[( .-]*(\d{3})[) .-]*(\d{3,4})[ .-]?(\d{4})$/',
                        'message' => 'Поле не соответствует формату телефонного номера'
                    ]),
                ]
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
