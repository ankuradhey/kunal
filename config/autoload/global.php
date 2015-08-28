<?php

/**
 * Global Configuration Override
 *
 * You can use this file for overriding configuration values from modules, etc.
 * You would place values in here that are agnostic to the environment and not
 * sensitive to security.
 *
 * @NOTE: In practice, this file will typically be INCLUDED in your source
 * control, so do not include passwords or other sensitive information in this
 * file.
 */
include( 'vendor/PHPMailer_5.2.4/sendmail.php');
//include( 'vendor/PHPExcel/Classes/PHPExcel.php');
//include( 'vendor/PHPExcel/Classes/PHPExcel/IOFactory.php');
//include( 'vendor/PHPExcel/Classes/PHPExcel/Cell.php');
return array(
    'service_manager' => array(
        'factories' => array(
            'Zend\Db\Adapter\Adapter' => 'Zend\Db\Adapter\AdapterServiceFactory',
            'SlaveAdapter' => 'Common\Factory\Model\SlaveAdapterServiceFactory',
            'SlaveAdapter2' => 'Common\Factory\Model\SlaveAdapter2ServiceFactory',
            'SlaveAdapter3' => 'Common\Factory\Model\SlaveAdapter3ServiceFactory'
        ),
        'abstract_factories' => array(
            'Zend\Cache\Service\StorageCacheAbstractServiceFactory',
        ),
    ),
    'constant' => array(
        'contentPath' => 'http://10.1.9.99/content_data/',
        'templatePath' => 'http://10.1.9.99/template/',
        'webhost'=>'http://10.1.9.99',
        'emplayer'=>'http://10.1.9.99/emplayer/emplayer.php?crsid=',
        'emplayerPath'=>'http://10.1.9.99/emplayer/',
        'animationPath'=>'content_data/crsemr/public/resources/video/',
        'multilingualPath'=>'content_data/school_multilingual_ani/',
        'apiUrl'=>'http://developer.extramarks.com/boardclasslists',
        'apiUrl2'=>'http://localhost/schoolerp/soap/ctp_v5/public/api/',
        //'apiUrl'=>'http://localhost/school_lms/public/boardclasslists',
        
        
    ),
    'Assessment' => array(
            'SentBoard' => array()
        ),
    'websiteMode'=> 'lms',
//    'websiteMode'=> 'website',
    'default_content' => array(
        'board' => 'free',
        'class' => 'free',
        'subject' => 'free',
        'sub subject' => 'free',
        'chapter' => 'block',
        'topic' => 'free',
        'service'=>'free',
        'content'=>'block',
    ),
    'product'=> array('productId'=>'3'), 
//    'product'=> array('productId'=>'45'), 
//    'product'=> array('productId'=>'42'), 
    'daily_report_to' => 'neha.dixit@extramarks.com,alok.vishwakarma@extramarks.com,karunn@extramarks.com',
    'ftp_config' => array(
        'junior_board_name' => 'K5',
        'FTP_SERVER' => '10.1.9.99',
        'FTP_USERNAME' => 'exmtest',
        'FTP_PASSWORD' => 'exm@123',
        'WEBSITE_ENV' => 'development'
    ),
    'social_authentication' => array(
        'googleClientId' => '222081534395-phjr6r8j7v6bbl0psdqpeaqu87eos2js.apps.googleusercontent.com',
        'googleSecret' => 'fUyogu50KxNZrhnes0BpbIxP',
        'twitterConsumerKey' => 'J0umXT2j0xt9tgIIh52APTcxR',
        'twitterConsumerSecret' => 'Et8OCrMKQG1shCo5uLslV6aXX5k0nTPgcC7xsosSEiHHNcynL2',
        'facebookClientId' => '640256979338806',
        'facebookSecret' => '5ef0d46c478dc2b248b3406ecaaba6b9'
        
    ),
     'lcms_constants' => array(
        'LMSSLIDE_IMAGE' => 'http://10.1.9.115/crsemr/public/resources/image/',
        'LMSSLIDE_AUDIO' => 'http://10.1.9.115/crsemr/public/resources/audio/',
        'LMSSLIDE_VIDEO' => 'http://10.1.9.115/crsemr/public/resources/video/',
        'TEMPLATE_PATH'  => 'http://10.1.9.115/crsemr/public/template',
        'BASEURL1'       => 'http://10.1.9.115/crsemr/',
        'CRSIDFILE_PATH' => 'http://10.1.9.115/crsemr/public/resources/cache/crs/',
        'CRSCATEGORYIMAGE'     => '1',
        'CRSCATEGORYANIMATION' => '2',
        'CRSCATEGORYVIDEO'     => '3',
        'CRSCATEGORYAUDIO'     => '4',
        'CRSCATEGORYSIMULATION'=> '5'
    ),
    
    'RESOURCEFILEURL' => array(
	'CRSCATEGORYIMAGE'=>"/public/resources/image/",
	'CRSCATEGORYAUDIO'=>"/public/resources/audio/",
	'CRSCATEGORYVIDEO'=>"/public/resources/video/",
	'CRSCATEGORYSIMULATION'=>"/public/resources/video/",
    ),
    'cdn'=> array(
        'default'=>'extramarks.com'
//        'default'=>'s3.amazonaws.com/joyage'
    )
);


