<?php
namespace BEAR\Package\Mock\ResourceObject;

use BEAR\Resource\ResourceObject;

class MockResource extends ResourceObject
{
    /**
     * Get
     *
     * @Cache
     *
     * @return array
     */
    public function onGet()
    {
        return microtime(true);
    }

    /**
     * Post
     *
     * @CacheUpdate
     */
    public function onPost()
    {
        return $this;
    }

}
