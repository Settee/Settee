<?php require_once 'header.php'; ?>
    <section>
            <div class="content">
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_settings'); ?></h2>
                </div>
                <div class="pagecontent">
                    <div class="contentform">
                        <?php echo $this->notif->getNotification(); ?>
                        <div class="title">
                            <h3>Settee</h3>
                            <div class="label">: <?php echo $this->lang->i18n('site_settings'); ?></div>
                        </div>
                        <form method="post" action="<?php echo Dispatcher::base(); ?>admin/settings">

                            <label for="names"><?php echo $this->lang->i18n('site_name'); ?> :</label>
                            <input type="text" id="names" placeholder="<?php echo $this->lang->i18n('site_placeholder_name'); ?>" name="names" value="<?php echo CONFIG::WEBSITE; ?>" />
                            
                            <div class="title">
                                <h3><?php echo $this->lang->i18n('site_privacy'); ?></h3>
                                <div class="label">: <?php echo $this->lang->i18n('site_access_level'); ?></div>
                            </div>
                                <?php
                                $public = '';$publicregistration = '';$privateadminvalitation = '';$privatememberinvite = '';$privateadmininvite = '';
                                switch (CONFIG::PRIVACY) {
                                    case 'public':
                                    $public = ' checked';
                                        break;
                                        case 'publicregistration':
                                    $publicregistration = ' checked';
                                        break;
                                        case 'privateadminvalitation':
                                    $privateadminvalitation = ' checked';
                                        break;
                                        case 'privatememberinvite':
                                    $privatememberinvite = ' checked';
                                        break;
                                        case 'privateadmininvite':
                                    $privateadmininvite = ' checked';
                                        break;
                                }
                                ?>
                                <input type="radio" name="accesslevel" id="public" value="public" required="required"<?php echo $public; ?>><?php echo $this->lang->i18n('site_level_public'); ?><br/>
                                <input type="radio" name="accesslevel" id="publicregistration" value="publicregistration" required="required"<?php echo $publicregistration; ?>><?php echo $this->lang->i18n('site_level_publicregistration'); ?><br/>
                                <input type="radio" name="accesslevel" id="privateadminvalitation" value="privateadminvalitation" required="required"<?php echo $privateadminvalitation; ?>><?php echo $this->lang->i18n('site_level_privateadminvalitation'); ?><br/>
                                <input type="radio" name="accesslevel" id="privatememberinvite" value="privatememberinvite" required="required"<?php echo $privatememberinvite; ?>><?php echo $this->lang->i18n('site_level_privatememberinvite'); ?><br/>
                                <input type="radio" name="accesslevel" id="privateadmininvite" value="privateadmininvite" required="required"<?php echo $privateadmininvite; ?>><?php echo $this->lang->i18n('site_level_privateadmininvite'); ?><br/>
                            <input type="submit" id="submit" value="<?php echo $this->lang->i18n('site_save'); ?>" />
                        </form>
                    </div>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>