<!DOCTYPE html>
<html lang="fr">
<head>
    <title><?php echo Config::WEBSITE; ?></title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scape=1" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->pages->getStyleDirectory('css'); ?>reset.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->pages->getStyleDirectory('css'); ?>font-awesome.min.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo $this->pages->getStyleDirectory('css'); ?>design.css" />
    <link rel="shortcut icon" href="<?php echo $this->pages->getStyleDirectory('images'); ?>favicon.png" />
</head>

<body>
    <div id="wrap">
        <header>
            <div id="logo">
                <h1><?php echo Config::WEBSITE; ?></h1>
            </div>
            <div class="clearfloat"></div>
        </header>
        <label for="menu-toggle">
            <div id="navhamburger"><i class="fa fa-bars"></i></div>
        </label>
        <input type="checkbox" id="menu-toggle" />
        <div class="asidewrap">
            <aside>
                <nav>
                    <?php if($this->auth->isLoged()): ?>
                        <div class="addpost">
                            <a href="<?php echo Dispatcher::base(); ?>addpostform" title="<?php echo $this->lang->i18n('site_placeholder_content'); ?>"><i class="fa fa-pencil-square-o"></i><span><?php echo  $this->lang->i18n('site_write'); ?></span></a>
                        </div>
                    <?php endif; ?>
                    <ul>
                        <?php echo $this->general->getSideNavBar(); ?>
                    </ul>
                </nav>
                <div id="cat" role="select">
                    <h2><?php echo $this->lang->i18n('site_categories'); ?></h2>
                    <?php echo $this->posts->getCategories(null,'list'); ?>
                </div>
            </aside>
        </div>