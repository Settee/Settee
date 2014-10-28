<?php
    $url = explode('/', Dispatcher::whaturl());
    $username_id = $this->pages->getUserInfo($url[1])->id;
    require_once 'header.php';
?>
    <section>
            <div class="content">
                <div class="feedhead">
                    <h2><i class="fa fa-list"></i> <?php echo $this->pages->getUserInfo($username_id)->surname; ?>'s posts</h2>
                    <div id="reload">
                        <a href="<?php echo Dispatcher::base().Dispatcher::whaturl(); ?>" title="Reload the posts"></a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
                    <?php echo $this->pages->getNotification(); ?>
                    <?php echo $this->posts->getPostsProfile($username_id); ?>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>