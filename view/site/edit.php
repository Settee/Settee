<?php 
require_once 'header.php'; 
$param = explode('/', Dispatcher::whaturl());
$data = $this->posts->getPostInfo($param[1]);
?>
      <section>
            <div class="content">
                <div class="feedhead">
                    <h2><?php echo $this->lang->i18n('site_edit'); ?></h2>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
        <article>
            <div id="newpost">
                <?php echo $this->pages->getPostForm($data); ?>
            </div>
        </article>
     </div>
      </div>
</section>
 <?php require_once 'footer.php'; ?>