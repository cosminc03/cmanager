<?php

namespace AppBundle\Form\Announcement\Main;

use AppBundle\Entity\Announcement;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;
use Symfony\Component\Form\AbstractType;
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
            ->add('content', CKEditorType::class, [
                'constraints' => [
                    new NotBlank([
                        'message' => 'not_blank.content',
                    ]),
                ],
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
            'data_class' => Announcement::class,
        ]);
    }
}
