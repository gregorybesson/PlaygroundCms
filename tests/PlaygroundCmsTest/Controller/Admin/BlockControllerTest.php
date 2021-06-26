<?php

namespace PlaygroundCmsTest\Controller\Admin;

use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class BlockControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected function setUp(): void
    {
        $this->setApplicationConfig(
            include __DIR__ . '/../../../TestConfig.php'
        );

        parent::setUp();
    }

    public function testCreateAction()
    {
        $this->assertTrue(true);
    }
    
    public function testEditAction()
    {
        $this->assertTrue(true);
    }
    
    public function testListAction()
    {
        $this->assertTrue(true);
    }
}
