<?php 
$var = explode('/', Dispatcher::whaturl());
if(isset($var[1]) && !empty($var[1])){
    $param = $var[1];
    require_once 'header.php';
}else{
    die(Template::theme('404'));
}?>
    <div class="container">
         <aside>
            <div class="sidecontainer">
                <div id="headercatmobile">
                    <div class="closecatmobile">
                        <a href="" title="Close Categories">
                            <img src="<?php echo Template::tmpdir('images'); ?>ico-close.svg" alt="Close Categories" />
                        </a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <h2>Categories</h2>
                <?php echo Template::categorie('list'); ?>
            </div>
        </aside>
        <section>
            <div class="content">

                <div id="feedhead">
                    <div class="title">
                        <h2>Timeline</h2>
                    </div>
                    <div class="menubutton">
                        <a href="" title="Categories" class="showcatmobile">
                            <img src="<?php echo Template::tmpdir('images'); ?>menu2.svg" alt="Categories" />
                        </a>
                    </div>
                    <div class="clearfloat"></div>
                </div>
                <?php echo (isset($_SESSION['e_out']) && !empty($_SESSION['e_out'])) ? $_SESSION['e_out'] : ''; unset($_SESSION['e_out']); ?>
                <div id="newpost">
                    <div class="leftavatar">
                        <form enctype="multipart/form-data" method="post" action="<?php echo Dispatcher::base(); ?>post/add">
                            <div class="textarea">
                                <textarea name="post" id="field1" required="required" placeholder="Write, upload, share…"></textarea>
                            </div>
                            <div class="addpostfooter">
                                <div class="postfooterleft">
                                    <div class="selectcat">
                                        <?php echo Template::categorie('post'); ?>
                                    </div>
                                    <div class="uploadfile">
                                        <input type="file" accept="image/*" name="file" title="Add a image">
                                        <span class="button">Add a image</span>
                                    </div>
                                </div>
                                <div class="postfooterright">
                                    <input value="Submit" type="submit" />
                                </div>
                                <div class="clearfloat"></div>
                            </div>

                        </form>
                    </div>
                </div>

                <div id="feed">
                    <?php echo Template::posts($param); ?>
                </div>
            </div>
        </section>
        <div id="asiderightwrap">
            <div id="asideright">
                <div id="headercomments">
                    <div class="closecomments">
                        <a href="" title="Close comments">

                            <img src="<?php echo Template::tmpdir('images'); ?>ico-close.svg" alt="Close comments" />
                            <span>Close comments</span>
                        </a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div class="postcomments" id="post_com">
                    <div class="listcomments">
                        <ul>
                            <?php echo Template::comment(); ?>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript" src="<?php echo Template::tmpdir('js'); ?>scrollbar.js"></script>
    <script type="text/javascript" src="<?php echo Template::tmpdir('js'); ?>panel.js"></script>
</body>
</html>