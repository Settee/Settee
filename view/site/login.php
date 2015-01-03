<?php require_once 'header.php'; ?>
    <section id="signpage">
            <div class="content">
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_login'); ?></h2>
                </div>
                <div class="pagecontent">
                    <div class="content">
                        <div class="contentform">
                            <form method="post" action="<?php echo Dispatcher::base(); ?>login">
                                <input type="text" placeholder="<?php echo $this->lang->i18n('site_username'); ?>" name="login" required="required" />
                                <input type="password" title="<?php echo $this->lang->i18n('site_password'); ?>" placeholder="<?php echo $this->lang->i18n('site_password'); ?>" name="passwd" required="required" />
                                    <?php echo $this->notif->getNotification();?>
                                <input type="submit" value="<?php echo $this->lang->i18n('site_signin'); ?>" />
                            </form>
                            <div class="signfooter">
                                <p><?php echo $this->lang->i18n('site_not_member'); ?>? <b><a href="<?php echo Dispatcher::base(); ?>register" title="<?php echo $this->lang->i18n('site_register'); ?>"><?php echo $this->lang->i18n('site_register'); ?></a></b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
 <?php require_once 'footer.php'; ?>