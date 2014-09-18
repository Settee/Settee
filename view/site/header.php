<!DOCTYPE html>
<html lang="fr">

<head>
    <title><?php echo Config::WEBSITE; ?></title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="<?php echo $this->pages->getStyleDirectory('css'); ?>reset.css" />
    <link rel="stylesheet" href="<?php echo $this->pages->getStyleDirectory('css'); ?>design.css" />
    <meta name="viewport" content="width=device-width, initial-scape=1" />
    <link rel="shortcut icon" href="<?php echo $this->pages->getStyleDirectory('images'); ?>favicon.png" />
    <script src="http://code.jquery.com/jquery-1.10.2.js"></script>
    <script src="http://code.jquery.com/ui/1.10.4/jquery-ui.js"></script>
    <!--[if lt IE 9]>
	<script src="//html5shim.googlecode.com/svn/trunk/html5.js"></script>
	<![endif]-->
</head>

<body>
    <header>
        <div id="navhamburger">
            <a href="" title="Menu">
                <img src="<?php echo $this->pages->getStyleDirectory('images'); ?>menu.svg" alt="Menu" />
            </a>
        </div>
        <div id="hleft">
            <h1><a href="<?php echo Dispatcher::base(); ?>" title=""><?php echo Config::WEBSITE; ?></a></h1>
        </div>
        <div id="hright">
            <?php echo $this->pages->getHeaderNavBar(); ?>
        </div>
        <div class="clearfloat"></div>
    </header>
