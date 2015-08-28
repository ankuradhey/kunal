<?php

namespace ZfcUserV0\Mapper;

use Zend\Crypt\Password\PasswordInterface as ZendCryptPassword;
use Zend\Stdlib\Hydrator\HydratorInterface as ZendHydrator;

interface HydratorInterface extends ZendHydrator
{
    /**
     * @return ZendCryptPassword
     */
    public function getCryptoService();
}
