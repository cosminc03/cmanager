<?php

namespace AppBundle\Form\Course;

use AppBundle\Entity\Announcement;
use AppBundle\Entity\Course;
use AppBundle\Entity\User;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
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
            ->add('title', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.title',
                    ]),
                ],
            ])
            ->add('abbreviation', TextType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.abbreviation',
                    ]),
                ],
            ])
            ->add('author', EntityType::class, [
                'required' => true,
                'class' => User::class,
                'choice_label' => 'username',
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.user',
                    ]),
                ],
                'placeholder' => 'placeholder.user',
                'translation_domain' => 'messages',
            ])
            ->add('linkToGrades', TextType::class, [
                'required' => false,
            ])
        ;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Course::class,
        ]);
    }
}
