<?php
declare(strict_types=1);

namespace Lcobucci\MyApi;

require __DIR__ . '/../vendor/autoload.php';

use Chimera\DependencyInjection\RegisterApplication;
use Doctrine\Common\Annotations\AnnotationRegistry;
use Lcobucci\DependencyInjection\ContainerBuilder;
use function dirname;

AnnotationRegistry::registerLoader('class_exists');

$builder = ContainerBuilder::default(__FILE__, __NAMESPACE__);
$root    = dirname(__DIR__);

return $builder->setDumpDir($root . '/var/tmp')
               ->setParameter('app.basedir', $root . '/')
               ->addFile(__DIR__ . '/container.xml')
               ->addPackage(RegisterApplication::class, ['my-api'])
               ->getContainer();
