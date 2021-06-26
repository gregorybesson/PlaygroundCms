<?php

namespace PlaygroundCmsTest\Controller\Admin;

use Laminas\Test\PHPUnit\Controller\AbstractHttpControllerTestCase;

class DynablockControllerTest extends AbstractHttpControllerTestCase
{
    protected $traceError = true;

    protected function setUp(): void
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
