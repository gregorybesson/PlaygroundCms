<?php

namespace PlaygroundCms\Grid;

use AtDataGrid\DataGrid;
use AtDataGrid\DataGrid\Filter\Sql as SqlFilter;
use AtDataGrid\DataGrid\Column\Decorator;
use Zend\Form\Element;

class Page extends DataGrid\DataGrid
{
    public function init()
    {
        parent::init();

        $this->setCaption('Manage pages')
             ->setIdentifierColumnName('page_id');

        // id
        $this->getColumn('page_id')
             ->setLabel('#')
             ->setVisibleInForm(false)
             ->setSortable(true);

        // uri
        $this->getColumn('identifier')
             ->setLabel('Page identifier (uri)')
             ->addFilter(new SqlFilter\Like())
             ->setSortable(true);

        // title
        $this->getColumn('title')
             ->setLabel('Title')
             ->addFilter(new SqlFilter\Like());

        // content
        //$content = new ATF_DataGrid_Column_Wysiwyg('content');
        $this->getColumn('content')
             ->setLabel('Page Content')
             ->setVisible(false);
        //$this->addColumn($content, true);
		
		// heading
        //$heading = new ATF_DataGrid_Column_Wysiwyg('heading');
        $this->getColumn('heading')
             ->setLabel('Page Heading')
             ->setVisible(false);

        // description
        $this->getColumn('meta_description')
             ->setLabel('Meta Description')
             ->setVisible(false);

        // keywords
        $this->getColumn('meta_keywords')
             ->setLabel('Meta Keywords')
             ->setVisible(false);

        // created_at
        $this->getColumn('created_at')
             ->setLabel('Date Created')
             ->setVisibleInForm(false);

        // updated_at
        $this->getColumn('updated_at')
             ->setLabel('Last Modified')
             ->setVisibleInForm(false);

        // is_active
        $activeFilterFormElement = new Element\Select('active');
        $activeFilterFormElement->setValueOptions(array('' => 'All', 0 => 'No', 1 => 'Yes'));

        $this->getColumn('active')
             ->setLabel('Enabled')
             ->addDecorator(new Decorator\YesNo())
             ->addFilter(new SqlFilter\Equal())
             ->setFormElement(new Element\Checkbox('active'))
             ->setFilterFormElement($activeFilterFormElement);

        // sort_order
        $this->getColumn('sort_order')
             ->setLabel('Sort Order');
   }
}
