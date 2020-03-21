<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/04/18
 * Time: 15:33
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\Translation\TranslatorInterface;

/**
 * Class EnableColumnType
 */
class EnableColumnType extends PropertyColumnType
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * EnableColumnType constructor.
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
        switch ($this->accessor->getValue($entity, $options['property_path'])) {
            case true:
                return '<span class="badge badge-feather-success"><i class="material-icons mr-1">check</i> ' . $this->translator->trans('common.enable') . '</span>';

            case false:
                return '<span class="badge badge-feather-danger"><i class="material-icons mr-1">cancel</i> ' . $this->translator->trans('common.disabled') . '</span>';

            default:
                return '';
        }
    }


}