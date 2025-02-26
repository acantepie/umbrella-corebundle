<?php

namespace Umbrella\CoreBundle\Tests;

use Symfony\Bundle\FrameworkBundle\Test\KernelTestCase;
use Symfony\Component\HttpKernel\KernelInterface;
use Umbrella\CoreBundle\Tests\App\Kernel;

class AppTestCase extends KernelTestCase
{
    protected static function getKernelClass(): string
    {
        return Kernel::class;
    }

    protected static function createKernel(array $options = []): KernelInterface
    {
        if (null === static::$class) {
            static::$class = static::getKernelClass();
        }

        return new static::$class('test', false);
    }
}
