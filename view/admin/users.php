<?php require_once 'header.php'; ?>
<section>
            <div class="content">
                <div id="pagehead">
                    <h2><?php echo $this->lang->i18n('site_members'); ?></h2>
                </div>
                <div class="pagecontent">
                <table>
                    <tr>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($this->user->getAllUsers() as $k => $v): ?>
                        <tr>
                            <td>
                                <?php echo ($v->type == 'root')? '[Admin] ' : '';  echo $v->surname; ?>
                            </td>
                            <td><?php echo $v->email; ?></td>
                            <td>
                                <a href="<?php echo Dispatcher::base(); ?>deleteuser/<?php echo $v->id; ?>">Delete</a>
                                <?php if($v->type != 'root'): ?>
                                    <a href="<?php echo Dispatcher::base(); ?>admin/setadmin/<?php echo $v->id; ?>">Set Admin</a>
                                <?php else: ?>
                                    <a href="<?php echo Dispatcher::base(); ?>admin/setadmin/<?php echo $v->id; ?>">Remove Admin</a>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>