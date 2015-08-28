<?php
namespace User\V1\Rest\Addtocart;

class AddtocartResourceFactory
{
    public function __invoke($services)
    {
        return new AddtocartResource($services);
    }
}
