<?php require_once 'header.php'; ?>
    <div class="container">
         <aside>
            <div class="sidecontainer">
                <div id="headercatmobile">
                    <div class="closecatmobile">
                        <a href="" title="Close Categories">
                            <img src="<?php echo $this->pages->getStyleDirectory('images'); ?>ico-close.svg" alt="Close Categories" />
                        </a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <h2>Categories</h2>
                <?php echo $this->posts->getCategories(null,'list'); ?>
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
                            <img src="<?php echo $this->pages->getStyleDirectory('images'); ?>menu2.svg" alt="Categories" />
                        </a>
                    </div>
                    <div class="clearfloat"></div>
                </div>

                <div id="feed">
                    <?php 
                        $param = explode('/', Dispatcher::whaturl());
                        $cat = current($this->database->sqlquery('SELECT * FROM '.CONFIG::PREFIX.'_categorie WHERE url = "'.$this->database->secure($param[1]).'" LIMIT 1','query'));
                        echo $this->posts->getPostsCategory($cat->id); 
                    ?>
                </div>
            </div>
        </section>
        <div id="asiderightwrap">
            <div id="asideright">
                <div id="headercomments">
                    <div class="closecomments">
                        <a href="" title="Close comments">

                            <img src="<?php echo $this->pages->getStyleDirectory('images'); ?>ico-close.svg" alt="Close comments" />
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
<?php require_once 'footer.php'; ?>