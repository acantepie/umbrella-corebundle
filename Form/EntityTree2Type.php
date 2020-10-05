<?php

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityRepository;
use Symfony\Component\Form\AbstractType;
use Umbrella\CoreBundle\Model\TreeNodeInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityTree2Type
 */
class EntityTree2Type extends AbstractType
{
    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('query_builder', function (EntityRepository $er) {
                return $er->createQueryBuilder('e')
                    ->orderBy('e.lft', 'ASC');
            })
            ->setDefault('select2_options', [
                'dropdownCssClass' => 'select2-tree-dropdown'
            ])
            ->setDefault('expose', function ($entity) {
                if (is_a($entity, TreeNodeInterface::class)) {
                    return [
                        'lvl' => $entity->getLvl(),
                        'indent' => range(0, $entity->getLvl())
                    ];
                }
            })
            ->setDefault('template_html', $this->getHtmlTemplate());
    }

    private function getHtmlTemplate()
    {
        $mLvl = '[[lvl]]';
        $mClass = 'select2-tree-option';
        $mText = '[[text]]';

        return sprintf('<span data-lvl="%s" class="%s"> <span class="value">%s</span></span>', $mLvl, $mClass, $mText);
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return Entity2Type::class;
    }
}
