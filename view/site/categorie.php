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
                    <?php 
                        $param = explode('/', Dispatcher::whaturl());
                        $cat = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE url = "'.$this->database->secure($param[1]).'" LIMIT 1','query'));
                        echo $this->posts->getPostsCategory($cat->id); 
                    ?>
                </div>
            </div>
        </section>
<?php require_once 'footer.php'; ?>



