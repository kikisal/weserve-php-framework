<?php
/*
-------------------------------------------------------------------

██████╗ ██╗   ██╗    ██╗  ██╗██╗██╗  ██╗██╗███████╗ █████╗ ██╗     
██╔══██╗╚██╗ ██╔╝    ██║ ██╔╝██║██║ ██╔╝██║██╔════╝██╔══██╗██║     
██████╔╝ ╚████╔╝     █████╔╝ ██║█████╔╝ ██║███████╗███████║██║     
██╔══██╗  ╚██╔╝      ██╔═██╗ ██║██╔═██╗ ██║╚════██║██╔══██║██║     
██████╔╝   ██║       ██║  ██╗██║██║  ██╗██║███████║██║  ██║███████╗
╚═════╝    ╚═╝       ╚═╝  ╚═╝╚═╝╚═╝  ╚═╝╚═╝╚══════╝╚═╝  ╚═╝╚══════╝
                                                                   
-------------------------------------------------------------------
                        No Comment.
*/

ini_set( 'display_errors', 'on' );

define( 'APP', true );

define( '__CORE__', $_SERVER['DOCUMENT_ROOT'] . '/core' );
define( '__LIBDIR__', __CORE__ . '/utils/libs' );

require __CORE__ . '/web/SimpleTheme.php';
require __DIR__ . '/autoloader.php';


use KCoreWeb\SimpleTheme;

$theme = new SimpleTheme();

$theme->setTitle( 'Radio Generation' );


$theme->addResource( 'static' );
$theme->setDirectory('C:\\Users\\giuse\\Documents\\djshop\\radiogeneration-php');
$theme->setDev(true);

Autoloader::register([
    $theme->getRootDir() . '/src/',
    __DIR__ . "/libs/"
]);


header("Access-Control-Allow-Origin: *");
$theme->load();