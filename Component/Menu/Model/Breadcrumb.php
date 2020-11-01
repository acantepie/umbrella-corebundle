<?php

namespace Umbrella\CoreBundle\Component\Menu\Model;

/**
 * Class Breadcrumb
 */
class Breadcrumb implements \IteratorAggregate, \Countable
{
    /**
     * @var BreadcrumbItem[]
     */
    protected $items = [];

    /**
     * Breadcrumb constructor.
     *
     * @param BreadcrumbItem[] $items
     */
    public function __construct(array $items = [])
    {
        $this->items = $items;
    }

    /**
     * @param BreadcrumbItem $item
     */
    public function addItem(array $options = [])
    {
        $this->items[] = BreadcrumbItem::create($options);
    }

    public function clear()
    {
        $this->items = [];
    }

    /**
     * Return first not empty icon found belongs items
     *
     * @return string|null
     */
    public function getIcon()
    {
        foreach ($this->items as $item) {
            if (!empty($item->icon)) {
                return $item->icon;
            }
        }

        return null;
    }

    /**
     * {@inheritdoc}
     */
    public function count()
    {
        return count($this->items);
    }

    /**
     * {@inheritdoc}
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->items);
    }
}
