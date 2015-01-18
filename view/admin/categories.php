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
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>