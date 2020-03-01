<?php

namespace Pumukit\TemplateBundle\Form;

use Pumukit\NewAdminBundle\Form\Type\Base\TextareaI18nType;
use Pumukit\TemplateBundle\Document\Template;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class TemplateType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add(
                'hide',
                null,
                [
                    'required' => false,
                ]
            )
            ->add('name')
            ->add(
                'i18n_text',
                TextareaI18nType::class,
                [
                    'attr' => ['style' => 'height: 200px;'],
                    'label' => 'Text',
                ]
            )
        ;
    }

    public function configureOptions(OptionsResolver $resolver): void
    {
        $resolver->setDefaults(
            [
                'data_class' => Template::class,
            ]
        );
    }

    public function getBlockPrefix(): string
    {
        return 'pumukittemplate_template';
    }
}
