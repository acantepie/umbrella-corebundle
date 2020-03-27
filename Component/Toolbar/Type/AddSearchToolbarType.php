<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 26/03/20
 * Time: 23:13
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;

use Doctrine\ORM\QueryBuilder;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarBuilder;
use Umbrella\CoreBundle\Form\SearchType;

/**
 * Class AddSearchToolbar
 */
class AddSearchToolbarType extends ToolbarType
{
    /**
     * @inheritdoc
     */
    public function buildToolbar(ToolbarBuilder $builder, array $options)
    {
        $builder->addAction('add', AddButtonActionType::class, array(
            'label' => $options['add_label'],
            'route' => $options['add_route'],
            'xhr' => $options['add_xhr'],
            'route_params' => $options['add_route_params']
        ));

        $builder->addFilter('search', SearchType::class);

        $builder->addSourceModifier(function(QueryBuilder $qb, array $data) {
            if (isset($data['form']['search'])) {
                $qb->andWhere('lower(e.search) LIKE :search');
                $qb->setParameter('search', '%' . strtolower($data['form']['search']) . '%');
            }
        });
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('add_xhr', true)
            ->setAllowedTypes('add_xhr', 'bool')

            ->setDefault('add_label', 'add_action')
            ->setAllowedTypes('add_label','string')

            ->setRequired('add_route')
            ->setAllowedTypes('add_route', 'string')

            ->setDefault('add_route_params', [])
            ->setAllowedTypes('add_route_params','array');

    }
}
