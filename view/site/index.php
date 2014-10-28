<?php require_once 'header.php'; ?>
      <section>
            <div class="content">
                <div class="feedhead">
                    <h2><i class="fa fa-list"></i> General feed</h2>
                    <div id="reload">
                        <a href="<?php echo Dispatcher::base().Dispatcher::whaturl(); ?>" title="Reload the posts"></a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
                    <?php echo $this->pages->getNotification(); ?>
                    <?php echo $this->posts->getPosts(); ?>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>