<?php

namespace AppBundle\Form\Course\Main;

use AppBundle\Entity\Course;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Validator\Constraints\Regex;

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
            ->add('description', TextareaType::class, [
                'required' => true,
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.description',
                    ]),
                ],
            ])
            ->add('type', ChoiceType::class, [
                'multiple' => false,
                'choices' => [
                    'type.mandatory' => 'type.mandatory',
                    'type.optional' => 'type.optional',
                ],
                'choice_translation_domain' => 'messages',
            ])
            ->add('evaluation', ChoiceType::class, [
                'multiple' => false,
                'choices' => [
                    'evaluation.exam' => 'evaluation.exam',
                    'evaluation.ongoing' => 'evaluation.ongoing',
                ],
                'choice_translation_domain' => 'messages',
            ])
            ->add('courseHours', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.course_hours',
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$/',
                        'message' => 'regex.course_hours',
                    ])
                ]
            ])
            ->add('seminarHours', TextType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.seminar_hours',
                    ]),
                    new Regex([
                        'pattern' => '/^([1-9]+\d*)$/',
                        'message' => 'regex.seminar_hours',
                    ])
                ]
            ])
            ->add('content', CKEditorType::class, [
                'config' => [
                    'toolbar' => [
                        [
                            'name' => 'basic_styles',
                            'items' => [
                                'Bold',
                                'Italic',
                                'Underline',
                                'Strike',
                                'Subscript',
                                'Superscript',
                                '-',
                                'RemoveFormat',
                            ],
                        ],
                        [
                            'name' => 'clipboard',
                            'items' => [
                                'Cut',
                                'Copy',
                                'Paste',
                                'PasteText',
                                'PasteFromWord',
                                '-',
                                'Undo',
                                'Redo',
                            ],
                        ],
                        [
                            'name' => 'links',
                            'items' => [
                                'Link',
                                'Unlink',
                                'Anchor',
                            ],
                        ],
                        [
                            'name' => 'paragraph',
                            'items' => [
                                'NumberedList',
                                'BulletedList',
                                '-',
                                'Outdent',
                                'Indent',
                                '-',
                                'Blockquote',
                                'CreateDiv',
                                '-',
                                'JustifyLeft',
                                'JustifyCenter',
                                'JustifyRight',
                                'JustifyBlock',
                                '-',
                                'BidiLtr',
                                'BidiRtl',
                            ],
                        ],
                        [
                            'name' => 'styles',
                            'items' => [
                                'Styles',
                                'Format',
                                'Font',
                                'FontSize',
                                'TextColor',
                                'BGColor',
                            ],
                        ],
                    ],
                    'uiColor' => '#ffffff',
                ],
            ])
            ->add('bibliography', CKEditorType::class, [
                'config' => [
                    'toolbar' => [
                        [
                            'name' => 'basic_styles',
                            'items' => [
                                'Bold',
                                'Italic',
                                'Underline',
                                'Strike',
                                'Subscript',
                                'Superscript',
                                '-',
                                'RemoveFormat',
                            ],
                        ],
                        [
                            'name' => 'clipboard',
                            'items' => [
                                'Cut',
                                'Copy',
                                'Paste',
                                'PasteText',
                                'PasteFromWord',
                                '-',
                                'Undo',
                                'Redo',
                            ],
                        ],
                        [
                            'name' => 'links',
                            'items' => [
                                'Link',
                                'Unlink',
                                'Anchor',
                            ],
                        ],
                        [
                            'name' => 'paragraph',
                            'items' => [
                                'NumberedList',
                                'BulletedList',
                                '-',
                                'Outdent',
                                'Indent',
                                '-',
                                'Blockquote',
                                'CreateDiv',
                                '-',
                                'JustifyLeft',
                                'JustifyCenter',
                                'JustifyRight',
                                'JustifyBlock',
                                '-',
                                'BidiLtr',
                                'BidiRtl',
                            ],
                        ],
                        [
                            'name' => 'styles',
                            'items' => [
                                'Styles',
                                'Format',
                                'Font',
                                'FontSize',
                                'TextColor',
                                'BGColor',
                            ],
                        ],
                    ],
                    'uiColor' => '#ffffff',
                ],
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
