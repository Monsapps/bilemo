<?php

namespace App;

use App\EventSubscriber\ExceptionSubscriber;
use Symfony\Bundle\FrameworkBundle\Kernel\MicroKernelTrait;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;
use Symfony\Component\HttpKernel\Kernel as BaseKernel;

class Kernel extends BaseKernel implements CompilerPassInterface
{
    use MicroKernelTrait;

    public function process(ContainerBuilder $container)
    {
        if (!$container->has(ExceptionSubscriber::class)) {
            return;
        }

        $exceptionListenerDefinition = $container->findDefinition(ExceptionSubscriber::class);

        foreach ($container->findTaggedServiceIds('app.normalizer') as $id => $tags) {
            $exceptionListenerDefinition->addMethodCall('addNormalizer', [new Reference($id)]);
        }
    }
}
