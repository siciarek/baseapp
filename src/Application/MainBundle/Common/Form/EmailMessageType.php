<?php

namespace Application\MainBundle\Common\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints as C;
use Symfony\Component\Form\Extension\Core\Type as T;
use Ivory\CKEditorBundle\Form\Type\CKEditorType;

class EmailMessageType extends AbstractType
{

    /**
     * Priorities index
     * @var array
     */
    protected $priorities = [];

    public function __construct(array $priorities = [])
    {
        $this->priorities = $priorities;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('priority', T\ChoiceType::class, [
                    'trim' => true,
                    'choices' => $this->priorities,
                    'data' => $this->priorities['Normal'],
                ])
                ->add('name', T\TextType::class, [
                    'required' => false,
                    'trim' => true,
                ])
                ->add('email', T\TextType::class, [
                    'trim' => true,
                    'constraints' => [
                        new C\Email(),
                    ],
                ])
                ->add('subject', T\TextType::class, [
                    'trim' => true,
                    'constraints' => [
                        new C\NotBlank(),
                        new C\Length(['min' => 1, 'max' => 255]),
                    ],
                ])
                ->add('body', CKEditorType::class, [
                    'trim' => true,
                    'config_name' => 'email',
                    'constraints' => [
                        new C\NotBlank(),
                        new C\Length(['min' => 1, 'max' => 10000]),
                    ],
                ])
                ->add('attachments', T\FileType::class, [
                    'multiple' => true,
                    'required' => false,
                ])
        ;

        if (array_key_exists('render_submit_button', $options) and $options['render_submit_button'] == true) {
            $builder
                    ->add('save', T\SubmitType::class, [
                        'attr' => [
                            'class' => 'pull-right btn-default btn-lg'
                        ]
                    ])
            ;
        }
    }

    public function getBlockPrefix()
    {
        return 'email_message';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
            'render_submit_button' => false,
        ]);
    }

}
