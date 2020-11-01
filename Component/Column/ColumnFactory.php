<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\Column;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\Column\Type\ColumnType;

/**
 * Class ColumnFactory.
 */
class ColumnFactory
{
    /**
     * @var ColumnType[]
     */
    private $columnTypes = [];

    /**
     * @param $id
     * @param ColumnType $columnType
     */
    public function registerColumnType($id, ColumnType $columnType)
    {
        $this->columnTypes[$id] = $columnType;
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Column
     */
    public function create($typeClass, array $options = [])
    {
        $type = $this->createType($typeClass);
        $column = new Column();
        $column->setType($type);

        $resolver = new OptionsResolver();
        $column->configureOptions($resolver);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $column->setOptions($resolvedOptions);

        return $column;
    }

    /**
     * @param $typeClass
     *
     * @return ColumnType
     */
    private function createType($typeClass)
    {
        if (ColumnType::class !== $typeClass && !is_subclass_of($typeClass, ColumnType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends ColumnType class.");
        }

        if (array_key_exists($typeClass, $this->columnTypes)) {
            return $this->columnTypes[$typeClass];
        } else {
            return new $typeClass();
        }
    }
}
