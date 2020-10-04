<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/06/17
 * Time: 22:29
 */

namespace Umbrella\CoreBundle\Component\Action\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ExportActionType
 */
class ExportActionType extends ActionType
{
    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('icon', 'mdi mdi-file-download-outline mr-1')
            ->setDefault('class', 'btn btn-light ml-1');
    }
}
