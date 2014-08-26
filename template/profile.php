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
                        <h2><?php echo strip_tags(Template::user(strtolower($param))->surname); ?>'s posts</h2>
                    </div>
                    <div class="menubutton">
                        <a href="" title="Categories" class="showcatmobile">
                            <img src="<?php echo Template::tmpdir('images'); ?>menu2.svg" alt="Categories" />
                        </a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div id="feed">
                    <?php echo Template::article($param); ?>
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

                <div class="postcomments" id="post_42">
                    <div class="listcomments">
                        <ul>
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