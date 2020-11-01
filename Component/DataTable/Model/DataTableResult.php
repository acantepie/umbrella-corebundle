<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 19:32
 */

namespace Umbrella\CoreBundle\Component\DataTable\Model;

/**
 * Class TableResult
 */
class DataTableResult implements \JsonSerializable
{
    /**
     * @var string
     */
    public $draw;

    /**
     * @var int
     */
    public $count = 0;

    /***
     * @var iterable
     */
    public $data = [];

    /**
     * {@inheritdoc}
     */
    public function jsonSerialize()
    {
        return [
            'draw' => $this->draw,
            'recordsTotal' => $this->count, // Total records, before filtering
            'recordsFiltered' => $this->count, // Total records, after filtering
            'data' => $this->data,
        ];
    }
}
