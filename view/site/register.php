<?php require_once 'header.php'; ?>
    <section id="signpage">
            <div class="content">
                <div id="pagehead">
                    <h2>Register</h2>
                </div>
                <div class="pagecontent">
                    <div class="content">
                        <div class="contentform">
                            <form method="post" action="<?php echo Dispatcher::base(); ?>register">
                                <input type="text" placeholder="Username" name="login" required="required" />
                                <input type="email" placeholder="Email" name="email" required="required" />
                                <input type="password" title="" placeholder="Password" name="passwd" required="required" />
                                <?php echo $this->posts->getNotification();?>
                                <input type="submit" value="Sign up" />
                            </form>
                            <div class="signfooter">
                                <p>Already a Member? <a href="<?php echo Dispatcher::base(); ?>login" title="Login">Login</a>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
        </section>
 <?php require_once 'footer.php'; ?>