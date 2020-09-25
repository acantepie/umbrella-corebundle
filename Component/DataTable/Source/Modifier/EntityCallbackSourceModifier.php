<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 27/03/20
 * Time: 22:44
 */

namespace Umbrella\CoreBundle\Component\DataTable\Source\Modifier;

use Doctrine\ORM\QueryBuilder;

/**
 * Class EntityCallbackSourceModifier
 */
class EntityCallbackSourceModifier extends EntitySourceModifier
{
    /**
     * @var callable
     */
    private $callback;

    /**
     * SourceModifier constructor.
     * @param callable $callback
     * @param int      $priority
     */
    public function __construct(callable $callback, $priority = 0)
    {
        parent::__construct($priority);
        $this->callback = $callback;
    }

    /**
     * @inheritDoc
     */
    public function modifyQb(QueryBuilder  $qb, array $queryData)
    {
        call_user_func($this->callback, $qb, $queryData);
    }
}
