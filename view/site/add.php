<?php require_once 'header.php'; ?>
      <section>
            <div class="content">
                <div class="feedhead">
                    <h2><?php echo $this->lang->i18n('site_add'); ?></h2>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
		<article>
			<div id="newpost">
				<?php echo $this->pages->getPostForm(); ?>
			</div>
		</article>
	 </div>
      </div>
</section>
<?php require_once 'footer.php'; ?>