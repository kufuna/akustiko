<?php

class PageHeader
{
    public static function init($request)
    {

        global $CFG;

        $siteLangs = $CFG["LANG_SHORT_NAMES"];

        $data = array(
//            'alternates' => $request->alternates,
//            'module' => $request->module,
//            'siteLangs' => $siteLangs
        );

        return (object)array("tpl" => "header", "data" => (object)$data);
    }
}
