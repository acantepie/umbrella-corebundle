<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 16/04/18
 * Time: 19:30
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Utils\StringUtils;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class CheckBoxColumnType
 */
class CheckBoxColumnType extends ColumnType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;


    /**
     * CheckBoxColumnType constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        return $this->columnTemplate(StringUtils::random(8));
    }

    /**
     * @param $htmlId
     * @return string
     */
    private function columnTemplate($htmlId)
    {
        return '<div class="custom-control custom-control-lg custom-checkbox">'
            . '<input type="checkbox" id="cb-' . $htmlId . '" class="custom-control-input">'
            . '<label class="checkbox-custom custom-control-label" for="cb-' . $htmlId . '">'
            . '</label>'
            . '</div>';
    }

    /**
     * @return string
     */
    private function labelTemplate()
    {
        return '<div class="dropdown">'
            . '<button class="btn btn-sm p-0 w-100" type="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">'
            . '<i class="mdi mdi-dots-vertical"></i>'
            . '</button>'
            . '<div class="dropdown-menu">'
            . '<a class="dropdown-item js-action-select" href="#" data-filter="all">' . $this->translator->trans('common.all') . '</a>'
            . '<a class="dropdown-item js-action-select" href="#" data-filter="none">' . $this->translator->trans('common.none') . '</a>'
            . '</div>';
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $htmlId = StringUtils::random(8);

        $resolver
            ->setDefault('order_by', null)
            ->setDefault('class', 'text-center js-select')
            ->setDefault('renderer', [$this, 'render'])
            ->setDefault('label', $this->labelTemplate())
            ->setDefault('label_prefix', null)
            ->setDefault('translation_domain', null)
            ->setDefault('width', '80px');
    }
}
