<?php

return array(
    'router' => array(
        'routes' => array(
            'assessmenthome' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/assessmenthome[/:studentId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'createpaper',
                    ),
                ),
            ),
            'myassignedpaper' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/myassignedpaper',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'myassignedpaper',
                    ),
                ),
            ),
            'studenttestattempt' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/studenttestattempt[/:paperassignId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'studenttestattempt',
                    ),
                ),
            ),
            'mentorRequests' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mentorRequests',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'mentorRequests',
                    ),
                ),
            ),
            'tempmentorrequest' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/tempmentorrequest',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'tempmentorrequest',
                    ),
                ),
            ),            
            'mentortestevaluate' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/mentortestevaluate[/:paperassignId][/:studentId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'mentortestevaluate',
                    ),
                ),
            ),
            'testevaluationresult' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/testevaluationresult[/:paperassignId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'testevaluationresult',
                    ),
                ),
            ),
            'lmsquestions' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/lmsquestions[/page/:page][/:boardId][/:classId][/:subjectId][/:studentId]',
                    'constraints' => array(
                        'action' => '(?!\bpage\b)(?!\border_by\b)[a-zA-Z][a-zA-Z0-9_-]*',
                        'id' => '[0-9]+',
                        'page' => '[0-9]+'
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'lmsquestions',
                    ),
                ),
            ),
            'selfquestions' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/selfquestions',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'selfquestions',
                    ),
                ),
            ),
            'uploadquestions' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/uploadquestions',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'uploadquestions',
                    ),
                ),
            ),
            'downloadquestion' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/downloadquestion[/:filename]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'downloadquestion',
                    ),
                ),
            ),
            'viewpaper' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/viewpaper[/:paperId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'viewpaper',
                    ),
                ),
            ),
            
            'deleteparentchild' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/deleteparentchild',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'deleteparentchild',
                    ),
                ),
            ),
            
            
            'previewpaper' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/previewpaper[/:paperId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'previewpaper',
                    ),
                ),
            ),
            'movequestion' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/movequestion[/:paperId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'movequestion',
                    ),
                ),
            ),
            'checkpapername' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/checkpapername[/:classId][/:subjectId][/:paperName]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'checkpapername',
                    ),
                ),
            ),
            'getquestionanswer' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/getquestionanswer[/:question_id]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'getquestionanswer',
                    ),
                ),
            ),
            'add-mentor-details' => array(
				'type' => 'segment',
				'options' => array(
					'route'    => '/add-mentor-details[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                    ),
					'defaults' => array(
						'controller' => 'Assessment\Controller\Index',
						'action'     => 'addMentorDetails',
					),
				),
			),
            'previewstudentattempt' => array(
                'type' => 'Zend\Mvc\Router\Http\Segment',
                'options' => array(
                    'route' => '/previewstudentattempt[/:paperAssignId]',
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'previewstudentattempt',
                    ),
                ),
            ),'chkMentor' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/chkMentor',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'checkMentor',
                    ),
                ),
            ),'addStudentDetails' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/addStudentDetails',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'addStudentDetails',
                    ),
                ),
            ),'addSubjectForInvitedLearner' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/addSubjectForInvitedLearner',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'addSubjectForInvitedLearner',
                    ),
                ),
            ),'getSubjectlist' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/getSubjectlist',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'getSubjectlist',
                    ),
                ),
            ),
            'getSubjectlistByClass' => array(
                'type' => 'segment',
                'options' => array(
                    'route' => '/getSubjectListByClass',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action' => 'getSubjectListByClass',
                    ),
                ),
            ),
            
//          'my-groups' => array(
//				'type' => 'segment',
//				'options' => array(
//					'route'    => '/my-groups[/:id]',
//                    'constraints' => array(
//                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
//                    ),
//					'defaults' => array(
//						'controller' => 'Assessment\Controller\Index',
//						'action'     => 'my-groups',
//					),
//				),
//			),
            
            'comments' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/comments[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',   
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'comments',
                    ),
                ),
            ),
            
            'reply-answers' => array(
				'type' => 'segment',
				'options' => array(
					'route'    => '/reply-answers[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                    ),
					'defaults' => array(
						'controller' => 'Assessment\Controller\Index',
						'action'     => 'replyAnswers',
					),
				),
			),
			'pagination-questions' => array(
				'type' => 'segment',
				'options' => array(
					'route'    => '/pagination-questions[/:action]',
                    'constraints' => array(
                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                    ),
					'defaults' => array(
						'controller' => 'Assessment\Controller\Index',
						'action'     => 'paginationQuestions',
					),
				),
			),
            'reply-add' => array(
				'type'    => 'segment',
				'options' => array(
					'route'    => '/reply-add[/:id]',
				'constraints' => array(
					'id' => '[a-zA-Z][a-zA-Z0-9_-]*',   
				),
				'defaults' => array(
					'controller' => 'Assessment\Controller\Index',
					'action'     => 'replyAdd',
				),
				),
			),
            
            'changegroupstatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/changegroupstatus',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'changegroupstatus',
                    ),
                ),
            ),
