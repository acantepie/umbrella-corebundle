<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 22:08.
 */

namespace Umbrella\CoreBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Model\IdTrait;
use Umbrella\CoreBundle\Utils\MathUtils;

/**
 * Class UmbrellaFile.
 *
 * @ORM\Entity
 * @ORM\Table(name="umbrella_file")
 * @ORM\EntityListeners({ "Umbrella\CoreBundle\Component\UmbrellaFile\UmbrellaFileListener" })
 */
class UmbrellaFile
{
    use IdTrait;

    /**
     * @var \DateTimeInterface
     * @ORM\Column(type="datetime", nullable=false)
     */
    public $createdAt;

    /**
     * @var string
     * @ORM\Column(type="string", length=255, nullable=false)
     */
    public $name;

    /**
     * @var int
     * @ORM\Column(type="integer", nullable=false)
     */
    public $size;

    /**
     * @var ?UploadedFile
     */
    public $_file;

    /**
     * @var string
     */
    public $_filePath;

    /**
     * @var bool
     */
    public $_deleteFile = false;

    /**
     * UmbrellaFile constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
    }

    public function getHumanSize(): string
    {
        return MathUtils::bytes_to_size($this->size);
    }
}
