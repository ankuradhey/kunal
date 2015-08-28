<?php
namespace User\V1\Rest\Subscribe;

class SubscribeResourceFactory
{
    public function __invoke($services)
    {
        return new SubscribeResource($services);
    }
}
