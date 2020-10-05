<?php

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\FormView;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\OptionsResolver\Options;
use Umbrella\CoreBundle\Model\TreeNodeInterface;
use Symfony\Component\Form\ChoiceList\View\ChoiceView;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Class EntityTree2Type
 */
class ParentEntityTree2Type extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * ParentEntityTree2Type constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @param FormView      $view
     * @param FormInterface $form
     * @param array         $options
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if (null !== $options['current_node']) {
            foreach ($view->vars['choices'] as &$choice) {
                if ($choice instanceof ChoiceView && $choice->data === $options['current_node']) {
                    $choice->attr['disabled'] = true;
                }
            }
        }
    }

    /**
     * @inheritDoc
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver
            ->setDefault('current_node', null)
            ->setAllowedTypes('current_node', ['null', TreeNodeInterface::class])

            ->setNormalizer('choices', function (Options $options, $value) {
                return $this->getChoices($options['class'], $options['current_node']);
            });
    }

    /**
     * @param $entityClass
     * @param TreeNodeInterface|null $treeNode
     * @retrun TreeNodeInterface[]
     */
    private function getChoices($entityClass, TreeNodeInterface $currentNode = null)
    {
        $qb = $this->em->createQueryBuilder();
        $qb->select('e');
        $qb->from($entityClass, 'e');
        $qb->orderBy('e.lft', 'ASC');

        $nodes = $qb->getQuery()->getResult();

        if (null === $currentNode || $currentNode->getId() === null) {
            return $nodes;
        }

        // exclude all child of currentNode
        return array_filter($nodes, function (TreeNodeInterface  $node) use ($currentNode) {
            return !$node->isChildOf($currentNode);
        });
    }

    /**
     * @inheritDoc
     */
    public function getParent()
    {
        return EntityTree2Type::class;
    }
}
