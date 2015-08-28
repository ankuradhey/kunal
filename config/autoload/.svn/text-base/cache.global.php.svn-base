<?php

/*
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */
return array(
    'memcached' => array(//can be called directly via SM in the name of 'memcached'
        'adapter' => array(
            'name' => 'memcached',
            'lifetime' => 7200,
            'options' => array(
                'servers' => array(
                    array(
                        'localhost', 11211
                    )
                ),
                'namespace' => 'memcached',
                'liboptions' => array(
                    'COMPRESSION' => true,
                    'binary_protocol' => true,
                    'no_block' => true,
                    'connect_timeout' => 100
                )
            )
        ),
        'plugins' => array(
            'exception_handler' => array(
                'throw_exceptions' => false
            ),
        ),
    ),
    'sessionMemcached' => array(//can be called directly via SM in the name of 'memcached'
        'adapter' => array(
            'name' => 'memcached',
            'lifetime' => 7200,
            'options' => array(
                'servers' => array(
                    array(
                        'localhost', 11211
                    )
                ),
                'namespace' => 'MYMEMCACHEDNAMESPACE',
                'liboptions' => array(
                    'COMPRESSION' => true,
                    'binary_protocol' => true,
                    'no_block' => true,
                    'connect_timeout' => 100
                )
            )
        ),
        'plugins' => array(
            'exception_handler' => array(
                'throw_exceptions' => false
            ),
        ),
    ),
);
