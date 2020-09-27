<?php
session_start();
require_once('Facebook/autoload.php');
$FBOject = new \Facebook\Facebook([
    'app_id' => '936885120077871',
    'app_secret' => '5aa156872aaf1f50c8d9b3fd124a71dc',
    'default_graph_version' => 'v2.10'
]);

$handler = $FBOject->getRedirectLoginHelper();
