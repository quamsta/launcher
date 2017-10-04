<?php

global $project;
$project = 'mysite';

global $database;
$database = 'urlCreator';

require_once 'conf/ConfigureFromEnv.php';
LeftAndMain::require_css('mysite/css/cms.css');
// Set the site locale
i18n::set_locale('en_US');
