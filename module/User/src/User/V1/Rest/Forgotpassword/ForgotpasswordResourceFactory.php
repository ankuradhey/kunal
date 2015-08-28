<?php
namespace User\V1\Rest\Forgotpassword;

class ForgotpasswordResourceFactory
{


    public function __invoke($services)
    {
        return new ForgotpasswordResource($services);
    }
}
