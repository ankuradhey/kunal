<?php
namespace ScnSocialAuth\View\Helper;

use Zend\View\Helper\AbstractHelper;

class SocialSignInButton extends AbstractHelper
{
    public function __invoke($provider, $redirect = false,$type=NULL)
    {
//        <div class="social_icon">
//  <a class="facebook" href="/livesite_em/user/facebook-login/"><span class="icon"></span><span class="name">Facebook</span></a>
//  <a class="googleplus" href="/livesite_em/user/google-login/"><span class="icon"></span><span class="name">Google</span></a>
//  <a class="twitter" href="/livesite_em/user/twitter-login/"><span class="icon"></span><span class="name">Twitter</span></a>
//  </div><span class="icons-'.$provider.'"></span><strong>' . ucfirst($provider) . '</strong>
        
        $redirectArg = $redirect ? '?redirect=' . $redirect : '';
        
        if($type=='old'){
        
        echo
            '<a class="'.$provider.'" href="'
            . $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider))
            . $redirectArg . '"><span class="icons-'.$provider.'"></span><strong>' . ucfirst($provider) . '</strong></a>';
    
        }else{
        echo
            '<a class="'.$provider.'Btn smGlobalBtn" href="'
            . $this->view->url('scn-social-auth-user/login/provider', array('provider' => $provider))
            . $redirectArg . '"></a>';
        }
    }
}
