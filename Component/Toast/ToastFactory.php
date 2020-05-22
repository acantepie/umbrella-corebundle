<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 19/04/20
 * Time: 11:57
 */

namespace Umbrella\CoreBundle\Component\Toast;

use Symfony\Contracts\Translation\TranslatorInterface;

/**
 * Class ToastFactory
 */
class ToastFactory
{
    /**
     * @var TranslatorInterface
     */
    private $translator;

    /**
     * ToastFactory constructor.
     * @param TranslatorInterface $translator
     */
    public function __construct(TranslatorInterface $translator)
    {
        $this->translator = $translator;
    }

    /**
     * @return Toast
     */
    public function create()
    {
        return new Toast();
    }

    /**
     * @param $transId
     * @param  array $transParams
     * @return Toast
     */
    public function createInfo($transId, array $transParams = [])
    {
        return $this->create()
            ->setType(Toast::INFO)
            ->setText($this->translator->trans($transId, $transParams));
    }

    /**
     * @param $transId
     * @param  array $transParams
     * @return Toast
     */
    public function createSuccess($transId, array $transParams = [])
    {
        return $this->create()
            ->setType(Toast::SUCCESS)
            ->setText($this->translator->trans($transId, $transParams));
    }

    /**
     * @param $transId
     * @param  array $transParams
     * @return Toast
     */
    public function createWarning($transId, array $transParams = [])
    {
        return $this->create()
            ->setType(Toast::WARNING)
            ->setText($this->translator->trans($transId, $transParams));
    }

    /**
     * @param $transId
     * @param  array $transParams
     * @return Toast
     */
    public function createError($transId, array $transParams = [])
    {
        return $this->create()
            ->setType(Toast::ERROR)
            ->setText($this->translator->trans($transId, $transParams));
    }
}
