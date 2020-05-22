<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 08/06/17
 * Time: 21:03
 */

namespace Umbrella\CoreBundle\Component\Column\Type;

use Umbrella\CoreBundle\Utils\HtmlUtils;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Liip\ImagineBundle\Imagine\Cache\CacheManager;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class ImageColumnType
 */
class ImageColumnType extends PropertyColumnType
{
    /**
     * @var CacheManager
     */
    protected $cacheManager;

    /**
     * ImageColumnType constructor.
     * @param CacheManager $cacheManager
     */
    public function __construct(CacheManager $cacheManager)
    {
        parent::__construct();
        $this->cacheManager = $cacheManager;
    }

    /**
     * @param $entity
     * @param  array       $options
     * @return null|string
     */
    public function render($entity, array $options)
    {
        $file = $this->accessor->getValue($entity, $options['property_path']);

        if (!$file instanceof UmbrellaFile) {
            return $options['html_empty'];
        }

        $attr = array_merge(['title' => $file->name], $options['image_attr']);
        $url = $file->getWebPath();
        if (!empty($options['imagine_filter'])) {
            $url = $this->cacheManager->getBrowserPath($url, $options['imagine_filter']);
        }
        return sprintf('<img src="%s" %s>', $url, HtmlUtils::array_to_html_attribute($attr));
    }

    /**
     * @inheritdoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        parent::configureOptions($resolver);

        $resolver
            ->setDefault('image_attr', [
                'width' => 80,
                'height' => 80
            ])
            ->setAllowedTypes('image_attr', 'array')

            ->setDefault('html_empty', '')
            ->setAllowedTypes('html_empty', 'string')

            ->setDefault('imagine_filter', null)
            ->setAllowedTypes('imagine_filter', ['null', 'string'])

            ->setDefault('class', 'text-center')
            ->setDefault('order_by', null);
    }
}
