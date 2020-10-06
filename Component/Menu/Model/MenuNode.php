<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 16:05.
 */

namespace Umbrella\CoreBundle\Component\Menu\Model;

use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class MenuNode.
 */
class MenuNode implements \IteratorAggregate, \Countable
{
    const TYPE_ROOT = 'root';
    const TYPE_TITLE = 'title';
    const TYPE_NODE = 'node';

    /**
     * @var string
     */
    public $type;

    /**
     * @var MenuNode
     */
    public $parent;

    /**
     * @var array
     */
    public $children = [];

    /**
     * @var string
     */
    public $icon;

    /**
     * @var string
     */
    public $label;

    /**
     * @var string
     */
    public $labelPrefix;

    /**
     * @var string
     */
    public $translationDomain;

    /**
     * @var string
     */
    public $url;

    /**
     * @var string
     */
    public $route;

    /**
     * @var array
     */
    public $routeParams = [];

    /**
     * @var string
     */
    public $target;

    /**
     * @var string
     */
    public $security;

    /**
     * whether the item is current. null means unknown
     * @var boolean|null
     */
    public $isCurrent = null;

    /**
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->type = $options['type'];
        $this->label = $options['label'];
        $this->labelPrefix = $options['label_prefix'];
        $this->translationDomain = $options['translation_domain'];
        $this->icon = $options['icon'];
        $this->security = $options['security'];
        $this->url = $options['url'];
        $this->route = $options['route'];
        $this->routeParams = $options['route_params'];
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setRequired('id')
            ->setAllowedTypes('id', 'string')

            ->setDefault('type', MenuNode::TYPE_NODE)
            ->setAllowedValues('type', [MenuNode::TYPE_TITLE, MenuNode::TYPE_NODE])

            ->setDefault('label', function (Options $options) {
                return $options['id'];
            })
            ->setAllowedTypes('label', 'string')

            ->setDefault('label_prefix', 'menu.')
            ->setAllowedTypes('label_prefix', ['null', 'string'])

            ->setDefault('translation_domain', 'messages')
            ->setAllowedTypes('translation_domain', ['null', 'string'])

            ->setDefault('icon', null)
            ->setAllowedTypes('icon', ['null', 'string'])

            ->setDefault('security', null)
            ->setAllowedTypes('security', ['null', 'string'])

            ->setDefault('url', null)
            ->setAllowedTypes('url', ['null', 'string'])

            ->setDefault('route', null)
            ->setAllowedTypes('route', ['null', 'string'])

            ->setDefault('route_params', [])
            ->setAllowedTypes('route_params', ['array'])

            ->setDefault('children', [])
            ->setAllowedTypes('children', ['array']);
    }

    /**
     * @param bool $current
     */
    public function setCurrent($current = true)
    {
        $this->isCurrent = $current;
    }

    /**
     * @return int|mixed
     */
    public function getLevel()
    {
        return $this->parent ? $this->parent->getLevel() + 1 : 0;
    }

    /**
     * @param $id
     * @param  MenuNode $child
     * @return $this
     */
    public function addChild($id, MenuNode $child)
    {
        if (array_key_exists($id, $this->children)) {
            throw new \InvalidArgumentException(sprintf('Cannot use "%s" node id, id is already used by sibling.', $id));
        }

        $child->parent = $this;
        $this->children[$id] = $child;

        return $this;
    }

    /**
     * @return bool
     */
    public function hasChildren()
    {
        return count($this->children) > 0;
    }

    /**
     * @return \ArrayIterator
     */
    public function getIterator()
    {
        return new \ArrayIterator($this->children);
    }

    /**
     * @return int
     */
    public function count()
    {
        return count($this->children);
    }

    /**
     * @return array
     */
    public function getBreadcrumbView()
    {
        return [
            'label' => $this->label,
            'label_prefix' => $this->labelPrefix,
            'translation_domain' => $this->translationDomain,
            'url' => $this->url,
            'icon' => $this->icon,
            'route' => $this->route,
            'route_params' => $this->routeParams,
        ];
    }
}
