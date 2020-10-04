<?php


namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;

/**
 * Class CkeditorType
 */
class CkeditorType extends AbstractType
{
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