<?php

namespace App\Command\Service;

use Symfony\Component\DependencyInjection\ParameterBag\ParameterBagInterface;
use Symfony\Component\Filesystem\Filesystem;

class ServiceClassGenerator
{
    public function __construct(
        private readonly ParameterBagInterface $parameterBag,
        private readonly Filesystem $filesystem
    )
    {
    }

    public function generateClass($serviceName): void
    {
        $filename = $this->parameterBag->get('kernel.project_dir')."/src/Service/".$serviceName.".php";
        $namespace = $this->getNamespace($serviceName);
        $methodName = "run";
        $template = $this->getTemplate();

        ob_start();
        include $template;
        $content = ob_get_clean();

        $this->filesystem->dumpFile($filename, $content);
    }

    private function getTemplate(): string
    {
        return $this->parameterBag->get('kernel.project_dir')."/src/Command/template/service.tpl.php";
    }

    public function getNamespace($name): string
    {
        return "App\\Service\\".$name;
    }
}
