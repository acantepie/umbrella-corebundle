<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 13/05/17
 * Time: 12:46.
 */

namespace Umbrella\CoreBundle\Component\Table\Model;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Contracts\Translation\TranslatorInterface;
use Umbrella\CoreBundle\Component\Toolbar\Toolbar;

/**
 * Class TreeTable.
 */
class TreeTable extends Table
{
    /**
     * @var array()
     */
    private $query;

    /**
     * @inheritdoc
     */
    public function handleRequest(Request $request)
    {
        // TODO
    }

    /**
     * @inheritdoc
     */
    public function getApiResults()
    {
        $result = $this->source->search($this->options['data_class'], $this->columns, $this->query);
        // TODO
        return $result;
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('id', $this->defaultId)
            ->setAllowedTypes('id', 'string')

            ->setDefault('data_class', null)
            ->setAllowedTypes('data_class', ['string', 'null'])

            ->setDefault('attr', [
                'class' => 'table table-striped table-centered'
            ])
            ->setAllowedTypes('attr', ['array'])

            ->setDefault('row_class', null)
            ->setAllowedTypes('row_class', ['null', 'array', 'callable'])

            ->setDefault('template', '@UmbrellaCore/DataTable/datatable.html.twig')
            ->setAllowedTypes('template', 'string');

        // fixme
        $toolbar = new Toolbar();
        $toolbar->configureOptions($resolver);
    }

    /**
     * @inheritdoc
     */
    public function getViewOptions(TranslatorInterface $translator)
    {
        // TODO
    }
}
