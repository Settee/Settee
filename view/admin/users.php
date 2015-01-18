<?php require_once 'header.php'; ?>
<section>
            <div class="content">
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_members'); ?></h2>
                </div>
                <div class="pagecontent">
                    <?php foreach($this->user->getAllUsers() as $k => $v): ?>
			<p><?php echo $v->surname; ?></p>
                    <?php endforeach; ?>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>