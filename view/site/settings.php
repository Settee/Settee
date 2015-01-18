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
                            <h3><?php echo $this->lang->i18n('site_profile'); ?></h3>
                            <div class="label">: <?php echo $this->lang->i18n('site_profile_informations'); ?></div>
                        </div>
                        <form enctype="multipart/form-data" method="post" action="<?php echo Dispatcher::base(); ?>settings/update">
                            <div class="avatarup">
                                <div class="preview">
                                    <img src="<?php echo $this->user->getUserAvatar($this->user->getActiveUser("id")); ?>" alt="Avatar preview" />
                                </div>
                                <div class="upload">
                                    <label>
                                        <input type="file" id="upload" name="avatar" accept="image/*"/><?php echo $this->lang->i18n('site_upload'); ?>
                                    </label>
                                </div>
                            </div>

                            <label for="names"><?php echo $this->lang->i18n('site_name'); ?> :</label>
                            <input type="text" id="names" placeholder="<?php echo $this->lang->i18n('site_placeholder_name'); ?>" name="names" value="<?php echo $this->user->getActiveUser('surname') ;?>" />
                            <label for="language"><?php echo $this->lang->i18n('site_language'); ?></label>
                            <select name="language">
                                <?php foreach ($this->lang->getListLanguage() as $k => $v){
                                    $mylang = ($v == $this->user->getActiveUser('lang'))? ' selected': '';
                                    echo '<option value="'.$v.'" '.$mylang.'>'.ucfirst($v).'</option>';
                                }?>
                            </select>

                            <div class="title">
                                <h3><?php echo $this->lang->i18n('site_account'); ?></h3>
                                <div class="label">: <?php echo $this->lang->i18n('site_account_informations'); ?></div>
                            </div>

                            <label for="email">Email :</label>
                            <input type="email" id="email" name="email" placeholder="name@domain.tld" value="<?php echo $this->user->getActiveUser('email') ;?>"/>

                            <label for="password"><?php echo $this->lang->i18n('site_password'); ?> :</label>
                            <input type="password" id="password" name="password" placeholder="<?php echo $this->lang->i18n('site_placeholder_password'); ?>" />

                            <label for="password"><?php echo $this->lang->i18n('site_password_again'); ?> :</label>
                            <input type="password" id="passwordagain" name="passwordagain" placeholder="<?php echo $this->lang->i18n('site_placeholder_password_again'); ?>" />

                            <input type="submit" id="submit" value="<?php echo $this->lang->i18n('site_save'); ?>" />
                        </form>
                        <?php if(Controller::privacy() == 3): ?>   
                            <hr/>

                            <div class="title">
                                <h3><?php echo $this->lang->i18n('site_invite'); ?></h3>
                                <div class="label">: <?php echo $this->lang->i18n('site_send_invite'); ?></div>
                            </div>

                            <form method="post" action="<?php echo Dispatcher::base(); ?>invite_user">
                                <input type="email" id="invite" placeholder="friend@domain.tld" />
                                <input type="submit" id="submit" value="<?php echo $this->lang->i18n('site_send_button'); ?>" />
                            </form>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>