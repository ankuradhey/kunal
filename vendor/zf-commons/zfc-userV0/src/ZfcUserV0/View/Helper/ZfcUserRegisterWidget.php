<?php

namespace ZfcUserV0\View\Helper;

use Zend\View\Helper\AbstractHelper;
use ZfcUser\Form\Register as RegisterForm;
use ScnSocialAuth\Options\ModuleOptions as SocialOptions;
use Zend\View\Model\ViewModel;

class ZfcUserRegisterWidget extends AbstractHelper
{
     
    
    /**
     * Register Form
     * @var RegisterForm
     */
    protected $registerForm;
    protected $southAfricaLoaded;
    protected $ipCountryTableGateway;
    protected $commMapper;

    /**
     * $var string template used for view
     */
    protected $viewTemplate;
    /**
     * __invoke
     *
     * @access public
     * @param array $options array of options
     * @return string
     */
    public function __invoke($options = array())
    {
        if (array_key_exists('render', $options)) {
            $render = $options['render'];
        } else {
            $render = true;
        }
        $socialOptions = new SocialOptions();
        
        $boardList = $options['boardList'];
        $valueOptions = array();
        $valueOptions[''] = 'Select Board';
        foreach ($boardList as $container) {
            $valueOptions[$container->getRackId()] = $container->getRackName()->getName();
        }
        
        $countryData = $options['countryData'];
        
        if (array_key_exists('registration_plugin_base_path', $options)) {
            $registration_plugin_base_path = $options['registration_plugin_base_path'];
        } else {
            $registration_plugin_base_path = false;
        }
        
        // source , referer , current_page
        $source = $options['source'];
        $referer = $options['referer'];
        $current_page = $options['current_page'];
        
        // ip and country related 
        $southafricaLoaded = $this->southAfricaLoaded;
        
        $comMapperObj = $this->commMapper;
        
        if($southafricaLoaded === true){
            $ipCaptureDetails['country'] = 'SOUTH AFRICA';
            $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
            $_SESSION['user_session_ip_state'] = 0;
            $_SESSION['user_session_ip_city'] = 0;
        }else{
            unset($_SESSION['user_session_ip_country']);
            unset($_SESSION['user_session_ip_state']);
            unset($_SESSION['user_session_ip_city']);
        }      
        if(!isset($_SESSION['user_session_ip_country']) && !isset($_SESSION['user_session_ip_state']) && !isset($_SESSION['user_session_ip_city']) ){
            if (array_key_exists('HTTP_X_FORWARDED_FOR', $_SERVER)){
                $ip_address = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }else{
                $ip_address = $_SERVER['REMOTE_ADDR'];
            }
            
            if($southafricaLoaded === true){
                $ipCaptureDetails['country'] = 'SOUTH AFRICA';
                $_SESSION['user_session_ip_country'] = $ipCaptureDetails['country'];
                $_SESSION['user_session_ip_state'] = 0;
                $_SESSION['user_session_ip_city'] = 0;
            }else{
                // fetching country/state/city based on IP
                $tableIpCountry = $this->ipCountryTableGateway;
                $ipResultSet = $tableIpCountry->ipRange($ip_address);
                $ipCaptureDetails = $comMapperObj->useripcapturefunction($ipResultSet,$ip_address);
            }
           
            if($ipCaptureDetails['country']){
                $countaryidDetails = $comMapperObj->getCountryIdByCountryName($ipCaptureDetails['country']);
                if(count($countaryidDetails) >0){
                    $countaryidDetailsNew = $countaryidDetails[0];
                    $_SESSION['statelist'] = $comMapperObj->getCountarybystate($countaryidDetailsNew->getCountryId());
                }
            }
        }
        // ip and country related ends here
        
        $vm = new ViewModel(array(
            'registerForm' => $this->registerForm,
            'southafricaLoaded' => $southafricaLoaded,
            'socialOptions'  => $socialOptions,
            'enableRegistration'  => true,
            'boardList' => $valueOptions,
            'countryData' => $countryData,
            'registration_plugin' => true,
            'registration_plugin_base_path' => $registration_plugin_base_path,
            'source' => $source,
            'referer' => $referer,
            'current_page' => $current_page
        ));
        $vm->setTemplate($this->viewTemplate);
        if ($render) {
            return $this->getView()->render($vm);
        } else {
            return $vm;
        }
    }

   
    /**
     * Inject Register Form Object
     * @param RegisterForm $loginForm
     * @return ZfcUserRegisterWidget
     */
    public function setRegisterForm(RegisterForm $registerForm , $southAfricaLoaded)
    {
        $this->registerForm = $registerForm;
        $this->southAfricaLoaded = $southAfricaLoaded;
        return $this;
    }
    
    public function setIpCountryTableGateway($ipCountryTableGateway, $commMapper) {
        $this->ipCountryTableGateway = $ipCountryTableGateway;
        $this->commMapper      = $commMapper;
    }
    
    
    /**
     * @param string $viewTemplate
     * @return ZfcUserLoginWidget
     */
    public function setViewTemplate($viewTemplate)
    {
        $this->viewTemplate = $viewTemplate;
        return $this;
    }
    
    
}
