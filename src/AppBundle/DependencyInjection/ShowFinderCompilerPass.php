<?php
/**
 * Created by PhpStorm.
 * User: remidupuy
 * Date: 12/02/18
 * Time: 13:59
 */

namespace AppBundle\DependencyInjection;


use AppBundle\ShowFinder\ShowFinder;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class ShowFinderCompilerPass implements CompilerPassInterface
{

    /**
     * You can modify the container here before it is dumped to PHP code.
     */
    public function process(ContainerBuilder $container)
    {
        $showFinderDefinition = $container->findDefinition(ShowFinder::class);
        $showFinderTaggedServices = $container->findTaggedServiceIds('show.finder');

        foreach ($showFinderTaggedServices as $showFinderTaggedServiceId => $showFinderTaggedService) {
            $service = new Reference($showFinderTaggedServiceId);
            $showFinderDefinition->addMethodCall('addFinder', [$service]);
        }
    }
}