<?php require_once 'header.php'; ?>
      <section>
            <div class="content">
                <div class="feedhead">
                    <h2><i class="fa fa-list"></i> <?php echo $this->lang->i18n('site_generalfeed'); ?></h2>
                    <div id="reload">
                        <a href="<?php echo Dispatcher::base().Dispatcher::whaturl(); ?>" title="<?php echo $this->lang->i18n('site_reload'); ?>"></a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
                    <?php 
                        echo $this->notif->getNotification();
                        echo $this->posts->getIndexPosts();
                    ?>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>