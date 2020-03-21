<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 28/05/17
 * Time: 14:06.
 */

namespace Umbrella\CoreBundle\Component\Toolbar\Type;


use Doctrine\ORM\QueryBuilder;
use Symfony\Component\Form\FormBuilderInterface;
use Umbrella\CoreBundle\Form\AddonTextType;
use Umbrella\CoreBundle\Form\SearchType;

/**
 * Class SearchToolbar.
 */
class SearchToolbarType extends ToolbarType
{
    /**
     * @inheritdoc
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('search', SearchType::class);
    }

    /**
     * @inheritdoc
     */
    public function filter(QueryBuilder $qb, $data)
    {
        if ($data['search']) {
            $qb->andWhere('lower(e.search) LIKE :search')->setParameter('search', '%'.strtolower($data['search']).'%');
        }
    }
}
