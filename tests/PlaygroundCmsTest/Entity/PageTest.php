<?php

namespace PlaygroundCmsTest\Entity;

use PlaygroundCmsTest\Bootstrap;
use PlaygroundCms\Entity\Page;

class PageTest extends \PHPUnit_Framework_TestCase
{
    protected $traceError = true;

    public function setUp()
    {
        $this->sm = Bootstrap::getServiceManager();
        $this->em = $this->sm->get('doctrine.entitymanager.orm_default');
        $tool = new \Doctrine\ORM\Tools\SchemaTool($this->em);
        $classes = $this->em->getMetadataFactory()->getAllMetadata();
        $tool->dropSchema($classes);
        $tool->createSchema($classes);
        parent::setUp();
    }

    public function testPopulate()
    {
        $page = new Page();
        $page->populate(array(
            "title" => "Titre de la page",
            "meta_keywords" => "key_words",
            "meta_description" => 'description',
            "content" => 'some content',
            "heading" => 'a heading',
            "category" => 1,
            "active" => 1,
            "sort_order" => 0,
            "pushHome" => 1,
            "displayHome" => 0
        ));

        $this->assertEquals("Titre de la page", $page->getTitle());
        $this->assertEquals("key_words", $page->getMetaKeywords());
        $this->assertEquals('description', $page->getMetaDescription());
        $this->assertEquals("some content", $page->getContent());
        $this->assertEquals("a heading", $page->getHeading());
        $this->assertEquals(1, $page->getCategory());
        $this->assertInternalType('integer', $page->getCategory());
        $this->assertTrue($page->getActive());
        $this->assertTrue($page->getPushHome());

        $page = new Page();
        $page->populate(array("active" => 0));
        $this->assertNull($page->getTitle());
        $this->assertFalse($page->getActive());
    }

    public function tearDown()
    {
        $dbh = $this->em->getConnection();
        unset($this->sm);
        unset($this->em);
        parent::tearDown();
    }
}
