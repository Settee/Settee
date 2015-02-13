<?php
require_once 'header.php'; 
$url = explode('/', Dispatcher::whaturl());
$data = $this->posts->getPostInfo($url[1]);
$me = $this->user->getUserById($data->author_id);
$cat = $this->posts->getComments($data->categorie_id,'info');
$likes = $this->database->sqlquery('SELECT posts.id as like_post, COUNT(likes.post_id) as nb_like FROM '.CONFIG::PREFIX.'_posts as posts, '.CONFIG::PREFIX.'_likes as likes WHERE likes.post_id = posts.id GROUP BY likes.post_id ORDER BY posts.id','query');
$comments = $this->database->sqlquery('SELECT posts.id as comments_post, COUNT(comments.post_id) as nb_comments FROM '.CONFIG::PREFIX.'_posts as posts, '.CONFIG::PREFIX.'_comments as comments WHERE comments.post_id = posts.id GROUP BY comments.post_id ORDER BY posts.id','query');

$nb_like = 0; $nb_comments = 0;
foreach($likes as $key => $value){
    if($data->id == $value->like_post){
        $nb_like = $value->nb_like;
    }
}

foreach($comments as $key => $value){
    if($data->id == $value->comments_post){
        $nb_comments = $value->nb_comments;
    }
}
?>
      <section>
            <div class="content">
                <div class="feedhead">
                    <h2><i class="fa fa-list"></i> <?php echo $this->lang->i18n('site_post'); ?></h2>
                    <div id="reload">
                        <a href="<?php echo Dispatcher::base().Dispatcher::whaturl(); ?>" title="<?php echo $this->lang->i18n('site_reload'); ?>"></a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div class="feed">
                    <?php echo $this->notif->getNotification(); ?>
                    <article>
                        <div class="post">
                            <div class="posthead">
                                <div class="avatar">
                                    <img src="<?php echo $this->user->getUserAvatar($me->id); ?>" alt="Avatar">
                                </div>
                                <div class="infos">
                                    <div class="name">
                                        <a href="<?php echo Dispatcher::base(); ?>profile/<?php echo $me->name; ?>" title="Posted by  <?php echo $me->surname; ?>" class="name"><?php echo $me->surname; ?></a>
                                    </div>
                                    <div class="datecat">
                                        <?php echo $this->general->getFullDate($data->date); ?> in <a href="<?php echo Dispatcher::base(); ?>category/<?php echo $cat->url; ?>" title="Posted in <?php echo $cat->name; ?>"><?php echo $cat->name; ?></a>
                                    </div>
                                </div>
                                <div class="clearfloat"></div>
                            </div>
                            <div class="posttext"><?php echo $data->post; ?></div>
                            <?php if($data->image != null): ?>
                                <div class="postimage">
                                    <a target="_blank" href="<?php echo Dispatcher::base(); ?>static/post/big/<?php echo $data->image; ?>">
                                            <img src="<?php echo Dispatcher::base(); ?>static/post/thumbnail/<?php echo $data->image; ?>" alt="Preview image">
                                    </a>
                                </div>
                            <?php endif; ?>
                                <div class="postfooter"><ul>
                                    <?php if($data->author_id == $this->user->getActiveUser('id')): ?>
                                        <li><a href="<?php echo Dispatcher::base(); ?>editpost/<?php echo $data->id; ?>" title="<?php echo $this->lang->i18n('site_edit'); ?>"><i class="fa fa-pencil"></i></a></li>
                                    <?php endif; ?>
                                    <?php if(($data->author_id == $this->user->getActiveUser('id')) || $this->user->getActiveUser('type') == 'root'): ?>
                                        <li><a href="<?php echo Dispatcher::base(); ?>deletepost/<?php echo $data->id; ?>" title="<?php echo $this->lang->i18n('site_delete'); ?>"><i class="fa fa-trash"></i></a></li>
                                    <?php endif;?>
                                    <li class="like"><a href="<?php echo Dispatcher::base(); ?>like/<?php echo $data->id; ?>" title="Like this post"><i class="fa fa-heart"></i><span><?php echo $nb_like; ?></span></a></li>
                                    <li class="buttonComments" id="<?php echo $data->id; ?>"><a href="" title="Read and write comments on this post"><i class="fa fa-comment"></i><span><?php echo $nb_comments; ?></span></a></li>
                                    <li><a href="<?php echo Dispatcher::base(); ?>share/<?php echo $data->id; ?>" title="<?php echo $this->lang->i18n('site_link'); ?>"><i class="fa fa-share"></i></a></li></ul>
                                <div class="clearfloat"></div>
                                </div>
                                <div class="comments opened">
                                    <ul>
                                        <?php echo $this->posts->getComments($url[1],'list'); ?>
                                    </ul>
                                    <?php if($this->auth->isLoged()): ?>
                                        <div class="addcomment">
                                            <form method="post" action="<?php echo Dispatcher::base(); ?>addcomment/<?php echo $url[1]; ?>">
                                                <textarea name="comment" name="comment" placeholder="Add a comment"></textarea>
                                                <input type="submit" value="<?php echo $this->lang->i18n('site_send_button'); ?>" />
                                            </form>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                    </article>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>
