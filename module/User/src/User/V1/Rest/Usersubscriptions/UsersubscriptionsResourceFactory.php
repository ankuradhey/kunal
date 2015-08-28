<?php
namespace User\V1\Rest\Usersubscriptions;

class UsersubscriptionsResourceFactory
{
    public function __invoke($services)
    {
        return new UsersubscriptionsResource($services);
    }
}
