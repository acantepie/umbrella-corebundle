<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/07/18
 * Time: 12:06
 */

namespace Umbrella\CoreBundle\Component\DataTable\Type;

use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class ToggleColumnType
 */
class ToggleColumnType extends PropertyColumnType
{
    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * ToggleColumnType constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
        parent::__construct();
    }

    /**
     * @param $entity
     * @param array $options
     * @return string
     */
    public function render($entity, array $options)
    {
        $bool = !!$this->accessor->getValue($entity, $options['property_path']);

        $route = $bool ? $options['false_route'] : $options['true_route'];
        $params = $bool ? $options['false_route_params'] : $options['true_route_params'];
        $params['id'] = $entity->id;


        if ($route) {
            $html = '<a data-xhr-href="' . $this->router->generate($route, $params) . '">';
        } else {
            $html = '';
        }

        $html .= $bool ? '<i class="fa fa-toggle-on text-success"></i></a>' : '<i class="fa fa-toggle-off"></i>';

        if ($route) {
            $html .= '<a>';
        }

        return $html;
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
         parent::configureOptions($resolver);

         $resolver->setDefaults(array(
             'class' => 'disable-row-click text-center',

             'true_route' => null,
             'true_route_params' => array(),

             'false_route' => null,
             'false_route_params' => array()
         ));
    }
}