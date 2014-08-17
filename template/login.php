<?php require_once 'header.php'; ?>
    <div class="container">
        <section id="signpage" class="fullwidth">
            <div class="content">

                <div class="title">
                    <h2>Login</h2>
                </div>

                <div class="contentform">
                    <form method="post" action="<?php echo Dispatcher::base(); ?>login">
                        <input name="login" type="text" placeholder="Username" required="required" />
                        <input name="passwd" type="password" title="" placeholder="Password" required="required" />
                        <?php echo (isset($_SESSION['e_out']) && !empty($_SESSION['e_out'])) ? $_SESSION['e_out'] : ''; unset($_SESSION['e_out']); ?>
                        <div class=".clearfloat"></div>
                        <input type="submit" value="Login" />
                    </form>
                    <div class="signfooter">
                        <p>Not Member? <b><a href="<?php echo Dispatcher::base(); ?>register" title="Register">Register</a></b></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
</body>
</html>