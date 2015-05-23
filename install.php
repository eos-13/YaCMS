<?php
$html = "";
//1 Présentation
if (!isset($_REQUEST['step']))
{
    $html .=  <<<EFO
    <html>
    <head>
    </head>
    <body>
            Ce CMS est un super outil pour la création de sites web ou d'applications web
            Pour l'installer, clicker sur <form method="POST" action="install.php"><input type="hidden" name="step" value="on"/><input type="hidden" name="step2" value="on"/><button>Installer</button></form>
    </body>
    </html>
EFO;
    echo $html;
}elseif (isset($_REQUEST['step2']))
{
    //2 check dependencies
    $html .=  <<<EFO
    <html>
    <head>
    </head>
    <body>
    <table>
EFO;
    $error = 0;
    $warn = 0;
    if (!touch(".htaccess"))
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>Can't write .htaccess</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>.htaccess can be overwrite</td></tr>";
    }
    if (!touch("conf/conf.php"))
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>Can't write conf/conf.php</td></tr>";
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>conf/conf.php can be write</td></tr>";
    }
    if (!is_callable("memcache_connect"))
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>Memcache is missing. Please install php-memcache</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>Memcache is KO</td></tr>";
    }
    if (!is_callable("_"))
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>Gettext is missing. Please install php-gettext</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>Gettext is OK</td></tr>";
    }
    if (include_once 'Image/GraphViz.php')
    {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>Image/GraphViz was installed</td></tr>";
    } else {
        $html .=  "<tr><td><img SRC='img/install/warning.png'/></td><td>Image/GraphViz is missing. Please install pear Image/GraphViz package</td></tr>";
        $warn++;
    }
    if (!is_callable("mysql_connect"))
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>PHP/MySQL connector is missing. Please install php-mysql</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>PHP/MySQL connector is OK</td></tr>";
    }
    if (!is_callable("pdo_drivers"))
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>PHP PDO is missing. Please install php-pdo</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>PHP PDO is OK</td></tr>";
    }
    if (!is_callable("gd_info"))
    {
        $html .=  "<tr><td><img SRC='img/install/warning.png'/></td><td>PHP GD is missing. Please install php-gd</td></tr>";
        $warn++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>PHP GD is OK</td></tr>";
    }
    if (!is_callable("imap_create"))
    {
        $html .=  "<tr><td><img SRC='img/install/warning.png'/></td><td>PHP IMAP is missing. Please install php-imap</td></tr>";
        $warn++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>PHP GD is OK</td></tr>";
    }
    system("mysql --version >/dev/null",$return);
    if ($return != "0")
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>MySQL binary not found</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>MySQL client binary found</td></tr>";
    }
    system("php --version >/dev/null",$return);
    if ($return != "0")
    {
        $html .=  "<tr><td><img SRC='img/install/ko.png'/></td><td>PHP-cli binary not found</td></tr>";
        $error ++;
    } else {
        $html .=  "<tr><td><img SRC='img/install/ok.png'/></td><td>PHP-cli binary found</td></tr>";
    }

    $html .=  <<< EOF
    </table>

    </body>
    </html>
