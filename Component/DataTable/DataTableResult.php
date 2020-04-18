<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 22/03/20
 * Time: 19:32
 */

namespace Umbrella\CoreBundle\Component\DataTable;

/**
 * Class DataTableResult
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
    public $count;

    /***
     * @var iterable
     */
    public $data;

    /**
     * @inheritdoc
     */
    public function jsonSerialize()
    {
        return array(
            'draw' => $this->draw,
            'recordsTotal' => $this->count, // Total records, before filtering
            'recordsFiltered' => $this->count, // Total records, after filtering
            'data' => $this->data,
        );
    }
}