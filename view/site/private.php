<?php require 'header.php'; ?>
<div class="container">
    <section id="page" class="fullwidth">
        <div class="content">

            <div id="feedhead">
                <div class="title">
                    <h2><?php echo $this->lang->i18n('site_private_area'); ?></h2>
                </div>
                <div class="clearfloat"></div>
            </div>

            <div class="pagecontent">
                <p class="center">
                    <span class="title"><?php echo $this->lang->i18n('site_private_message'); ?></span>
                    <br/>
                    <a href="<?php echo Dispatcher::base(); ?>login" class="loglink"><?php echo $this->lang->i18n('site_login'); ?></a>
                    <br/>
                    <a href="<?php echo Dispatcher::base(); ?>register" class="loglink"><?php echo $this->lang->i18n('site_register'); ?></a>
                </p>
            </div>
        </div>
    </section>
</div>
 <?php require_once 'footer.php'; ?>