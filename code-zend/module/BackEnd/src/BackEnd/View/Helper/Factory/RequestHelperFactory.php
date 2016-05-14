<?php

namespace BackEnd\View\Helper\Factory;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class RequestHelperFactory implements FactoryInterface
{
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        $service = $serviceLocator->getServiceLocator();
        
        $helper = new \BackEnd\View\Helper\Requesthelper;
        
        $request = $service->get('Request');
        $helper->setRequest($request);
        
        return $helper;
    }
}

