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
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\JsResponse\JsResponseBuilder;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
use Umbrella\CoreBundle\Component\Table\Model\Table;
use Umbrella\CoreBundle\Component\Table\TableBuilder;
use Umbrella\CoreBundle\Component\Table\TableFactory;
use Umbrella\CoreBundle\Component\Toast\Toast;
use Umbrella\CoreBundle\Component\Toast\ToastFactory;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;
use Umbrella\CoreBundle\Component\Toolbar\ToolbarFactory;

/**
 * Class BaseController.
 */
abstract class BaseController extends AbstractController
{
    const BAG_TOAST = 'toast';

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
     * @return JsResponseBuilder
     */
    protected function jsResponseBuilder()
    {
        return $this->get(JsResponseBuilder::class);
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
     * @return Table
     */
    protected function createTable($type, array $options = array())
    {
        return $this->get(TableFactory::class)->create($type, $options);
    }

    /**
     * @param array $options
     * @param string $type
     *
     * @return TableBuilder
     */
    protected function createTableBuilder(array $options = array(), $type = Table::class)
    {
        return $this->get(TableFactory::class)->createBuilder($type, $options);
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
     * @param $transId
     * @param array $transParams
     */
    protected function toastInfo($transId, array $transParams = array())
    {
        return $this->addBagToast($this->get(ToastFactory::class)->createInfo($transId, $transParams));
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastSuccess($transId, array $transParams = array())
    {
        return $this->addBagToast($this->get(ToastFactory::class)->createSuccess($transId, $transParams));
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastWarning($transId, array $transParams = array())
    {
        return $this->addBagToast($this->get(ToastFactory::class)->createWarning($transId, $transParams));
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastError($transId, array $transParams = array())
    {
        return $this->addBagToast($this->get(ToastFactory::class)->createError($transId, $transParams));
    }

    /**
     * @param Toast $toast
     */
    protected function addBagToast(Toast $toast)
    {
        /** @var Session $session */
        $session = $this->get('session');
        $session->getFlashBag()->add(self::BAG_TOAST, $toast);
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
                ToastFactory::class,
                ToolbarFactory::class,
                TableFactory::class,
                JsResponseBuilder::class,
                'translator' => TranslatorInterface::class
            )
        );
    }
}
