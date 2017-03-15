<?php
/**
 * This file is part of the BEAR.Package package.
 *
 * @license http://opensource.org/licenses/MIT MIT
 */
namespace BEAR\Package;

use BEAR\AppMeta\AbstractAppMeta;
use BEAR\Package\Provide\Error\VndErrorModule;
use BEAR\Package\Provide\Resource\ResourceObjectModule;
use BEAR\Package\Provide\Router\WebRouterModule;
use BEAR\QueryRepository\QueryRepositoryModule;
use BEAR\Sunday\Module\SundayModule;
use Ray\Di\AbstractModule;

class PackageModule extends AbstractModule
{
    protected $appMeta;

    public function __construct(AbstractAppMeta $appMeta = null)
    {
        $this->appMeta = $appMeta;
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure()
    {
        $this->install(new QueryRepositoryModule);
        $this->install(new WebRouterModule);
        $this->install(new VndErrorModule);
        $this->install(new SundayModule);
        $this->install(new ResourceObjectModule($this->appMeta));
    }
}
