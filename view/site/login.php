<?php require_once 'header.php'; ?>
    <section id="signpage">
            <div class="content">
                <div id="pagehead">
                    <h2>Login</h2>
                </div>
                <div class="pagecontent">
                    <div class="content">
                        <div class="contentform">
                            <form method="post" action="<?php echo Dispatcher::base(); ?>login">
                                <input type="text" placeholder="Username" name="login" required="required" />
                                <input type="password" title="" placeholder="Password" name="passwd" required="required" />
                                    <?php echo $this->posts->getNotification();?>
                                <input type="submit" value="Sign in" />
                            </form>
                            <div class="signfooter">
                                <p>Not Member? <b><a href="<?php echo Dispatcher::base(); ?>register" title="Register">Register</a></b>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
 <?php require_once 'footer.php'; ?>