<?php require_once 'header.php'; ?>
<section>
            <div class="content">
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_categories'); ?></h2>
                </div>
                <div class="pagecontent">
                    <table>
                    <tr>
                        <th>Nom</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($this->posts->getCategories() as $k => $v): ?>
                        <tr>
                            <td>
                                    <?php echo $v->name; ?><br/>
                            </td>
                            <td>
                                <a href="<?php echo Dispatcher::base(); ?>admin/editcategorie/<?php echo $v->id; ?>">Edit</a>
                                <a href="<?php echo Dispatcher::base(); ?>admin/deletecategory/<?php echo $v->id; ?>">Delete</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                    <div class="contentform">
                        <hr />
                        <div class="title">
                                <h3><?php echo $this->lang->i18n('site_categories'); ?></h3>
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