EOF;

    $pre = "";
    $post = "";
    if ($error == "0")
    {
        $post = '<br/><form method="POST" action="install.php"><input type="hidden" name="step" value="on"/><input type="hidden" name="step3" value="on"/><button>Next-&gt;</button></form>';
    } else {
        $pre = '<br/><div style="padding:10px; margin-top:10px; width:400px; border: 1px Solid red;"><center>';
        $pre .= '<h2 style="color: red">Please fix installation and try again</h2>';
        $pre .= '</center></div><br/>';
    }
    echo $pre.$html.$post;
} elseif (isset($_REQUEST['step3']))
{
    //3 mysql data + conf data
    echo <<<EOF
    <form method="POST" action="install.php">
            <input type="hidden" name="step" value="on"/>
            <input type="hidden" name="step4" value="on"/>
            <table CELLPADDING="10">
            <tr><td>MySQL Host</td>
                <td><input type="text" name="mysql_host" value="127.0.0.1"/></td>
            </tr>
            <tr><td>MySQL Port</td>
                <td><input type="text" name="mysql_port" value="3306"/></td>
            </tr>
            <tr><td>MySQL Admin login</td>
                <td><input type="text" name="mysql_login_admin" value="root"/>
            </tr>
            <tr><td>MySQL Admin pass</td>
                <td><input type="password" name="mysql_pass_admin" value=""/>
            </tr>
            <tr><td>MySQL Login for the app</td>
                <td><input type="text" name="mysql_login_app" value=""/>
            </tr>
            <tr><td>MySQL pass for the app</td>
                <td><input type="text" name="mysql_pass_app" value=""/>
            </tr>
            <tr><td>MySQL Database name</td>
                <td><input type="text" name="mysql_database_name" value=""/>
            </tr>
            <tr><td>Full URL</td>
                <td><input type="text" name="main_url_root" value="/"/>
            </tr>
            <tr><td>Base PATH</td>
                <td><input type="text" name="main_base_path" value="/"/>
            </tr>
            <tr><td>System PATH</td>
EOF;
           echo '<td><input type="text" name="main_document_root" value="'.__DIR__.'"/>';
echo <<<EOF
            </tr>
            <tr><td>Admin URL PATH</td>
                <td><input type="text" name="admin_keyword" value="admin"/>
            </tr>
            </table>
       <button>Next-&gt;</button>
    </form>
EOF;
} elseif (isset($_REQUEST['step4']))
{
    $need_create_user = false;
    $need_create_base = false;
    if (isset($_REQUEST['mysql_host'])
        && isset($_REQUEST['mysql_port'])
        && isset($_REQUEST['mysql_database_name'])
    )
    {
        $res = mysql_connect($_REQUEST['mysql_host'].":".$_REQUEST['mysql_port'], $_REQUEST['mysql_login_admin'],$_REQUEST['mysql_pass_admin']);
        if ($res)
        {
            $res1 = mysql_connect($_REQUEST['mysql_host'].":".$_REQUEST['mysql_port'], $_REQUEST['mysql_login_app'],$_REQUEST['mysql_pass_app']);
            if (!$res1)
            {
                $need_create_user=true;
            }
            $res3=mysql_select_db($_REQUEST['mysql_database_name']);
            if (!$res3) $need_create_base=true;

            //Install DB
            if ($need_create_base)
            {
                $requete = "CREATE DATABASE ".$_REQUEST['mysql_database_name']." DEFAULT CHARACTER SET utf8 DEFAULT COLLATE utf8_general_ci;";
                $sql = mysql_query($requete,$res);
                if (!$sql)
                {
                    header('location:install.php?step=on&step3=on&message=MySQL%20Admin%20as%20insuficient%20rights');
                    exit;
                }
            }
            $commande = "cat sql/install.sql | mysql -u ".$_REQUEST['mysql_login_admin'].($_REQUEST['mysql_pass_admin']."x" =="x"?" ":" -p".$_REQUEST['mysql_pass_admin']." ".$_REQUEST['mysql_database_name']);
            `$commande`;
            if ($need_create_user)
            {
                $requete="GRANT ALL PRIVILEGES ON ".$_REQUEST['mysql_database_name'].".* TO ".addslashes($_REQUEST['mysql_login_app'])."@localhost identified by '".addslashes($_REQUEST['mysql_pass_app'])."'";
                $sql = mysql_query($requete,$res);
                if ($sql)
                {
                    $requete = "FLUSH PRIVILEGES";
                    $sql = mysql_query($requete,$res);
                }
            }
            //Gen conf file
            $content = "<?php\n";
            $content .= '$main_url_root="'.$_REQUEST['main_url_root'].'";'."\n";
            $content .= '$main_base_path="'.$_REQUEST['main_base_path'].'";'."\n";
            $content .= '$main_document_root="'.$_REQUEST['main_document_root'].'";'."\n";
            $content .= '$main_db_host="'.$_REQUEST['mysql_host'].'";'."\n";
            $content .= '$main_db_port="'.$_REQUEST['mysql_port'].'";'."\n";
            $content .= '$main_db_name="'.$_REQUEST['mysql_database_name'].'";'."\n";
            $content .= '$main_db_user="'.$_REQUEST['mysql_login_app'].'";'."\n";
            $content .= '$main_db_pass="'.$_REQUEST['mysql_pass_app'].'";'."\n";
            $content .= '$main_db_type="mysqli";'."\n";
            $content .= '$main_db_character_set="utf8";'."\n";
            $content .= '$character_set_client="utf8";'."\n";
            $content .= '$main_db_collation="utf8_general_ci";'."\n";
            $content .= '$admin_keyword="'.$_REQUEST['admin_keyword'].'";'."\n";
            $content .= '?>';
            $result = file_put_contents("conf/conf.php", $content);
            if (!$result)
            {
                header('location:install.php?step=on&step3=on&message=conf/conf.php%20generation%20failed');
            }
            $result = file_get_contents("install.htaccess");
            if (!$result)
            {
                header('location:install.php?step=on&step3=on&message=install.htaccess%20file%20not%20found');
                exit;
            } else {
                $htaccess = preg_replace('/==MAINBASEPATH==/',$_REQUEST['main_base_path'],$result);
                $result = file_put_contents('.htaccess', $htaccess);
                if (!$result)
                {
                    header('location:install.php?step=on&step3=on&message=.htaccess%20file%20is%20not%20writtable');
                    exit;
                }
            }
            $commande = "cd lib/SphinxQL-Query-Builder-master/; php composer.phar install";
            `$commande`;
            $commande = "cd lib/indenter/; php composer.phar install";
            `$commande`;
            if (is_file('.install'))
            {
                $result = unlink(".install");
                if (!$result) header('location:install.php?step=on&step3=on&message=.install%20file%20cannot%20be%remove.%20Remove%20it%20manually');
            }
            echo "<html><head></head><body>The site is now accessible<br/><a href='home'>Go</a></body></html>";
        } else {
            header('location:install.php?step=on&step3=on&message=MySQL%20Admin%20Credential%20are%20KO');
            exit;
        }
    } else {
        header('location:install.php?step=on&step3=on&message=No%20%data%20provided');
        exit;
    }
}

