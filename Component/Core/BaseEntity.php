<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 18:50.
 */

namespace Umbrella\CoreBundle\Component\Core;

use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Annotation\Searchable;
use Umbrella\CoreBundle\Model\TimestampableTrait;

/**
 * Class BaseEntity.
 *
 * @ORM\MappedSuperclass
 * @ORM\HasLifecycleCallbacks
 *
 * @Searchable(searchField="search")
 */
abstract class BaseEntity
{
    use TimestampableTrait;

    /**
     * @ORM\Column(type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    public $id;

    /**
     * @ORM\Column(type="text", nullable=true)
     */
    public $search;

    /**
     * @return string
     */
    public function __toString()
    {
        return (string)$this->id;
    }

    public function touch()
    {
        $this->_timestampable_preUpdate();
    }


}
