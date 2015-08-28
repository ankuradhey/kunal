<?php

namespace Assessment;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Assessment\Model\User;
use Assessment\Model\UserTable;
use Assessment\Model\MentorPaper;
use Assessment\Model\MentorPaperTable;
use Assessment\Model\Question;
use Assessment\Model\QuestionTable;
use Assessment\Model\MentorQuestion;
use Assessment\Model\MentorQuestionTable;
use Assessment\Model\MentorPaperQuestion;
use Assessment\Model\MentorPaperQuestionTable;
use Assessment\Model\MentorAssignPaper;
use Assessment\Model\MentorAssignPaperTable;
use Assessment\Model\MentorPaperUserAnswer;
use Assessment\Model\MentorPaperUserAnswerTable;
use Assessment\Model\MentorPaperUploadFile;
use Assessment\Model\MentorPaperUploadFileTable;
use Assessment\Model\Tlessonplan;
use Assessment\Model\TlessonplanTable;
use Assessment\Model\Tusertype;
use Assessment\Model\TusertypeTable;
use Assessment\Model\TfeedbackscommentsTable;
use Assessment\Model\Tfeedbackscomments;

use Assessment\Model\Tcountryphone;
use Assessment\Model\TcountryphoneTable;

use Assessment\Model\TfeedbackchaptersTable;
use Assessment\Model\Tfeedbackchapters;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\TableGateway\TableGateway;
use Album\Factory\Model\SlaveAdapter;
use Zend\Db\TableGateway\Feature;
use Assessment\Model\Tstudentandmentor;
use Assessment\Model\TstudentandmentorTable;
use Assessment\Model\Inviteexternalemail;
use Assessment\Model\InviteexternalemailTable;
use Assessment\Model\Tuserpackage;
use Assessment\Model\TuserpackageTable;
use Assessment\Model\Ttempgroups;
use Assessment\Model\TtempgroupsTable;
use Assessment\Model\Tusergroups;
use Assessment\Model\TusergroupsTable;
use Assessment\Model\Tparentandchild;
use Assessment\Model\TparentandchildTable;
use Assessment\Model\UserQuestion;
use Assessment\Model\UserQuestionTable;
use Assessment\Model\Treplyonquestion;
use Assessment\Model\TreplyonquestionTable;
use Assessment\Model\TuploadsdownloadsTable;
use Assessment\Model\Tuploadsdownloads;
use Zend\ModuleManager\Feature as nerFeature;
use Zend\Loader;
use Zend\EventManager\EventInterface;
use Assessment\Model\Ticker;
use Assessment\Model\TickerTable;
use Assessment\Model\NotificationMaster;
use Assessment\Model\NotificationMasterTable;
use Assessment\Model\MetaTags;
use Assessment\Model\MetaTagsTable;
use Assessment\Model\UserTracker;
use Assessment\Model\UserTrackerTable;
use Assessment\Model\UserOtherDetails;
use Assessment\Model\UserOtherDetailsTable;

use Assessment\Model\AdminLogDetails;
use Assessment\Model\AdminLogDetailsTable;

