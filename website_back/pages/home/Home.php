<?php

class Page_Home extends Page
{
    private $data = null;

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function loadData()
    {

        $this->data = (object)array(

        );

    }

    public function getTemplates()
    {
        $this->request->canonical = $this->getCanonical();
        $this->request->alternates = $this->getAlternates();
        $this->request->pageObject = $this;

        $this->request->data = $this->data;

        $pageHead = new PageHead();
        $pageScripts = new PageScripts();

        return array(
            $pageHead->init($this->request),
            PageHeader::init($this->request),
            (object)array("tpl" => "home", "data" => $this->data),
            PageFooter::init($this->request),
            $pageScripts->init($this->request),
            (object)array("tpl" => "foot", "data" => null)
        );
    }

    public function getCanonical()
    {
        return SITE_URL;
    }
}
