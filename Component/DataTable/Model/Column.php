<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

use Symfony\Bridge\Twig\Form\TwigRenderer;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Form\FormRenderer;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Model\OptionsAwareInterface;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class Column.
 */
class Column implements OptionsAwareInterface
{
    /**
     * @var array
     */
    public $options;

    /**
     * @var string
     */
    public $id;

    /**
     * @var string
     */
    public $label;

    /**
     * @var bool
     */
    public $orderable;

    /**
     * @var array
     */
    public $orderBy;

    /**
     * @var string|null
     */
    public $order;

    /**
     * @var string
     */
    public $class;

    /**
     * @var array
     */
    public $style;

    /**
     * @var null|\Closure
     */
    public $renderer;

    /**
     * @var null|\Closure
     */
    public $labelRenderer;

    /**
     * @var bool
     */
    public $isSafeHtml;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * Column constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $entity
     *
     * @return string
     */
    public function render($entity)
    {
        $value = null;
        if (is_callable($this->renderer)) {
            $value = call_user_func($this->renderer, $entity, $this->options);
        } else {
            $value = (string)$entity;
        }

        return $this->isSafeHtml ? $value : htmlspecialchars($value);
    }

    /**
     * @param $translationPrefix
     * @return string
     */
    public function renderLabel($translationPrefix)
    {
        if (is_callable($this->labelRenderer)) {
            return call_user_func($this->labelRenderer, $this->options);
        }
        return empty($this->label) ? '' : $this->translator->trans($translationPrefix . $this->label);
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options = array())
    {
        $this->options = $options;

        $this->id = $options['id'];
        $this->label = ArrayUtils::get($options, 'label', $this->id);

        $this->orderable = ArrayUtils::get($options, 'orderable');
        $this->orderBy = (array) ArrayUtils::get($options, 'order_by', []);

        $this->order = ArrayUtils::get($options, 'order');
        $this->class = ArrayUtils::get($options, 'class');
        $this->style = ArrayUtils::get($options, 'style');
        $this->renderer = ArrayUtils::get($options, 'renderer');
        $this->labelRenderer = ArrayUtils::get($options, 'label_renderer');
        $this->isSafeHtml = ArrayUtils::get($options, 'is_safe_html');

        // deprecated
        if (isset($options['order_path'])) {
            @trigger_error('Option order_path is deprecated. Use order_by instead.');
        }
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setRequired(array(
            'id'
        ));

        $resolver->setDefined(array(
            'label',
            'order_path',
            'order_by',
            'orderable',
            'order',
            'class',
            'style',
            'renderer',
            'label_renderer',
            'is_safe_html'
        ));

        $resolver->setAllowedTypes('order_by', ['string', 'array']);
        $resolver->setAllowedTypes('orderable', 'bool');
        $resolver->setAllowedTypes('renderer', array('null', 'callable'));
        $resolver->setAllowedTypes('label_renderer', array('null', 'callable'));
        $resolver->setAllowedTypes('style', 'array');
        $resolver->setAllowedTypes('is_safe_html', 'bool');
        $resolver->setAllowedValues('order', ['ASC', 'DESC']);

        $resolver->setDefault('orderable', false);
        $resolver->setDefault('is_safe_html', true);
    }
}
