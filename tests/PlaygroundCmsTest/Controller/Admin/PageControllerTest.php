<?php

namespace PlaygroundCmsTest\Controller\Admin;

use Zend\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class PageControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../TestConfig.php'
        );

        parent::setUp();
    }

    public function testIndexAction()
    {
        $this->assertTrue(true);
    }
}
