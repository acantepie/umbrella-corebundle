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
use Umbrella\CoreBundle\Model\TimestampTrait;
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
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $md5;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $mimeType;

    /**
     * @var string
     * @ORM\Column(type="string", nullable=false)
     */
    public $path;

    /**
     * @var UploadedFile
     */
    public $file;

    /**
     * UmbrellaFile constructor.
     */
    public function __construct()
    {
        $this->createdAt = new \DateTime('NOW');
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getWebPath();
    }

    /**
     * @return string
     */
    public function getWebPath()
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getHumanSize()
    {
        return MathUtils::bytes_to_size($this->size);
    }
}
