<?php require_once 'header.php'; ?>
    <div class="container">
        <section id="signpage" class="fullwidth">
            <div class="content">

                <div class="title">
                    <h2>Register</h2>
                </div>
                <div class="contentform">
                    <form method="post" action="<?php echo Dispatcher::base(); ?>register">
                        <input type="text" placeholder="Username" name="login" required="required" />
                        <input type="email" placeholder="Email" name="email" required="required" />
                        <input type="password" title="" placeholder="Password" name="passwd" required="required" />
                        <?php echo (isset($_SESSION['e_out']) && !empty($_SESSION['e_out'])) ? $_SESSION['e_out'] : ''; unset($_SESSION['e_out']); ?>
                        <input type="submit" value="Register" />
                    </form>
                    <div class="signfooter">
                        <p>Already a Member? <a href="<?php echo Dispatcher::base(); ?>login" title="Login">Login</a></p>
                    </div>
                </div>
            </div>
        </section>
    </div>
 <?php require_once 'footer.php'; ?>