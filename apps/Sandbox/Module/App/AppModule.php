<?php

namespace Sandbox\Module\App;

use BEAR\Sunday\Module as SundayModule;
use BEAR\Package\Module\Package\PackageModule;
use BEAR\Package\Provide as ProvideModule;
use Ray\Di\Injector;
use Sandbox\Interceptor\Form\BlogPost;
use Sandbox\Interceptor\TimeMessage;
use Ray\Di\AbstractModule;
use Ray\Di\Scope;
use Zend\Stdlib\Exception\LogicException;

/**
 * Application module
 */
class AppModule extends AbstractModule
{
    /**
     * @var array
     */
    private $config;

    public function __construct($mode)
    {
        $packageDir = dirname(dirname(__DIR__));

        $modeConfig = $packageDir . "/config/{$mode}.php";
        if (! file_exists($modeConfig)) {
            throw new LogicException("Invalid mode {$mode}");
        }
        $this->config = (require $modeConfig) + (require $packageDir . '/config/prod.php');
        parent::__construct();
    }

    protected function configure()
    {
        // install package module
        $this->install(new PackageModule($this->config));

        // install twig
//        $this->install(new ProvideModule\TemplateEngine\Twig\TwigModule($this));

        // dependency binding for application
        $this->bind('BEAR\Sunday\Extension\Application\AppInterface')->to('Sandbox\App');
        $this->bind()->annotatedWith('greeting_msg')->toInstance('Hola');
        $this
            ->bind('BEAR\Resource\RenderInterface')
            ->annotatedWith('hal')
            ->to( 'BEAR\Package\Provide\ResourceView\HalRenderer')
            ->in(Scope::SINGLETON);

        // aspect weaving for application
        $this->installTimeMessage();
        $this->installNewBlogPost();
    }

    /**
     * @Form - bind form validator
     */
    private function installNewBlogPost()
    {
        $blogPost = $this->requestInjection('Sandbox\Interceptor\Form\BlogPost');
        $this->bindInterceptor(
            $this->matcher->subclassesOf('Sandbox\Resource\Page\Blog\Posts\Newpost'),
            $this->matcher->annotatedWith('BEAR\Sunday\Annotation\Form'),
            [$blogPost]
        );
    }

    /**
     * Add time message aspect
     */
    private function installTimeMessage()
    {
        // time message binding
        $this->bindInterceptor(
            $this->matcher->subclassesOf('Sandbox\Resource\App\First\Greeting\Aop'),
            $this->matcher->any(),
            [new TimeMessage]
        );
    }
}