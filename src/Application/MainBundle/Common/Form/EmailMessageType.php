<?php

namespace Application\MainBundle\Common\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class EmailMessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
                ->add('name', 'text', [
                    'required' => false,
                    'trim' => true,
                ])
                ->add('email', 'email', [
                    'trim' => true,
                ])
                ->add('subject', 'text', [
                    'trim' => true,
                ])
                ->add('body', 'ckeditor', [
                    'config_name' => 'email',
                    'trim' => true,
                ])
                ->add('attachments', 'file', [
                    'multiple' => true,
                    'required' => false,
                ])
                ->add('save', 'submit')
        ;
    }

    public function getName()
    {
        return 'email_message';
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => null,
        ]);
    }
}
