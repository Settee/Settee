<?php
/* DEFAULT VALUE */
date_default_timezone_set('Europe/Paris');
$date = new DateTime();
$error = '';

function settee_test_form($data){
    if(isset($_POST[$data]) && !empty($_POST[$data])){
        $_POST[$data] = trim($_POST[$data]);
        return true;
    }else{
        return false;
    }
}

function schemadb(){
    if(settee_test_form('host_prefix')){
        $prefixdb = $_POST['host_prefix']."_";
    }else{
        $prefixdb = "";
    }
    return "

CREATE TABLE IF NOT EXISTS `".$prefixdb."categorie` (
  `id` INT(11) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(300) NOT NULL DEFAULT '',
  `url` VARCHAR(100) NOT NULL DEFAULT '',
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `".$prefixdb."users` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `name` VARCHAR(100) NOT NULL DEFAULT '',
  `surname` VARCHAR(100) NOT NULL DEFAULT '',
  `password` VARCHAR(100) NOT NULL DEFAULT '',
  `email` VARCHAR(100) NOT NULL DEFAULT '',
  `avatar` VARCHAR(255) NULL DEFAULT '',
  `type` VARCHAR(45) NULL DEFAULT NULL,
  PRIMARY KEY (`id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

CREATE TABLE IF NOT EXISTS `".$prefixdb."posts` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `post` LONGTEXT NOT NULL,
  `author_id` BIGINT(20) NOT NULL,
  `categorie_id` INT(11) UNSIGNED NOT NULL,
  `image` VARCHAR(300) NULL,
  PRIMARY KEY (`id`, `author_id`, `categorie_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_".$prefixdb."posts_".$prefixdb."categorie1_idx` ON `".$prefixdb."posts` (`categorie_id` ASC);
CREATE INDEX `fk_".$prefixdb."posts_".$prefixdb."user1_idx` ON `".$prefixdb."posts` (`author_id` ASC);

CREATE TABLE IF NOT EXISTS `".$prefixdb."comments` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `date` DATETIME NOT NULL,
  `post` LONGTEXT NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  `post_id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`, `user_id`, `post_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_".$prefixdb."comments_".$prefixdb."user1_idx` ON `".$prefixdb."comments` (`user_id` ASC);
CREATE INDEX `fk_".$prefixdb."comments_".$prefixdb."post1_idx` ON `".$prefixdb."comments` (`post_id` ASC);


CREATE TABLE IF NOT EXISTS `".$prefixdb."likes` (
  `id` BIGINT(20) NOT NULL AUTO_INCREMENT,
  `post_id` BIGINT(20) NOT NULL,
  `user_id` BIGINT(20) NOT NULL,
  PRIMARY KEY (`id`, `post_id`, `user_id`))
ENGINE = MyISAM
DEFAULT CHARACTER SET = utf8;

CREATE INDEX `fk_".$prefixdb."likes_".$prefixdb."post_idx` ON `".$prefixdb."likes` (`post_id` ASC);

CREATE INDEX `fk_".$prefixdb."likes_".$prefixdb."user1_idx` ON `".$prefixdb."likes` (`user_id` ASC);";
}

if(settee_test_form('website')){
    /* ROOT USER */
    if(settee_test_form('username') && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['username'])){
        if(settee_test_form('passwd') && settee_test_form('passwdconfirm') && $_POST['passwd'] == $_POST['passwdconfirm'] && strlen($_POST['passwd']) >= '7' && preg_match('/^[a-zA-Z0-9\@_-]{6,}$/', $_POST['passwd'])){
            if(settee_test_form('email') && filter_var($_POST['email'], FILTER_VALIDATE_EMAIL)){
                /* DATABASE */
                if(settee_test_form('host')){
                    if(settee_test_form('host_user')){
                        if(settee_test_form('host_passwd')){
                            if(settee_test_form('host_db')){
                                if(settee_test_form('accesslevel')){
                                    /* KEY GENERATOR */
                                    if(settee_test_form('host_prefix')){$prefix = $_POST['host_prefix']."_";}else{$prefix = "";}
                                    $key = sha1($date->getTimestamp()*(rand(10,568742)+strlen($_POST['host_db'])));
                                    $_POST['passwd'] = crypt($_POST['passwd'] . $key);

                                    /* CONFIG FILE */
                                    $conf = '<?php Class Config extends Dispatcher{/* If you don\'t know PHP and this CMS DON\'T TOUCH THIS FILE PLEASE */ const WEBSITE = "'.$_POST['website'].'";const HOST = "'.$_POST['host'].'";const USER = "'.$_POST['host_user'].'";const PASSWD = "'.$_POST['host_passwd'].'";const DB = "'.$_POST['host_db'].'";const PREFIX = "'.$_POST['host_prefix'].'";const KEY = "'.$key.'";const PRIVACY = "'.$_POST['accesslevel'].'";}?>';
                                    $file = fopen('../config.php', 'w+');
                                    $ligne = fputs($file,$conf);
                                    fclose($file);
                                    chmod("../config.php",0700);

                                    /* DATABASE */
                                    try{
                                        $pdo = new PDO('mysql:host='.$_POST['host'].';dbname='.$_POST['host_db'],$_POST['host_user'],$_POST['host_passwd'],array(PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8'));
                                        $pdo->query(schemadb());
                                        $pdo->query('INSERT INTO '.$prefix.'users (name,surname,password,email,type) VALUES("'.$_POST['username'].'","'.$_POST['username'].'","'.$_POST['passwd'].'","'.$_POST['email'].'","root")');
                                    }catch(PDOException $e){
                                        $error = "Problem of communication with database";
                                    }

                                    /* END OF INSTALL */
                                    $site = '<!DOCTYPE html><html lang="fr"><head><title>Settee » Installation</title><meta charset="utf-8"><link rel="stylesheet" href="reset.css" /><link rel="stylesheet" href="design.css" /><meta name="viewport" content="width=device-width, initial-scape=1" /></head><body><header><div class="wrap"><div id="title"><h1>Installation</h1></div><div id="preamble"><h2>Welcome to <b>Settee</b></h2><p>This installation script has already been launched !</p><p><a href="/">Go back</a></p></div></div></header></body></html>';
                                    $file = fopen('index.php', 'w+');
                                    $ligne = fputs($file,$site);
                                    fclose($file);
                                }else{
                                    $error = "Oups, Access Level is wrong";
                                }
                            }else{
                                $error = "Oups, Host Database is wrong";
                            }
                        }else{
                            $error = "Oups, Host Password is wrong";
                        }
                    }else{
                        $error = "Oups, Host Username is wrong";
                    }
                }else{
                    $error = "Oups, Host is wrong";
                }
            }else{
                $error = "Oups, Email is wrong";
            }
        }else{
            $error = "Oups, Passwords are not the same or have less than seven characters";
        }
    }else{
        $error = "Oups, Username is wrong";
    }
}else{
    if(isset($_POST['install'])){
        $error = "Oups, Website's name is wrong";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <title>Settee » Installation</title>
    <meta charset="utf-8">
    <link rel="stylesheet" href="reset.css" />
    <link rel="stylesheet" href="design.css" />
    <meta name="viewport" content="width=device-width, initial-scape=1" />
</head>

<body>
    <header>
        <div class="wrap">
            <div id="title">
                <h1>Installation</h1>
            </div>
            <div id="preamble">
                <h2>Welcome to <b>Settee</b></h2>
                <p>Well, now that you have uploaded the installation files on your web hosting, please fill the form below to finalize your installation. Any doubt? You can check the documentation that will surely help you.</p>
                <p>Please provide the following information. Don't worry, you can change them later.</p>
                <?php if(isset($error) && !empty($error)): ?>
                    <div class="m-error">
                        <span><?php echo $error; ?></span>
                        <div class=".clearfloat"></div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </header>
    <form method="post" action="index.php">
        <section>
            <div class="wrap">
                <h3>Site configuration</h3>

                <label for="sitetitle">Site title</label>
                <input type="text" id="sitetitle" name="website" required="required"/>

                <label for="username">Username</label>
                <input type="text" id="username" name="username" required="required"/>
                <p class="help">Usernames can have only alphanumeric characters (8 min), spaces, underscores, hyphens, periods and the @ symbol.</p>

                <label for="password1">Password, twice</label>
                <input type="password" id="password1" name="passwd" required="required" />
                <input type="password" id="password2" name="passwdconfirm" required="required" />
                <p class="help">The password should be at least seven characters long. To make it stronger, use upper and lower case letters, numbers, and symbols like !</p>

                <label for="email">Email</label>
                <input type="email" id="email" name="email" required="required"/>
                <p class="help">Double-check your email address before continuing.</p>
            </div>
        </section>
        <section>
            <div class="wrap">
                <h3>Database configuration</h3>

                <label for="dbhost">Host</label>
                <input type="text" id="dbhost" name="host" required="required"/>

                <label for="dbusername">Username</label>
                <input type="text" id="dbusername" name="host_user" required="required"/>

                <label for="dbpassword">Password</label>
                <input type="text" id="dbpassword" name="host_passwd" required="required"/>

                <label for="dbname">Name</label>
                <input type="text" id="dbname" name="host_db" required="required"/>

                <label for="dbprefix">Prefix</label>
                <input type="text" id="dbprefix" name="host_prefix"/>
                <p class="help">Prefix without underscore. (Ex: st)</p>
            </div>
        </section>
        <section>
            <div class="wrap">
                <h3>Access level</h3>

                <div id="accesslevel">
                    <input type="radio" name="accesslevel" id="public" value="public" required="required">Public access
                    <br/>
                    <p class="help">N'importe qui peut accéders aux publications et aux commentaires, compte requis pour poster et commenter.</p>

                    <input type="radio" name="accesslevel" id="publicregistration" value="publicregistration" required="required">Public access with registration
                    <br/>
                    <p class="help">L'accès aux publications et aux commentaires necessite la création d'un compte.</p>

                    <input type="radio" name="accesslevel" id="privateadminvalitation" value="privateadminvalitation" required="required">Private with admin validation
                    <br/>
                    <p class="help">L'accès aux publications et aux commentaires necessiste la création d'un compte qui ne peut se faire que par la validation d'inscription par l'administrateur.</p>

                    <input type="radio" name="accesslevel" id="privatememberinvite" value="privatememberinvite" required="required">Private with a member invitation
                    <br/>
                    <p class="help">L'accès aux publications et aux commentaires necessiste la création d'un compte qui ne peut se faire que par l'invitation d'un membre.</p>

                    <input type="radio" name="accesslevel" id="privateadmininvite" value="privateadmininvite" required="required">Private with a admin invitation
                    <br/>
                    <p class="help">L'accès aux publications et aux commentaires necessiste la création d'un compte qui ne peut se faire que par l'invitation de l'administrateur.</p>
                </div>
            </div>
        </section>
        <section>
            <div class="wrap">
                <div class="submit">
                    <input type="submit" name="install" value="Finalize installation" />
                </div>
            </div>
        </section>
    </form>
</body>
</html>