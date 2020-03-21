<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 12/02/18
 * Time: 10:37
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class BadgeColumnType
 */
class BadgeColumnType extends PropertyColumnType
{

    /**
     * @var TranslatorInterface
     */
    protected $translator;

    /**
     * BadgeColumnType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        parent::__construct();
        $this->translator = $translator;
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $value = $this->accessor->getValue($entity, $options['property_path']);
        $class = isset($options['styles'][$value]) ? $options['styles'][$value] : $options['default_class'];

        $html = '<span class="badge ' . $class . '">';
        $html .= !empty($this->labelPrefix) ? $this->translator->trans($options['label_prefix'] . $value) : $value;
        $html .= '</span>';
        return $html;
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver->setDefined(array(
            'default_class',
            'label_prefix',
            'styles'
        ));

        $resolver->setAllowedTypes('default_class', 'string');
        $resolver->setAllowedTypes('label_prefix', 'string');
        $resolver->setAllowedTypes('styles', 'array');

        $resolver->setDefault('default_class', 'primary');
        $resolver->setDefault('label_prefix', '');
        $resolver->setDefault('styles', array());
    }
}