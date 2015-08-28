<?php

/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/Common for the canonical source repository
 * @copyright Copyright (c) 2005-2012 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Common\Controller;

use Zend\Mvc\Controller\AbstractActionController;

class IndexController extends AbstractActionController {

    public function indexAction() {

        return array();
    }

    public function fooAction() {
        // This shows the :controller and :action parameters in default route
        // are working when you browse to /index/index/foo
        return array();
    }

    public function getStateByCountryAction() {
        $request = $this->getRequest();
        $post = $request->getPost();

        $comMapperObj = $this->getServiceLocator()->get("com_mapper");
        $statelist = $comMapperObj->getCountarybystate($post->country_id);

        $stateArray = array();
        $data = array('output' => 'nosuccess');
        foreach ($statelist as $stateObj) {
            $stateArray[$stateObj->getStateId()] = $stateObj->getStateName();
            $data = array('output' => 'success');
        }
        $temp_array = array('states' => $stateArray);

        echo json_encode(array_merge($data, $temp_array));
        exit;
    }

}
