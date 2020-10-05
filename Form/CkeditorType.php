<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\FormView;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Umbrella\CoreBundle\Component\Ckeditor\CkeditorConfiguration;

/**
 * Class CkeditorType
 */
class CkeditorType extends AbstractType
{
    /**
     * @var CkeditorConfiguration
     */
    private $ckeditorConfig;

    /**
     * CkeditorType constructor.
     * @param CkeditorConfiguration $ckeditorConfig
     */
    public function __construct(CkeditorConfiguration $ckeditorConfig)
    {
        $this->ckeditorConfig = $ckeditorConfig;
    }

    /**
     * @inheritDoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['config'] = $form->getConfig()->getAttribute('config');
    }

    /**
     * @inheritDoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // resolve config
        $config = null === $options['config_name']
            ? $this->ckeditorConfig->getDefaultConfig()
            : $this->ckeditorConfig->getConfig($options['config_name']);

        $builder->setAttribute('config', array_merge($config, $options['config']));
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('config_name', null)
            ->setAllowedTypes('config_name', ['null', 'string'])

            ->setDefault('config', [])
            ->setAllowedTypes('config', ['array']);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return TextareaType::class;
    }

    /**
     * @inheritDoc
     */
    public function getBlockPrefix()
    {
        return 'ckeditor';
    }
}
