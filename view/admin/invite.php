<?php require_once 'header.php'; ?>
<section>
            <div class="content">
                    <?php echo $this->notif->getNotification(); ?>
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_invite'); ?></h2>
                </div>
                <div class="pagecontent">
                    <div class="contentform">
                        <div class="title">
                                <h3><?php echo $this->lang->i18n('site_invite'); ?></h3>
                                <div class="label">: <?php echo $this->lang->i18n('site_send_invite'); ?></div>
                            </div>

                            <form method="post" action="<?php echo Dispatcher::base().Dispatcher::whaturl(); ?>">
                                <input type="text" id="invite" placeholder="user@domain.tld" name="email"/>
                                <input type="submit" id="submit" value="<?php echo $this->lang->i18n('site_send_button'); ?>" />
                            </form>
                    </div>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>