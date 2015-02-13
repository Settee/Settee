<?php 
require_once 'header.php'; 
$param = explode('/', Dispatcher::whaturl());
$data = null;
foreach ($this->posts->getCategories() as $k => $v){
  if($v->id == $param[2]){
    $data = $v;
  }
}
if($data == null){
  die();
}
?>
<section>
  <div class="content">
      <div id="pagehead">
          <h2><?php echo $this->lang->i18n('site_categories'); ?></h2>
      </div>
      <div class="pagecontent">
          <div class="contentform">
              <div class="title">
                  <h3><?php echo $this->lang->i18n('site_categories'); ?></h3>
              </div>
              <form method="post" action="<?php echo Dispatcher::base().Dispatcher::whaturl() ?>">

                  <label for="name"><?php echo $this->lang->i18n('site_name'); ?> :</label>
                  <input type="text" id="name" name="name" value="<?php echo $data->name; ?>">
                  
                  <label for="slug">Slug :</label>
                  <input type="text" id="slug" name="slug" value="<?php echo $data->url; ?>">
                  
                  <input type="submit" id="submit" value="<?php echo $this->lang->i18n('site_save'); ?>">
              </form>
          </div>
      </div>
  </div>
</section>
 <?php require_once 'footer.php'; ?>