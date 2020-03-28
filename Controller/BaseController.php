<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 12:45.
 */

namespace Umbrella\CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\AppProxy\AppMessageBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\Type\DataTableType;
use Umbrella\CoreBundle\Component\DataTable\DataTableFactory;
use Umbrella\CoreBundle\Component\DataTable\Model\DataTable;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Toolbar\Model\Toolbar;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;
use Umbrella\CoreBundle\Component\Tree\Model\Tree;
use Umbrella\CoreBundle\Component\Tree\TreeFactory;

/**
 * Class BaseController.
 */
abstract class BaseController extends AbstractController
{

    const TOAST_KEY = 'TOAST';
    const TOAST_INFO = 'info';
    const TOAST_SUCCESS = 'success';
    const TOAST_WARNING = 'warning';
    const TOAST_ERROR = 'error';

    /**
     * @param $id
     * @param array $parameters
     * @param null  $domain
     * @param null  $locale
     *
     * @return string
     */
    protected function trans($id, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->trans($id, $parameters, $domain, $locale);
    }

    /**
     * @param $id
     * @param $number
     * @param array $parameters
     * @param null  $domain
     * @param null  $locale
     *
     * @return string
     */
    protected function transChoice($id, $number, array $parameters = array(), $domain = null, $locale = null)
    {
        return $this->get('translator')->transChoice($id, $number, $parameters, $domain, $locale);
    }


    /**
     * @param $className
     * @param null $persistentManagerName
     * @return \Doctrine\Common\Persistence\ObjectRepository
     */
    protected function getRepository($className, $persistentManagerName = null)
    {
        return $this->em($persistentManagerName)->getRepository($className);
    }

    /**
     * @param null $name
     *
     * @return EntityManagerInterface
     */
    protected function em($name = null)
    {
        return $this->get('doctrine')->getManager($name);
    }

    /**
     * @param $elem
     */
    protected function persistAndFlush($elem)
    {
        $this->em()->persist($elem);
        $this->em()->flush();
    }

    /**
     * @param $elem
     */
    protected function removeAndFlush($elem)
    {
        $this->em()->remove($elem);
        $this->em()->flush();
    }

    /**
     * @return AppMessageBuilder
     */
    protected function appMessageBuilder()
    {
        return $this->get(AppMessageBuilder::class);
    }

    /**
     * @param $type
     * @param array $options
     *
     * @return Toolbar
     */
    protected function createToolbar($type, array $options = array())
    {
        return $this->get(ToolbarFactory::class)->create($type, $options);
    }

    /**
     * @param $type
     * @param array $options
     *
     * @return Tree
     */
    protected function createTree($type, array $options = array())
    {
        return $this->get(TreeFactory::class)->create($type, $options);
    }

    /**
     * @param $type
     * @param array $options
     *
     * @return DataTable
     */
    protected function createTable($type, array $options = array())
    {
        return $this->get(DataTableFactory::class)->create($type, $options);
    }

    /**
     * @param array $options
     * @param string $type
     *
     * @return DataTableBuilder
     */
    protected function createTableBuilder(array $options = array(), $type = DataTableType::class)
    {
        return $this->get(DataTableFactory::class)->createBuilder($type, $options);
    }

    /**
     * @return MenuHelper
     */
    protected function getMenuHelper()
    {
        return $this->get(MenuHelper::class);
    }

    /**
     * @param $name
     * @return Menu
     */
    protected function getMenu($name)
    {
        return $this->getMenuHelper()->getMenu($name);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastInfo($id, array $params = array())
    {
        $this->toast($id, $params, self::TOAST_INFO);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastSuccess($id, array $params = array())
    {
        $this->toast($id, $params, self::TOAST_SUCCESS);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastWarning($id, array $params = array())
    {
        $this->toast($id, $params, self::TOAST_WARNING);
    }

    /**
     * @param $id
     * @param array $params
     */
    protected function toastError($id, array $params = array())
    {
        $this->toast($id, $params, self::TOAST_ERROR);
    }

    /**
     * @param $id
     * @param array $params
     * @param string $level
     */
    protected function toast($id, array $params = array(), $level = self::TOAST_INFO)
    {
        $this->toastMsg($this->trans($id, $params), $level);
    }

    /**
     * @param $id
     * @param array $params
     * @param string $level
     */
    protected function toastMsg($msg, $level = self::TOAST_INFO)
    {
        $toasts = $this->get('session')->getFlashBag()->get(self::TOAST_KEY);
        $toasts[] = array(
            'type' => $level,
            'message' => $msg
        );
        $this->get('session')->getFlashBag()->set(self::TOAST_KEY, $toasts);
    }

    /**
     * @param $className
     * @param $id
     * @return object
     */
    protected function findOrNotFound($className, $id)
    {
        $e = $this->em()->find($className, $id);
        $this->throwNotFoundExceptionIfNull($e);
        return $e;
    }

    /**
     * @param $target
     * @param string $message
     */
    protected function throwNotFoundExceptionIfNull($target, $message = 'Not Found')
    {
        if ($target === null) {
            throw $this->createNotFoundException($message);
        }
    }

    /**
     * @param $target
     * @param string $message
     */
    protected function throwAccessDeniedExceptionIfFalse($target, $message = '')
    {
        if ($target === false) {
            throw $this->createAccessDeniedException($message);
        }
    }

    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            array(
                ToolbarFactory::class,
                DataTableFactory::class,
                TreeFactory::class,
                AppMessageBuilder::class,
                'translator' => TranslatorInterface::class
            )
        );
    }
}
