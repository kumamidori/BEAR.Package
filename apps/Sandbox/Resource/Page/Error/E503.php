<?php
namespace Sandbox\Resource\Page\Error;

use BEAR\Resource\AbstractObject;
use BEAR\Sunday\Inject\ResourceInject;

/**
 * Error
 *
 */
class E503 extends AbstractObject
{
    public function onGet()
    {
        $this->code = 503;
        return $this;
    }
}