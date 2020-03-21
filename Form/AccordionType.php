<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 05/01/19
 * Time: 18:45
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\Valid;

/**
 * Class AccordionType
 */
class AccordionType extends AbstractType
{

    /**
     * @inheritdoc
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['collapsed'] = $options['collapsed'];
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'inherit_data' => true,
            'collapsed' => false,
            'constraints' => array(new Valid()),
        ));

        $resolver->setAllowedTypes('collapsed', 'boolean');
    }

}