<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 02/04/20
 * Time: 00:57
 */

namespace Umbrella\CoreBundle\Component\Table\Source;

use Doctrine\ORM\QueryBuilder;

/**
 * Class EntitySearchModifier
 */
class EntitySearchModifier extends AbstractSourceModifier
{
    /**
     * @param array $args
     * @return mixed
     */
    public function modify(array $args)
    {
        /** @var QueryBuilder $qb */
        $qb = $args['qb'];
        $data = $args['query'];

        if (isset($data['form']['search'])) {
            $qb->andWhere('lower(e.search) LIKE :search');
            $qb->setParameter('search', '%' . strtolower($data['form']['search']) . '%');
        }
    }
}