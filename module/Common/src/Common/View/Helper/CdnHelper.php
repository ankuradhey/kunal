<?php

namespace Common\View\Helper;
 
use Zend\Http\Request;
use Zend\ServiceManager\ServiceManager;
use Zend\View\Helper\AbstractHelper;
 
class CdnHelper extends AbstractHelper {
    public function __construct(Request $request, ServiceManager $serviceLocator) {
        $this->request        = $request;
        $this->serviceLocator = $serviceLocator;
    }
 
    /**
     * Get URL on CDN servers.
     * @param  String $filePath - the relative path of assets file path
     * @return the url of assets file path
     */
    public function __invoke($filePath = null) {
        $config  = $this->serviceLocator->get('config');
        if( !array_key_exists('cdn', $config) ) {
            return $filePath;
        }
 
        $options     = $config['cdn'];
        $cdnDomain   = self::getCdnDomain($filePath, $options);
        return self::getCdnUrl($cdnDomain, $filePath);
    }
 
    /**
     * Use File Extension to get the domain of CDN.
     * @param  String $filePath - the relative path of assets file path
     * @return the domain of the CDN server
     */
    public function getCdnDomainUrl($filePath)  {
        $cdnDomain   = self::getCdnDomain($filePath);
        return '//' . rtrim($cdnDomain, '/');
    }
 
    /**
     * Use File Extension to get the domain of CDN.
     * @param  String $filePath - the relative path of assets file path
     * @param  Array $options - CDN service settings
     * @return the domain of the CDN server
     */
    private function getCdnDomain($filePath, $options) {
        $assetName   = basename($filePath);
        foreach( $options as $fileExt => $cdnDomain ) {
            if( preg_match('/^.*\.('.$fileExt.')$/i', $assetName) ) {
                return $cdnDomain;
            }
        }
        $cdnDomain   = $options['default'];
        return $cdnDomain;
    }
 
    /**
     * Get the url of assets files.
     * @param  String $cdnDomain - the domain of CDN server
     * @param  String $filePath  - the relative path of assets file path
     * @return the url of assets file path
     */
    private function getCdnUrl($cdnDomain, $filePath) {
         if(!empty($filePath))
            return  '//' . rtrim($cdnDomain, '/') . '/' . ltrim($filePath, '/');
        else
            return '//'.rtrim($cdnDomain, '/');
    }
 
    public function getRequest() {
        return $this->request;
    }
 
    protected $request;
 
    protected $serviceLocator;
}