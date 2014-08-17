<?php require 'header.php'; ?>
<div class="container">
    <section id="page" class="fullwidth">
        <div class="content">

            <div id="feedhead">
                <div class="title">
                    <h2>Private area</h2>
                </div>
                <div class="clearfloat"></div>
            </div>

            <div class="pagecontent">
                <p class="center">
                    <span class="title">Sorry, you must be logged to access this site.</span>
                    <br/>
                    <a href="<?php echo Dispatcher::base(); ?>login" class="loglink">Login</a>
                    <br/>
                    <a href="<?php echo Dispatcher::base(); ?>register" class="loglink">Register</a>
                </p>
            </div>
        </div>
    </section>
</div>