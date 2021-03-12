<?php

namespace Aschaeffer\SonataEditableListBundle;

use Aschaeffer\SonataEditableListBundle\DependencyInjection\Compiler\AdminExtensionCompilerPass;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\HttpKernel\Bundle\Bundle;

class AschaefferSonataEditableListBundle extends Bundle
{
    /**
     * {@inheritdoc}
     */
    public function build(ContainerBuilder $container)
    {
        parent::build($container);

        $container->addCompilerPass(new AdminExtensionCompilerPass());
    }
}