use Assessment\Model\Tuserlicensedetail;
use Assessment\Model\TuserlicensedetailTable;
use Assessment\Model\Temsregistration;
use Assessment\Model\TemsregistrationTable;
use Assessment\Model\Temsactivation;
use Assessment\Model\TemsactivationTable;
use Assessment\Model\Temsstudents;
use Assessment\Model\TemsstudentsTable;
use Assessment\Model\Tstudentsregistrationexceldetails;
use Assessment\Model\TstudentsregistrationexceldetailsTable;
use Assessment\Model\RegistrationViaPluginDetails;
use Assessment\Model\RegistrationViaPluginDetailsTable;
use Assessment\Model\IpCountry;
use Assessment\Model\IpCountryTable;
use Assessment\Model\tabletupdatedappdetails;
use Assessment\Model\tabletupdatedappdetailsTable;
use Assessment\Model\Terpusermapped;
use Assessment\Model\TerpusermappedTable;
use Assessment\Model\Treferreduserdetails;
use Assessment\Model\TreferreduserdetailsTable;
class Module implements 
	nerFeature\AutoloaderProviderInterface,
    nerFeature\ConfigProviderInterface,
    nerFeature\ServiceProviderInterface {

    public function onBootstrap(MvcEvent $e) {
        $e->getApplication()->getServiceManager()->get('translator');
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
    }

    public function getConfig() {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig() {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }

    public function getServiceConfig() {
        return array(
            'factories' => array(
                'Assessment\Model\TmentorfeedbackTable'=>'Assessment\Factory\Model\TmentorfeedbackTableFactory',
                'Assessment\Model\TmentordetailsFactory'=>'Assessment\Factory\Model\TmentordetailsTableFactory',
                'Assessment\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserTableGateway');
                    $table = new UserTable($tableGateway);
                    return $table;
                },
                'UserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());
                    return new TableGateway('t_user', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\IpCountryTable' => function($sm) {
                    $tableGateway = $sm->get('IpCountryTableGateway');
                    $table = new IpCountryTable($tableGateway);
                    return $table;
                },
                'IpCountryTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new IpCountry());
                    return new TableGateway('ipcountry', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\QuestionTable' => function($sm) {
                    $tableGateway = $sm->get('QuestionTableGateway');
                    $table = new QuestionTable($tableGateway);
                    return $table;
                },
                'QuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Question());
                    return new TableGateway('main_content', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\MentorPaperTable' => function($sm) {
                    $tableGateway = $sm->get('MentorPaperTableGateway');
                    $table = new MentorPaperTable($tableGateway);
                    return $table;
                },
                'MentorPaperTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MentorPaper());
                    return new TableGateway('mentor_paper', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\MentorQuestionTable' => function($sm) {
                    $tableGateway = $sm->get('MentorQuestionTableGateway');
                    $table = new MentorQuestionTable($tableGateway);
                    return $table;
                },
                'MentorQuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MentorQuestion());
                    return new TableGateway('mentor_question', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\MentorPaperQuestionTable' => function($sm) {
                    $tableGateway = $sm->get('MentorPaperQuestionTableGateway');
                    $table = new MentorPaperQuestionTable($tableGateway);
                    return $table;
                },
                'MentorPaperQuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MentorPaperQuestion());
                    return new TableGateway('mentor_paper_questions', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\MentorAssignPaperTable' => function($sm) {
                    $tableGateway = $sm->get('MentorAssignPaperTableGateway');
                    $table = new MentorAssignPaperTable($tableGateway);
                    return $table;
                },
                'MentorAssignPaperTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MentorAssignPaper());
                    return new TableGateway('mentor_assign_paper', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                        
                
               'Assessment\Model\TfeedbackscommentsTable' => function($sm) {
                    $tableGateway = $sm->get('TfeedbackscommentsTableGateway');
                    $table = new TfeedbackscommentsTable($tableGateway);
                    return $table;
                },
                'TfeedbackscommentsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tfeedbackscomments());
                    return new TableGateway('t_feedbacks_comments', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },       
                        
                
                'Assessment\Model\TquestionTable' => function($sm) {
                    $tableGateway = $sm->get('TquestionTableGateway');
                    $table = new TquestionTable($tableGateway);
                    return $table;
                },
                'TquestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tquestion());
                    return new TableGateway('t_question', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },     
                    
                'Assessment\Model\MentorPaperUserAnswerTable' => function($sm) {
                    $tableGateway = $sm->get('MentorPaperUserAnswerTableGateway');
                    $table = new MentorPaperUserAnswerTable($tableGateway);
                    return $table;
                },
                'MentorPaperUserAnswerTableGateway' => function ($sm) {
                    
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MentorPaperUserAnswer());
                    return new TableGateway('mentor_paper_user_ans', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                'Assessment\Model\MentorPaperUploadFileTable' => function($sm) {
                    $tableGateway = $sm->get('MentorPaperUploadFileTableGateway');
                    $table = new MentorPaperUploadFileTable($tableGateway);
                    return $table;
                },
                'MentorPaperUploadFileTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MentorPaperUploadFile());
                    return new TableGateway('mentor_paper_upload_file', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                          'Assessment\Model\TstudentandmentorTable' => function($sm) {
                    $tableGateway = $sm->get('TstudentandmentorTableGateway');
//                    echo '<pre>';print_r($tableGateway);echo '</pre>';die('Macro Die');
                    $table = new TstudentandmentorTable($tableGateway);
                    
                    return $table;
                },
                'UserGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                   $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new User());

                    return new TableGateway('user', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
               'Assessment\Model\UserTable' => function($sm) {
                    $tableGateway = $sm->get('UserGateway');
//                    echo '<pre>';print_r($tableGateway);echo '</pre>';die('Macro Die');
                    $table = new UserTable($tableGateway);
                    
                    return $table;
                },
                'Assessment\Model\TtempgroupsTable' =>  function($sm) {
                   $tableGateway = $sm->get('TtempgroupsTableGateway');
                   $table = new TtempgroupsTable($tableGateway);
                   return $table;
                },
                    'TtempgroupsTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Ttempgroups());
                    return new TableGateway('temp_group_request', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                        
//               'Assessment\Model\TmentorfeedbackTable' =>  function($sm) {
//                   $tableGateway = $sm->get('TmentorfeedbackTableGateway');
//                   $table = new TmentorfeedbackTable($tableGateway);
//                   return $table;
//                },
//                    'TmentorfeedbackTableGateway' => function ($sm) { 
//                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
//                    $resultSetPrototype = new ResultSet();
//                    $resultSetPrototype->setArrayObjectPrototype(new Tmentorfeedback());
//                    return new TableGateway('t_mentor_feedback', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
//                }, 
                        
                        
                'TstudentandmentorTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tstudentandmentor());
                    return new TableGateway('t_student_and_mentor', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                  'Assessment\Model\TuserpackageTable' =>  function($sm) {
                   $tableGateway = $sm->get('TuserpackageTableGatewayAssesment');
                   $table = new TuserpackageTable($tableGateway);
                   return $table;
                },
		   'TuserpackageTableGatewayAssesment' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tuserpackage());
                    return new TableGateway('t_user_package', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                }
                , 'Assessment\Model\TparentandchildTable' =>  function($sm) {
                   $tableGateway = $sm->get('TparentandchildTableGateway');
                   $table = new TparentandchildTable($tableGateway);
                   return $table;
                },'TparentandchildTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tparentandchild());
                    return new TableGateway('t_parent_and_child', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                }      
                , 'Assessment\Model\InviteexternalemailTable' =>  function($sm) {
                   $tableGateway = $sm->get('InviteexternalemailTableGateway');
                   $table = new InviteexternalemailTable($tableGateway);
                   return $table;
                },'InviteexternalemailTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Inviteexternalemail());
                    return new TableGateway('invite_external_email', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },      
                        
                    'Assessment\Model\TusergroupsTable' =>  function($sm) {
                      $tableGateway = $sm->get('TusergroupsTableGateway');
                      $table = new TusergroupsTable($tableGateway);
                      return $table;
                },
                      'TusergroupsTableGateway' => function ($sm) { 
                      $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                      $resultSetPrototype = new ResultSet();
                      $resultSetPrototype->setArrayObjectPrototype(new Tusergroups());
                      return new TableGateway('t_user_groups', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },  
                   'Assessment\Model\UserQuestionTable' => function($sm) {
                    $tableGateway = $sm->get('UserQuestionTableGateway');
                    $table = new UserQuestionTable($tableGateway);
                    return $table;
                },
                   'UserQuestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserQuestion());
                    return new TableGateway('t_question', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
               },
                   'Assessment\Model\TlessonplanTable' =>  function($sm) {
                    $tableGateway = $sm->get('TlessonplanTableGateway');
                    $table = new TlessonplanTable($tableGateway);
                    return $table;
              },
                  'TlessonplanTableGateway' => function ($sm) {
                  $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                  $resultSetPrototype = new ResultSet();
                  $resultSetPrototype->setArrayObjectPrototype(new Tlessonplan());
                  return new TableGateway('t_lesson_plan', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
              },
                      'Assessment\Model\TreplyonquestionTable' => function($sm) {
                    $tableGateway = $sm->get('TreplyonquestionTableGateway');
                    $table = new TreplyonquestionTable($tableGateway);
                    return $table;
                },
                'TreplyonquestionTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Treplyonquestion());
                    return new TableGateway('t_reply_on_question', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                          'Assessment\Model\TuploadsdownloadsTable' => function($sm) {
                    $tableGateway = $sm->get('TuploadsdownloadsTableGateway');
                    $table = new TuploadsdownloadsTable($tableGateway);
                    return $table;
                },
                'TuploadsdownloadsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tuploadsdownloads());
                    return new TableGateway('t_uploads_downloads', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')), new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                        
                'Assessment\Model\TusertypeTable' =>  function($sm) {
                   $tableGateway = $sm->get('TusertypeTableGateway');
                   $table = new TusertypeTable($tableGateway);
                   return $table;
                },
		'TusertypeTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tusertype());
                    return new TableGateway('t_user_type', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                        
                'Assessment\Model\TcountryphoneTable' =>  function($sm) {
                   $tableGateway = $sm->get('TcountryphoneTableGateway');
                   $table = new TcountryphoneTable($tableGateway);
                   return $table;
                },
		'TcountryphoneTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tcountryphone());
                    return new TableGateway('t_country_phone', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                
               'Assessment\Model\TfeedbackchaptersTable' =>  function($sm) {
                   $tableGateway = $sm->get('TfeedbackchaptersTableGateway');
                   $table = new TfeedbackchaptersTable($tableGateway);
                   return $table;
                },
		'TfeedbackchaptersTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tfeedbackchapters());
                    return new TableGateway('t_feedback_chapters', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                
                'Assessment\Model\TickerTable'=>function($sm){
                    $tableGateway = $sm->get('TickerTableGateway');
                    $table = new TickerTable($tableGateway);
                    return $table;                    
                },
                        
                      
                'Assessment\Model\NotificationMasterTable' =>  function($sm) {
                   $tableGateway = $sm->get('NotificationMasterTableGateway');
                   $table = new NotificationMasterTable($tableGateway);
                   return $table;
                },
                        
                'NotificationMasterTableGateway' => function ($sm) {
                     $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new NotificationMaster());
                    return new TableGateway('notification_type_master', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                        
                        
                 'TickerTableGateway' =>function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Ticker());
                    return new TableGateway('t_ticker', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                 },  
                         
                 'Assessment\Model\MetaTagsTable'=>function($sm){
                    $tableGateway = $sm->get('MetaTagsTableGateway');
                    $table = new MetaTagsTable($tableGateway);
                    return $table;                    
                },
                 'MetaTagsTableGateway' =>function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new MetaTags());
                    return new TableGateway('t_meta_tag', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                 },  
                  'Assessment\Model\UserTrackerTable'=>function($sm){
                    $tableGateway = $sm->get('UserTrackerTableGateway');
                    $table = new UserTrackerTable($tableGateway);
                    return $table;                    
                },
                 'UserTrackerTableGateway' =>function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserTracker());
                    return new TableGateway('user_tracker', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                 }, 
                  'Assessment\Model\UserOtherDetailsTable'=>function($sm){
                    $tableGateway = $sm->get('UserOtherDetailsTableGateway');
                    $table = new UserOtherDetailsTable($tableGateway);
                    return $table;                    
                },
                 'UserOtherDetailsTableGateway' =>function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new UserOtherDetails());
                    return new TableGateway('user_other_details', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                 },
                 
                         
                'Assessment\Model\AdminLogDetailsTable'=>function($sm){
                    $tableGateway = $sm->get('AdminLogDetailsTableGateway');
                    $table = new AdminLogDetailsTable($tableGateway);
                    return $table;                    
                },
                 'AdminLogDetailsTableGateway' =>function($sm){
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new AdminLogDetails());
                    return new TableGateway('admin_edit_log', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                 },         
                         
                 'Assessment\Model\TuserlicensedetailTable' =>  function($sm) {
                   $tableGateway = $sm->get('TuserlicensedetailTableGateway');
                   $table = new TuserlicensedetailTable($tableGateway);
                   return $table;
                },
		    'TuserlicensedetailTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tuserlicensedetail());
                    return new TableGateway('tablet_user_license_details', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TemsregistrationTable' =>  function($sm) {
                   $tableGateway = $sm->get('TemsregistrationTableGateway');
                   $table = new TemsregistrationTable($tableGateway);
                   return $table;
                },
		    'TemsregistrationTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Temsregistration());
                    return new TableGateway('tablet_ems_registration', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TemsactivationTable' =>  function($sm) {
                   $tableGateway = $sm->get('TemsactivationTableGateway');
                   $table = new TemsactivationTable($tableGateway);
                   return $table;
                },
		    'TemsactivationTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Temsactivation());
                    return new TableGateway('tablet_ems_activation', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TemsstudentsTable' =>  function($sm) {
                   $tableGateway = $sm->get('TemsstudentsTableGateway');
                   $table = new TemsstudentsTable($tableGateway);
                   return $table;
                },
		    'TemsstudentsTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Temsstudents());
                    return new TableGateway('tablet_ems_students', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TerpusermappedTable' =>  function($sm) {
                   $tableGateway = $sm->get('TerpusermappedTableGateway');
                   $table = new TerpusermappedTable($tableGateway);
                   return $table;
                },
		    'TerpusermappedTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Terpusermapped());
                    return new TableGateway('erp_user_mapped', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TstudentsregistrationexceldetailsTable' =>  function($sm) {
                   $tableGateway = $sm->get('TstudentsregistrationexceldetailsTableGateway');
                   $table = new TstudentsregistrationexceldetailsTable($tableGateway);
                   return $table;
                },
		    'TstudentsregistrationexceldetailsTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Tstudentsregistrationexceldetails());
                    return new TableGateway('student_registration_excel_details', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\tabletupdatedappdetailsTable' =>  function($sm) {
                   $tableGateway = $sm->get('tabletupdatedappdetailsTableGateway');
                   $table = new tabletupdatedappdetailsTable($tableGateway);
                   return $table;
                },
		    'tabletupdatedappdetailsTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new tabletupdatedappdetails());
                    return new TableGateway('tablet_updated_app_details', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\RegistrationViaPluginDetailsTable' =>  function($sm) {
                   $tableGateway = $sm->get('RegistrationViaPluginDetailsTableGateway');
                   $table = new RegistrationViaPluginDetailsTable($tableGateway);
                   return $table;
                },
                'RegistrationViaPluginDetailsTableGateway' => function ($sm) { 
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new RegistrationViaPluginDetails());
                    return new TableGateway('registration_via_plugin_details', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TuserTable' =>  function($sm) {
                   $tableGateway = $sm->get('TuserTableGateway');
                   $table = new UserTable($tableGateway);
                   return $table;
                },
		'TuserTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new user());
                    return new TableGateway('user', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },
                 'Assessment\Model\TreferreduserdetailsTable' =>  function($sm) {
                   $tableGateway = $sm->get('TreferreduserdetailsTableGateway');
                   $table = new TreferreduserdetailsTable($tableGateway);
                   return $table;
                },
		'TreferreduserdetailsTableGateway' => function ($sm) {
                    $dbAdapter = $sm->get('Zend\Db\Adapter\Adapter');
                    $resultSetPrototype = new ResultSet();
                    $resultSetPrototype->setArrayObjectPrototype(new Treferreduserdetails());
                    return new TableGateway('t_referred_user_details', $dbAdapter, array(new Feature\MasterSlaveFeature($sm->get('SlaveAdapter')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter2')),new Feature\MasterSlaveFeature($sm->get('SlaveAdapter3'))), $resultSetPrototype);
                },


            ),
        );
    }

}
