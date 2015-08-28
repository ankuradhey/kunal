<?php
namespace Notification\V1\Rest\Notifications;

class NotificationsResourceFactory
{
    public function __invoke($services)
    {
        return new NotificationsResource($services);
    }
}