//            'my-subscriptions' => array(
//				'type' => 'segment',
//				'options' => array(
//					'route'    => '/my-subscriptions[/:action]',
//                    'constraints' => array(
//                        'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
//                    ),
//					'defaults' => array(
//						'controller' => 'Assessment\Controller\Index',
//						'action'     => 'my-subscriptions',
//					),
//				),
//			),
            
                'checkEmail' => array(
				'type' => 'segment',
				'options' => array(
				'route'    => '/checkEmail[/:id]',
                                'constraints' => array(
                                    'id' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                                ),
                                'defaults' => array(
                                        'controller' => 'Assessment\Controller\Index',
                                        'action'     => 'checkEmail',
                                ),
				),
		),
                        
                'addgroupdetails' => array(
                    'type' => 'segment',
                    'options' => array(
			'route'    => '/addgroupdetails[/:id]',
                    'constraints' => array(
                        'id' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                    ),
			'defaults' => array(
				'controller' => 'Assessment\Controller\Index',
				'action'     => 'addgroupdetails',
			),
			),
		),

            'changeparentstatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/changeparentstatus',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'changeparentstatus',
                    ),
                ),
            ),

             'changequestionstatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/changequestionstatus',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'changequestionstatus',
                    ),
                ),
            ), 
            'changequestionreplystatus' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/changequestionreplystatus',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'changequestionreplystatus',
                    ),
                ),
            ),

            'my-students-feedback' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/my-students-feedback[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'myStudentsFeedback',
                    ),
                ),
            ), 
            'mymentorsfeedback' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/mymentorsfeedback[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'myMentorsFeedback',
                    ),
                ),
            ),
            
            'ajaxfeedbackstudent' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account/ajaxfeedbackstudent[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'ajaxfeedbackstudent',
                    ),
                ),
            ),
            
            'ajaxfeedbackstudents' => array(
                'type'    => 'segment',
                'options' => array(
                    'route' => '/account/ajax-feedback-student[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'ajaxfeedbackstudent',
                    ),
                ),
            ),
            
           'ajaxuploadsdownloads' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account/ajax-uploads-downloads[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'ajaxUploadsDownloads',
                    ),
                ),
            ), 
            'ajax-uploads' => array(
                    'type' => 'segment',
                    'options' => array(
                            'route'    => '/ajax-uploads[/:action]',
                            'constraints' => array(
                           'action' => '[a-zA-Z][a-zA-Z0-9_-]*',                        
                            ),
                            'defaults' => array(
                               'controller' => 'Assessment\Controller\Index',
                                'action'     => 'ajaxUploads',
                              ),
                         ),
                 ),
           'download-file' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/download-file[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'downloadFile',
                    ),
                ),
            ), 
            
          'ajaxStudentMentorQuery' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account/ajax-student-mentor-query[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'ajaxstudentmentorquery',
                    ),
                ),
            ),
            'ajaxFeedbackStudentComments' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account/ajax-feedback-student-comments[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'ajaxFeedbackStudentComments',
                    ),
                ),
            ),
            
            'getChapters' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/account/getChapters[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'getChapters',
                    ),
                ),
            ),
            'getChaptersList' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/getChaptersList',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'getChaptersList',
                    ),
                ),
            ),
            
          'linkparent' => array(
                        'type' => 'Literal',
                        'options' => array(
                            'route' => '/linkparent',
                            'defaults' => array(
                                'controller' => 'Assessment\Controller\Index',
                                'action'     => 'linkparent',
                            ),
                        ),
                    ),
            'search-result' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/search-result/[/:id]',
                    'constraints' => array(
                        'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',   
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'searchResult',
                    ),
                ),
            ),
          'download-file' => array(
                'type'    => 'segment',
                'options' => array(
                    'route'    => '/download-file[/:id]',
                    'constraints' => array(
                       'id' => '[%&;a-zA-Z0-9][%&;a-zA-Z0-9_-]*',
                    ),
                    'defaults' => array(
                        'controller' => 'Assessment\Controller\Index',
                        'action'     => 'downloadFile',
                    ),
                ),
            ),
            
         
        ),
    ),
    'controllers' => array(
        'invokables' => array(
            'Assessment\Controller\Index' => 'Assessment\Controller\IndexController'
        ),
    ),
    'view_manager' => array(
        'template_path_stack' => array(
            __DIR__ . '/../view',
        ),
//        'template_map' => array(
//        // use Applications layout instead
//        'layout/layout' => __DIR__ . '/../../Application/view/layout/layout.phtml',
//    ),
        'template_map' => array(
            'paginator-slide' => __DIR__ . '/../view/layout/slidePaginator.phtml',
        ),
    ),
);
