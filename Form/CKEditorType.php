<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/07/17
 * Time: 19:34
 */

namespace Umbrella\CoreBundle\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Routing\RouterInterface;

/**
 * Class CKEditorType
 */
class CKEditorType extends AbstractType
{
    const CSS_CLASS_BOXED = 'js-ckeditor';
    const CSS_CLASS_INLINE = 'js-ckeditor-inline';

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * AdvantageType constructor.
     * @param RouterInterface $router
     */
    public function __construct(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param null $type
     * @return array
     */
    public function buildCKEditorOptions($type = null)
    {
        $toolbarFull = [
            ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
            ['name' => 'styles', 'items' => ['Format']],
            ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']],
            ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList', '-', 'Outdent', 'Indent', '-', 'Blockquote']],
            ['name' => 'links', 'items' => ['Link', 'Unlink']],
            ['name' => 'insert', 'items' => ['Image', 'oembed', 'Table']],
            ['name' => 'tools', 'items' => ['Maximize', 'Scayt', 'Source']]
        ];

        $toolbarMin = [
            ['name' => 'clipboard', 'items' => ['Undo', 'Redo']],
            ['name' => 'styles', 'items' => ['Format']],
            ['name' => 'basicstyles', 'items' => ['Bold', 'Italic', 'Strike', '-', 'RemoveFormat']],
            ['name' => 'paragraph', 'items' => ['NumberedList', 'BulletedList']],
            ['name' => 'links', 'items' => ['Link', 'Unlink']]
        ];

        switch($type) {
            case 'minimal':
                return [
                    'uiColor' => '#FEFEFE',
                    'toolbar' => $toolbarMin,
                ];

            default:
                return [
                    'uiColor' => '#FEFEFE',
                    'toolbar' => $toolbarFull,
                    'filebrowserBrowseUrl' => $this->router->generate('elfinder')
                ];
        }
    }

    /**
     * @return string
     */
    public function getParent()
    {
        return TextareaType::class;
    }

    /**
     * @param FormView $view
     * @param FormInterface $form
     * @param array $options
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {

        $css_class = ($options['ckeditor_mode'] == 'inline') ? self::CSS_CLASS_INLINE : self::CSS_CLASS_BOXED;

        if (isset($view->vars['attr']['class'])) {
            $view->vars['attr']['class'] .= ' ' . $css_class;
        } else {
            $view->vars['attr']['class'] = $css_class;
        }

        $ckeOptions = empty($options['ckeditor_options'])
            ? $this->buildCKEditorOptions($options['ckeditor'])
            : $options['ckeditor_options'];

        $view->vars['attr']['data-config'] = htmlentities(json_encode($ckeOptions));
    }

    /**
     * @param OptionsResolver $resolver
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'ckeditor' => 'full', // only available if option "ckeditor_options" is empty
            'ckeditor_options' => array(),
            'ckeditor_mode' => 'boxed'
        ]);

        $resolver->setAllowedValues('ckeditor', array('full', 'minimal'));
        $resolver->setAllowedValues('ckeditor_mode', array('inline', 'boxed'));

        $resolver->setAllowedTypes('ckeditor_options', 'array');



    }
}