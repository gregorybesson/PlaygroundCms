<?php

namespace PlaygroundCmsTest\Controller\Frontend;
use \PlaygroundCms\Entity\Page as PageEntity;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class IndexControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../TestConfig.php'
        );

        parent::setUp();
    }

    public function testIndexActionNoIdentifier()
    {
    
    	$this->dispatch('/page');
    	$this->assertResponseStatusCode(404);
    }
    
    public function testIndexActionNonExistentPage()
    {
        $serviceManager = $this->getApplicationServiceLocator();
        $serviceManager->setAllowOverride(true);

        $pluginManager    = $this->getApplicationServiceLocator()->get('ControllerPluginManager');

        $page = new PageEntity();

        //mocking the method checkExistingEntry
        $f = $this->getMockBuilder('PlaygroundCms\Service\Page')
        ->setMethods(array('getPageMapper'))
        ->disableOriginalConstructor()
        ->getMock();

        $serviceManager->setService('playgroundcms_page_service', $f);
        
        $pageMapperMock = $this->getMockBuilder('PlaygroundCms\Mapper\Page')
        ->disableOriginalConstructor()
        ->getMock();
        
        $f->expects($this->once())
        ->method('getPageMapper')
        ->will($this->returnValue($pageMapperMock));
        
        $pageMapperMock->expects($this->once())
        ->method('findByIdentifier')
        ->will($this->returnValue(false));

    	$this->dispatch('/page/fakepage');
    	$this->assertResponseStatusCode(404);
    }
    
    public function testIndexActionNonActivePage()
    {
    	$serviceManager = $this->getApplicationServiceLocator();
    	$serviceManager->setAllowOverride(true);
    
    	$pluginManager    = $this->getApplicationServiceLocator()->get('ControllerPluginManager');
    
    	$page = new PageEntity();
    	$page->setActive(false);
    
    	//mocking the method checkExistingEntry
    	$f = $this->getMockBuilder('PlaygroundCms\Service\Page')
    	->setMethods(array('getPageMapper'))
    	->disableOriginalConstructor()
    	->getMock();
    
    	$serviceManager->setService('playgroundcms_page_service', $f);
    
    	$pageMapperMock = $this->getMockBuilder('PlaygroundCms\Mapper\Page')
    	->disableOriginalConstructor()
    	->getMock();
    
    	$f->expects($this->once())
    	->method('getPageMapper')
    	->will($this->returnValue($pageMapperMock));
    
    	$pageMapperMock->expects($this->once())
    	->method('findByIdentifier')
    	->will($this->returnValue($page));
    
    	$this->dispatch('/page/fakepage');
    	$this->assertResponseStatusCode(404);
    }
    
    public function testIndexActionPage()
    {
    	$serviceManager = $this->getApplicationServiceLocator();
    	$serviceManager->setAllowOverride(true);
    
    	$pluginManager    = $this->getApplicationServiceLocator()->get('ControllerPluginManager');
    
    	$page = new PageEntity();
    	$page->setActive(true);
    	$page->setTitle('titre');
    	$page->setContent('content');
    	$page->setIdentifier('fakepage');
    
    	//mocking the method checkExistingEntry
    	$f = $this->getMockBuilder('PlaygroundCms\Service\Page')
    	->setMethods(array('getPageMapper'))
    	->disableOriginalConstructor()
    	->getMock();
    
    	$serviceManager->setService('playgroundcms_page_service', $f);
    
    	$pageMapperMock = $this->getMockBuilder('PlaygroundCms\Mapper\Page')
    	->disableOriginalConstructor()
    	->getMock();
    
    	$f->expects($this->once())
    	->method('getPageMapper')
    	->will($this->returnValue($pageMapperMock));
    
    	$pageMapperMock->expects($this->once())
    	->method('findByIdentifier')
    	->will($this->returnValue($page));
    
    	$this->dispatch('/page/fakepage');
    	
    	$this->assertModuleName('playgroundcms');
    	$this->assertActionName('index');
    	$this->assertControllerName('playgroundcms');
    	$this->assertControllerClass('IndexController');
    	$this->assertMatchedRouteName('frontend/cms');

    }
}
