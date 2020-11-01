<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 02/04/20
 * Time: 00:57
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source\Modifier;

use Doctrine\ORM\QueryBuilder;

/**
 * Class EntitySearchModifier
 */
class EntitySearchModifier extends EntitySourceModifier
{
    /**
     * {@inheritdoc}
     */
    public function modifyQb(QueryBuilder $qb, array $formData)
    {
        if (isset($formData['search'])) {
            $qb->andWhere('lower(e.search) LIKE :search');
            $qb->setParameter('search', '%' . strtolower($formData['search']) . '%');
        }
    }
}
