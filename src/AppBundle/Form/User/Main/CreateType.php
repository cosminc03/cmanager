<?php

namespace AppBundle\Form\User\Main;

use AppBundle\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Email;
use Symfony\Component\Validator\Constraints\NotBlank;

class CreateType extends AbstractType
{
    /**
     * @param FormBuilderInterface $builder
     * @param array                $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('username', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.username',
                    ]),
                ],
            ])
            ->add('email', EmailType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.email',
                    ]),
                    new Email([
                        'message' => 'invalid.email',
                    ]),
                ],
            ])
            ->add('firstName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.first_name',
                    ]),
                ],
            ])
            ->add('lastName', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.last_name',
                    ]),
                ],
            ])
            ->add('phone', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.phone',
                    ]),
                ],
            ])
            ->add('dateOfBirth', DateType::class, [
                'required' => false,
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.date_of_birth',
                    ]),
                ],
            ])
            ->add('nationality', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.nationality',
                    ]),
                ]
            ])
            ->add('citizenship', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.citizenship',
                    ]),
                ]
            ])
            ->add('gender', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'choices' => [
                    'gender.male' => 'gender.male',
                    'gender.female' => 'gender.female',
                ],
                'translation_domain' => 'messages',
            ])
            ->add('yearOfStudy', ChoiceType::class, [
                'required' => true,
                'multiple' => false,
                'choices' => [
                    'year_of_study.first' => 'year_of_study.first',
                    'year_of_study.second' => 'year_of_study.second',
                    'year_of_study.third' => 'year_of_study.third',
                ],
                'translation_domain' => 'messages',
            ])
            ->add('description', TextareaType::class)
            ->add('skype', TextType::class)
            ->add('linkedIn', TextType::class)
            ->add('twitter', TextType::class)
            ->add('gplus', TextType::class)
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
