<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 01/06/17
 * Time: 23:36
 */

namespace Umbrella\CoreBundle\Annotation;

use Doctrine\Common\Annotations\Annotation\Target;

/**
 * Class Searchable
 *
 * @Annotation
 * @Target("CLASS")
 */
class Searchable
{
    /**
     * @var string
     */
    private $searchField = 'search';

    /**
     * Searchable constructor.
     * @param array $options
     */
    public function __construct(array $options)
    {
        if (isset($options['searchField'])) {
            $this->searchField = $options['searchField'];
        }
    }

    /**
     * @return string
     */
    public function getSearchField()
    {
        return $this->searchField;
    }
}
