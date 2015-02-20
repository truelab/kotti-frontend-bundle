<?php

namespace Truelab\KottiFrontendBundle;

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;
use Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass\HtmlBodyProcessorCompilerPass;
use Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass\NavigationRootChooserCompilerPass;
use Truelab\KottiFrontendBundle\DependencyInjection\CompilerPass\PathHandlerCompilerPass;

class TruelabKottiFrontendBundle extends Bundle
{
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new NavigationRootChooserCompilerPass());
        $container->addCompilerPass(new HtmlBodyProcessorCompilerPass());
        $container->addCompilerPass(new PathHandlerCompilerPass());
    }
}
