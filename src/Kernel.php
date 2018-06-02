<?php

declare(strict_types=1);

namespace Zored\Dmns;

use Symfony\Bundle\FrameworkBundle\FrameworkBundle;
use Symfony\Component\Config\Loader\LoaderInterface;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel
{
    public function registerBundles()
    {
        return [
            new FrameworkBundle(),
            new DmnsBundle(),
        ];
    }

    public function registerContainerConfiguration(LoaderInterface $loader)
    {
        $loader->load(__DIR__ . '/Resources/config/services.yaml');
    }

    public function getRootDir()
    {
        return __DIR__ . '/..';
    }

    public function getName()
    {
        return 'dmns';
    }
}
