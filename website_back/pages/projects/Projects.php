<?php

class Page_Projects extends Page
{
    private $data = null;

    public function __construct($request)
    {
        parent::__construct($request);
    }

    protected $paramBinding = array(
        1 => array('type' => 'number', 'name' => 'currentPage', 'default' => 1)
    );


    public function loadData()
    {

//        $news = DB::get(array(
//            'table' => 'news',
//            'query' => array('active' => 1),
//            'order' => 'date desc, id desc',
//        ));
//
//        $results = count($news);
//
//        $pagingConfig = array(
//            'pageSize' => 8,
//            'totalCount' => $results,
//            'currentPage' => $this->currentPage
//        );
//
//        $pageConfig = Paging::init($pagingConfig);
//
//        $this->currentPage = $pageConfig->currentPage;
//
//        $news = DB::get(array(
//            'table' => 'news',
//            'query' => array('active' => 1),
//            'order' => 'date desc, id desc',
//            'skip' => $pageConfig->skip,
//            'limit' => $pageConfig->limit,
//        ));
//        $paging = Paging::getModel($this->getCanonical());

        $this->seo = array(
            'title' => L('news_seo_title'),
            'description' => L('news_seo_description'),
            'keywords' => L('news_seo_keywords'),
            'image' => ROOT_URL.'img/fb_logo.jpg'
        );

        $this->data = (object)array(
//            'news' => $news,
//            'paging' => $paging,
//            'results' => $results,
//            'currentPage' => $this->currentPage,
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
            (object)array("tpl" => "projects", "data" => $this->data),
            PageFooter::init($this->request),
            $pageScripts->init($this->request),
            (object)array("tpl" => "foot", "data" => null)
        );
    }

    public function getCanonical()
    {
        return SITE_URL . 'projects/' . $this->currentPage . '/';
    }
}
