<?php require_once 'header.php'; ?>
<section>
            <div class="content">
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_categories'); ?></h2>
                </div>
                <div class="pagecontent">
                    <?php foreach($this->posts->getCategories() as $k => $v): ?>
			<p><?php echo $v->name; ?></p>
                    <?php endforeach; ?>
                    <div class="contentform">
                        <hr />
                        <div class="title">
                                <h3><?php echo $this->lang->i18n('site_invite'); ?></h3>
                                <div class="label">: <?php echo $this->lang->i18n('site_send_invite'); ?></div>
                            </div>

                            <form method="post" action="<?php echo Dispatcher::base(); ?>admin/categories">
                                <input type="text" id="invite" placeholder="My category" name="category"/>
                                <input type="submit" id="submit" value="<?php echo $this->lang->i18n('site_send_button'); ?>" />
                            </form>
                    </div>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>