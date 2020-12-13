<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 06/06/17
 * Time: 19:44
 */

namespace Umbrella\CoreBundle\Form;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\CallbackTransformer;
use Symfony\Component\Form\DataTransformerInterface;
use Symfony\Component\Form\Event\PostSubmitEvent;
use Symfony\Component\Form\Event\PreSubmitEvent;
use Symfony\Component\Form\Event\SubmitEvent;
use Symfony\Component\Form\Extension\Core\Type\CheckboxType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Umbrella\CoreBundle\Component\UmbrellaFile\UploadHandler;
use Umbrella\CoreBundle\Entity\UmbrellaFile;
use Umbrella\CoreBundle\Services\UmbrellaFileUploader;
use Umbrella\CoreBundle\Utils\ArrayUtils;

/**
 * Class UmbrellaFileType
 */
class UmbrellaFileType extends AbstractType
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * UmbrellaFileType constructor.
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * {@inheritdoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        /** @var ?UmbrellaFile $umbrellaFile */
        $umbrellaFile = $form->getData();

        $initializedUmbrellaFile = null === $umbrellaFile || null === $umbrellaFile->id ? null : $umbrellaFile;

        if (null === $initializedUmbrellaFile) {
            $view->vars['file_info'] = '';
            $view->vars['umbrella_file'] = null;
        } else {
            $view->vars['file_info'] = $options['file_info']($umbrellaFile);
            $view->vars['umbrella_file'] = $initializedUmbrellaFile;
        }

        $view->vars['allow_delete'] = $options['allow_delete'] && !$options['required'];
        $view->vars['label_browse'] = $options['label_browse'];
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var ?UmbrellaFile $umbrellaFile */
        $umbrellaFile = $builder->getData();

        $builder->add('filename', TextType::class, [
            'disabled' => $options['disabled'],
            'mapped' => false,
            'attr' => [
                'readonly' => true,
                'class' => 'js-text'
            ]
        ]);

        $options['file_attr']['class'] = 'js-file';

        $builder->add('_file', FileType::class, [
            'required' => $options['required'],
            'error_bubbling' => true, // pass error to the parent
            'attr' => $options['file_attr'],
        ]);

        $builder->add('_deleteFile', CheckboxType::class, [
            'required' => false,
            'attr' => [
                'class' => 'js-delete'
            ]
        ]);

        $builder->addEventListener(FormEvents::SUBMIT, function (SubmitEvent $event) use($options) {

            /** @var ?UmbrellaFile $currentUmbrellaFile */
            $currentUmbrellaFile = $event->getData();


            if (null === $currentUmbrellaFile) {
                return; // no upload was performed
            }

            $uploadedFile = $currentUmbrellaFile->_file;

            // delete current uploaded file !
            if (null === $uploadedFile && $currentUmbrellaFile->_deleteFile) {
                $this->em->remove($currentUmbrellaFile);
                $event->setData(null);
                return;
            }

            // unpersisted umbrellafile + no file uploaded => return  null
            if (null === $uploadedFile && null === $currentUmbrellaFile->id) {
                $currentUmbrellaFile = null;
                $event->setData(null);
                return;
            }

            // persisted umbrellafile + file uploaded => remove previous // new current
            if (null !== $uploadedFile && null !== $currentUmbrellaFile->id) {
                $this->em->remove($currentUmbrellaFile);

                $currentUmbrellaFile = new UmbrellaFile();
                $currentUmbrellaFile->_file = $uploadedFile;
                $event->setData($currentUmbrellaFile);
            }

            $currentUmbrellaFile->_filePath = $options['file_path'];
        });
    }

    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => UmbrellaFile::class,
            'file_attr' => [],

            'file_info' => function(UmbrellaFile $umbrellaFile) {
                return sprintf('%s - %s', \pathinfo($umbrellaFile->name, PATHINFO_FILENAME), $umbrellaFile->getHumanSize());
            },
            'file_path' => null,
            'error_bubbling' => false, // resolve error at this level

            'allow_delete' => true,
            'label_browse' => 'common.browse'
        ]);

        $resolver->setAllowedTypes('file_info', ['callable']);
        $resolver->setAllowedTypes('file_path', ['null', 'string']);
        $resolver->setAllowedTypes('file_attr', 'array');
        $resolver->setAllowedTypes('allow_delete', 'boolean');
    }

    /**
     * {@inheritdoc}
     */
    public function getBlockPrefix()
    {
        return 'umbrellafile';
    }
}