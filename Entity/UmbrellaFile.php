<?php
/**
 * Created by PhpStorm.
 * User: acantepie
 * Date: 21/05/17
 * Time: 22:08.
 */

namespace Umbrella\CoreBundle\Entity;

use Symfony\Component\HttpFoundation\File\UploadedFile;
use Umbrella\CoreBundle\Entity\BaseEntity;
use Doctrine\ORM\Mapping as ORM;
use Umbrella\CoreBundle\Utils\MathUtils;

/**
 * Class UmbrellaFile.
 *
 * @ORM\Entity
 * @ORM\Table(name="umbrella_file")
 * @ORM\HasLifecycleCallbacks
 * @ORM\EntityListeners({ "Umbrella\CoreBundle\Listener\UmbrellaFileListener" })
 */
class UmbrellaFile extends BaseEntity
{
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

    /**
     * @return string
     */
    public function __toString()
    {
        return (string) $this->getWebPath();
    }
}
