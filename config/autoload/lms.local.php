<?php

/* 
 * To change this license header, choose License Headers in Project Properties.
 * To change this template file, choose Tools | Templates
 * and open the template in the editor.
 */


return array(
    'user_type'=> array('1'=>'Non_teacher','2'=>'Parent','3'=>'School','4'=>'Student','5'=>'Teacher','6'=>'Other'),
    'BoardContentServices'=> array('0'=>'assignment','1'=>'casestudy','2'=>'topicwiseqa','3'=>'slm','4'=>'worksheet','5'=>'getsetgo','6'=>'groupquiz','7'=>'quiz','8'=>'mcq','9'=>'mct'),
    'numberOfQuestions'=> array('test'=>20,'test-multi'=>20,'group-quiz'=>20,'quiz'=>20),
    'lcmsDisplay'=> 'noiframe',
    'cdn'=>array('default'=>$_SERVER['HTTP_HOST'].'/school_lms/public/'),
    'lcms_constants' => array(
        'LMSSLIDE_IMAGE' => '/content_data/crsemr/public/resources/image/',
        'LMSSLIDE_AUDIO' => '/content_data/crsemr/public/resources/audio/',
        'LMSSLIDE_VIDEO' => '/content_data/crsemr/public/resources/video/',
        'TEMPLATE_PATH'  => '/content_data/crsemr/public/template',
        'BASEURL1'       => '/content_data/crsemr/',
        'CRSIDFILE_PATH' => '/school_lms/data/cache/',
        'EMPLAYER_PATH' => '/emplayer/emplayer.php',
         
        'CRSCATEGORYIMAGE'     => '1',
        'CRSCATEGORYANIMATION' => '2',
        'CRSCATEGORYVIDEO'     => '3',
        'CRSCATEGORYAUDIO'     => '4',
        'CRSCATEGORYSIMULATION'=> '5'
        ),
    );
