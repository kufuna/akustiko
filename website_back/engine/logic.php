<?php
if(!session_id()) {
  session_start(); // Sometimes we start the session earlier
}

/* Include required libraries     */
require_once __DIR__.'/includes.php';

/* Check DB connection */
if(!DB::connect()) {
  CN_Error::err("Can't connect to database"); // Stops execution
}

URL::parse(); // Get variables from url (Controller initialization)
Lang::init(URL::$vars); // Language initialization

//include custom logic
$fileName= ROOT.'/'.DIR_BACK.'/'.'logic.php';
if(file_exists($fileName)) include_once($fileName);

$Model = new Model(URL::$vars); // Model initialization
