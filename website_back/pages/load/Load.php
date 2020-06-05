<?php

class Page_Load extends ContactPage
{
    protected $tpl = 'popup/email';
    private $data = null;

    public function __construct($request)
    {
        parent::__construct($request);
    }

    public function loadData()
    {
        if (isset($_POST['tpl'])) {
            if ($_POST['tpl'] == 'email') {
                $this->tpl = 'popup/email';
            } elseif ($_POST['tpl'] == 'success') {
                $this->tpl = 'popup/success';
            }
        }
    }

    public function getTemplates()
    {
        return array(
            (object)array("tpl" => $this->tpl, "data" => $this->data),
        );
    }

    public function getCanonical()
    {
        return SITE_URL . 'load';
    }

    protected $disableForSitemap = true;
}
