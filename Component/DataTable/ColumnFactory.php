<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 23/05/17
 * Time: 20:03.
 */

namespace Umbrella\CoreBundle\Component\DataTable;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DataTable\Model\Column;
use Umbrella\CoreBundle\Component\DataTable\Type\ColumnType;

/**
 * Class ColumnFactory.
 */
class ColumnFactory
{
    /**
     * @var ContainerInterface
     */
    private $container;

    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * DataTableFactory constructor.
     *
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
        $this->translator = $container->get('translator');
    }

    /**
     * @param $typeClass
     * @param array $options
     *
     * @return Column
     */
    public function create($typeClass, array $options = array())
    {
        $type = $this->createType($typeClass);
        $column = new Column($this->translator);

        $resolver = new OptionsResolver();
        $column->configureOptions($resolver);
        $type->configureOptions($resolver);
        $resolvedOptions = $resolver->resolve($options);
        $column->setOptions($resolvedOptions);

        return $column;
    }


    /**
     * @param $typeClass
     * @return ColumnType
     */
    private function createType($typeClass)
    {
        if ($typeClass !== ColumnType::class && !is_subclass_of($typeClass, ColumnType::class)) {
            throw new \InvalidArgumentException("Class '$typeClass' must extends ColumnType class.");
        }

        if ($this->container->has($typeClass)) {
            return $this->container->get($typeClass);
        } else {
            return new $typeClass();
        }
    }
}
