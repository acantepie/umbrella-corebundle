<?php

/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 07/05/17
 * Time: 12:45.
 */

namespace Umbrella\CoreBundle\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\DataTable\DataTableBuilder;
use Umbrella\CoreBundle\Component\DataTable\DataTableFactory;
use Umbrella\CoreBundle\Component\DataTable\Model\AbstractDataTable;
use Umbrella\CoreBundle\Component\JsResponse\JsResponseBuilder;
use Umbrella\CoreBundle\Component\Menu\MenuHelper;
use Umbrella\CoreBundle\Component\Menu\Model\Breadcrumb;
use Umbrella\CoreBundle\Component\Menu\Model\Menu;
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

    public static function getSubscribedServices()
    {
        return array_merge(
            parent::getSubscribedServices(),
            [
                MenuHelper::class,
                ToastFactory::class,
                ToolbarFactory::class,
                DataTableFactory::class,
                JsResponseBuilder::class,
                'translator' => TranslatorInterface::class,
            ]
        );
    }

    /**
     * @param $id
     * @param array $parameters
     * @param null  $domain
     * @param null  $locale
     *
     * @return string
     */
    protected function trans($id, array $parameters = [], $domain = null, $locale = null)
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
    protected function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->get('translator')->transChoice($id, $number, $parameters, $domain, $locale);
    }

    /**
     * @param $className
     * @param null $persistentManagerName
     *
     * @return EntityRepository
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
        return $this->getDoctrine()->getManager($name);
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
    protected function createToolbar($type, array $options = [])
    {
        return $this->get(ToolbarFactory::class)->create($type, $options);
    }

    /**
     * @param $type
     * @param array $options
     *
     * @return AbstractDataTable
     */
    protected function createTable($type, array $options = [])
    {
        return $this->get(DataTableFactory::class)->create($type, $options);
    }

    /**
     * @param array  $options
     * @param string $type
     *
     * @return DataTableBuilder
     */
    protected function createTableBuilder(array $options = [], $type = AbstractDataTable::class)
    {
        return $this->get(DataTableFactory::class)->createBuilder($type, $options);
    }

    /**
     * @return MenuHelper
     */
    protected function menuHelper()
    {
        return $this->get(MenuHelper::class);
    }

    /**
     * @param null $name
     *
     * @return Menu
     */
    protected function getMenu($name = null)
    {
        return $this->menuHelper()->getMenu($name);
    }

    /**
     * @param null $name
     *
     * @return Breadcrumb
     */
    protected function getBreadcrumb($name = null)
    {
        return $this->menuHelper()->getBreadcrumb($name);
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastInfo($transId, array $transParams = [])
    {
        return $this->toast($this->get(ToastFactory::class)->createInfo($transId, $transParams));
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastSuccess($transId, array $transParams = [])
    {
        return $this->toast($this->get(ToastFactory::class)->createSuccess($transId, $transParams));
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastWarning($transId, array $transParams = [])
    {
        return $this->toast($this->get(ToastFactory::class)->createWarning($transId, $transParams));
    }

    /**
     * @param $transId
     * @param array $transParams
     */
    protected function toastError($transId, array $transParams = [])
    {
        return $this->toast($this->get(ToastFactory::class)->createError($transId, $transParams));
    }

    /**
     * @param Toast $toast
     */
    protected function toast(Toast $toast)
    {
        /** @var Session $session */
        $session = $this->get('session');
        $session->getFlashBag()->add(self::BAG_TOAST, $toast);
    }

    /**
     * @param $className
     * @param $id
     *
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
        if (null === $target) {
            throw $this->createNotFoundException($message);
        }
    }

    /**
     * @param $target
     * @param string $message
     */
    protected function throwAccessDeniedExceptionIfFalse($target, $message = '')
    {
        if (false === $target) {
            throw $this->createAccessDeniedException($message);
        }
    }
}
