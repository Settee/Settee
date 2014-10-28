<?php require_once 'header.php'; ?>
    <section>
            <div class="content">
                <div id="pagehead">
                    <h2>Settings</h2>
                </div>
                <div class="pagecontent">
                    <div class="contentform">
                        <?php echo $this->pages->getNotification(); ?>
                        <div class="title">
                            <h3>Profile</h3>
                            <div class="label">: Your profile informations</div>
                        </div>
                        <form enctype="multipart/form-data" method="post" action="<?php echo Dispatcher::base(); ?>settings/update">
                            <div class="avatarup">
                                <div class="preview">
                                    <img src="<?php echo $this->pages->getAvatar($this->pages->getInfo("id")); ?>" alt="Avatar preview" />
                                </div>
                                <div class="upload">
                                    <label>
                                        <input type="file" id="upload" name="avatar" accept="image/*"/>Upload
                                    </label>
                                </div>
                            </div>

                            <label for="names">Names:</label>
                            <input type="text" id="names" placeholder="Your public name" name="names" value="<?php echo $this->pages->getInfo('surname') ;?>" />
                            <!--<label for="language">Language:</label>
                            <select>
                                <option value="#">English</option>
                                <option value="#">Français</option>
                                <option value="#">Español</option>
                            </select>-->

                            <div class="title">
                                <h3>Account</h3>
                                <div class="label">: Your basic account informations</div>
                            </div>

                            <label for="email">Email:</label>
                            <input type="email" id="email" name="email" placeholder="name@domain.tld" value="<?php echo $this->pages->getInfo('email') ;?>"/>

                            <label for="password">Password:</label>
                            <input type="password" id="password" name="password" placeholder="Change your password" />

                            <label for="password">Password again:</label>
                            <input type="password" id="passwordagain" name="passwordagain" placeholder="Type your password again" />

                            <input type="submit" id="submit" value="Save" />
                        </form>

                      <!--  <hr></hr>

                        <div class="title">
                            <h3>Invite</h3>
                            <div class="label">: Send an invite to friend</div>
                        </div>

                        <form>
                            <input type="email" id="invite" placeholder="friend@domain.tld" />
                            <input type="submit" id="submit" value="Send" />

                        </form> -->
                    </div>
                </div>
            </div>
        </section>
 <?php require_once 'footer.php'; ?>