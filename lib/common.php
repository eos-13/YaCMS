<?php
function make_path($path,$file,$ext,$no_force_no_admin=true,$force_full_path=false)
{
    global $conf;
    $file_rt=false;
    $found = false;
    if (preg_match('/\.'.$ext.'$/',$file))
    {
        $file = preg_replace('/\.'.$ext.'$/','',$file);
    }
    if ($conf->admin_mode && $no_force_no_admin)
    {
        //var_dump('main/admin/'.$path.'/'.$file.'.'.$ext);
        if (is_file('customer/admin/'.$path.'/'.$file.'.'.$ext) )
        {
            $file_rt='customer/admin/'.$path.'/'.$file.'.'.$ext;
            $found=true;
        }elseif (is_file('main/admin/'.$path.'/'.$file.'.'.$ext) )
        {
            $file_rt='main/admin/'.$path.'/'.$file.'.'.$ext;
            $found=true;
        }
        if ($force_full_path && $found)
        {
            global $main_url_root;
            $file_rt = $main_url_root . $file_rt;
        }
        return $file_rt;
    }
    if (is_file('customer/'.$path.'/'.$file.'.'.$ext))
    {
        $file_rt= 'customer/'.$path.'/'.$file.'.'.$ext;
        $found=true;
    } else if (is_file('main/'.$path.'/'.$file.'.'.$ext)){
        $file_rt= 'main/'.$path.'/'.$file.'.'.$ext;
        $found=true;
    }
    if ($force_full_path && $found)
    {
        global $main_url_root;
        $file_rt = $main_url_root . $file_rt;
    }
    return $file_rt;
}
function make_path_plugin($plugin,$path,$file,$ext,$no_force_no_admin=true,$force_full_path=false)
{
    global $conf;
    $file_rt=false;
    $found = false;
    if (preg_match('/\.'.$ext.'$/',$file))
    {
        $file = preg_replace('/\.'.$ext.'$/','',$file);
    }
    if ($conf->admin_mode && $no_force_no_admin)
    {
        if (is_file('plugins/template/'.$plugin.'/admin/'.$path.'/'.$file.'.'.$ext) )
        {
            $file_rt='plugins/template/'.$plugin.'/admin/'.$path.'/'.$file.'.'.$ext;
            $found=true;
        } elseif (is_file('customer/admin/'.$path.'/'.$file.'.'.$ext) )
        {
            $file_rt='customer/admin/'.$path.'/'.$file.'.'.$ext;
            $found=true;
        }elseif (is_file('main/admin/'.$path.'/'.$file.'.'.$ext) )
        {
            $file_rt='main/admin/'.$path.'/'.$file.'.'.$ext;
            $found=true;
        }
        if ($force_full_path && $found)
        {
            global $main_url_root;
            $file_rt = $main_url_root . $file_rt;
        }
        return $file_rt;
    }

    if (is_file('plugins/template/'.$plugin.'/'.$path.'/'.$file.'.'.$ext))
    {
        $file_rt= 'plugins/template/'.$plugin.'/'.$path.'/'.$file.'.'.$ext;
        $found=true;
    }elseif (is_file('customer/'.$path.'/'.$file.'.'.$ext)){
        $file_rt= 'customer/'.$path.'/'.$file.'.'.$ext;
        $found=true;
    } else if (is_file('main/'.$path.'/'.$file.'.'.$ext)){
        $file_rt= 'main/'.$path.'/'.$file.'.'.$ext;
        $found=true;
    }
    if ($force_full_path && $found)
    {
        global $main_url_root;
        $file_rt = $main_url_root . $file_rt;
    }
    return $file_rt;
}


function request_uri() {
    if (isset($_SERVER['REQUEST_URI'])) {
        $uri = $_SERVER['REQUEST_URI'];
    }
    else {
        if (isset($_SERVER['argv'])) {
            $uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['argv'][0];
        }
        elseif (isset($_SERVER['QUERY_STRING'])) {
            $uri = $_SERVER['SCRIPT_NAME'] .'?'. $_SERVER['QUERY_STRING'];
        }
        else {
            $uri = $_SERVER['SCRIPT_NAME'];
        }
    }

    $uri = '/'. ltrim($uri, '/');

    return $uri;
}
function curPageURL_filter_get() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    $uri = $_SERVER["REQUEST_URI"];
    $uri=preg_replace('/\?.*$/','',$uri);
    if ($_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$uri;
    } else {
        $pageURL .= $_SERVER["SERVER_NAME"].$uri;
    }
    return $pageURL;
}
function curPageURL() {
    $pageURL = 'http';
    if (isset($_SERVER["HTTPS"]) && $_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
    $pageURL .= "://";
    if (isset($_SERVER["SERVER_PORT"])  && isset($_SERVER["SERVER_NAME"]) && isset($_SERVER["REQUEST_URI"]) && $_SERVER["SERVER_PORT"] != "80")
    {
        $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
    } else if (isset($_SERVER["SERVER_NAME"]) && isset($_SERVER["REQUEST_URI"])) {
        $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
    }
    return $pageURL;
}
function load_alternative_class($classfile)
{
    if (is_file("customer_".$classfile)) require_once("customer_".$classfile);
    else require_once($classfile);
}
function load_alternative_api_class($classfile)
{
    if (is_file("api/customer_".$classfile)) require_once("api/customer_".$classfile);
    else require_once("api/".$classfile);
}

function dispatch($bdd,$path,$search_mode=false)
{
    //En BDD
    load_alternative_class('class/page.class.php');
    $page = new page($bdd);
    $res = $page->fetch_by_url($path);
    if ($res && ! $page->get_page_on_disk() )
    {
        if ($search_mode)
        {
            $page->get_all();
            $file = make_path('view','bdd','php');
            include_once($file);
            $obj = new view_bdd($bdd,$path,$page,true,$search_mode);
        } else {

            $granted = $page->check_group_publication(false,$search_mode);
            if ($granted)
            {
                $granted = $page->check_status_publication(false,$search_mode);
            }
            if ($res && ($page->get_active() == 1) && ($page->get_page_on_disk() != 1 || $path == 'home' ) && $granted)
            {
                $page->get_all();
                $file = make_path('view','bdd','php');
                include_once($file);

                $obj = new view_bdd($bdd,$path,$page,true,$search_mode);
            } else {
                $res = false;
            }
        }
    }
    if (!$res || $page->get_page_on_disk())
    {
        //Controler
        $file=make_path('controller', $path, 'php');
        if ($file)
        {
            include_once($file);
            if (class_exists("controller_".$path))
            {
                //On appelle le model
                $class = "controller_".$path;
                $obj = new $class($bdd,$path,false,true,$search_mode);

            } else {
                //Pas de class ? Error 500?
                include_once 'errors/500.php';
                exit;
            }
        } else {
            //On appelle la view
            $file_view=make_path('view', $path, 'php');
            if ($file_view)
            {
                include_once $file_view;
                if (class_exists("view_".$path))
                {
                    //On appelle le model
                    $class = "view_".$path;
                    $obj = new $class($bdd,$path,false,true,$search_mode);
                } else {
                    //Pas de class ? Error 500?
                    include_once 'errors/500.php';
                    exit;
                }
            } else {
               include_once 'errors/404.php';
                exit;
            }
        }
    }
}
function dispatch_old($bdd,$path,$search_mode=false)
{
    $file=make_path('controller', $path, 'php');
    if ($file)
    {
        include_once($file);
        if (class_exists("controller_".$path))
        {
            //On appelle le model
            $class = "controller_".$path;
            $obj = new $class($bdd,$path,false,true,$search_mode);

        } else {
            //Pas de class ? Error 500?
            include_once 'errors/500.php';
            exit;
        }
    } else {
        //On appelle la view
        $file_view=make_path('view', $path, 'php');
        if ($file_view)
        {
            include_once $file_view;
            if (class_exists("view_".$path))
            {
                //On appelle le model
                $class = "view_".$path;
                $obj = new $class($bdd,$path,false,true,$search_mode);
            } else {
                //Pas de class ? Error 500?
                include_once 'errors/500.php';
                exit;
            }
        } else {
            // On a la page dans la BDD
            load_alternative_class('class/page.class.php');
            $page = new page($bdd);
            $res = $page->fetch_by_url($path);
            $granted = $page->check_group_publication(false,$search_mode);
            if ($granted)
            {
                $granted = $page->check_status_publication(false,$search_mode);
            }

            if ($res && $page->get_active() == 1 && $page->get_page_on_disk() != 1 && $granted)
            {
                $page->get_all();
                $file = make_path('view','bdd','php');
                include_once($file);
                $obj = new view_bdd($bdd,$path,$page,true,$search_mode);
            } else {
                // sinon
                include_once 'errors/404.php';
                exit;
            }
        }
    }
}
function convert_time_us_to_ts($date_to_convert)
{
    $date = DateTime::createFromFormat('Y-m-d H:i:s', $date_to_convert);
    return $date->format('U');
}
function convert_time_us_to_fr($date_to_convert)
{
    if ($date_to_convert)
    try{
        $date = DateTime::createFromFormat('Y-m-d H:i:s', $date_to_convert);
        return $date->format('d/m/Y H:i:s');
    } catch (Exception $e)
    {
    return $date_to_convert;
    }
    else return false;
}
function sanatize_string($str)
{
    return strtolower(preg_replace("/[^a-z0-9_\-\.]+/i", "_", $str));
}
function convert_size($num)
{
    if ($num > 1024*1024*1024*1024)
    {
        $num = round($num/(1024*1024*1024*1024),2,$num);
        $num.= "To";
    } elseif($num > 1024*1024*1024)
    {
        $num = round($num/(1024*1024*1024),2,$num);
        $num.= "Go";
    } elseif($num > 1024*1024)
    {
        $num = round($num/(1024*1024),2,$num);
        $num.= "Mo";
    } elseif($num > 1024)
    {
        $num = round($num/(1024),2,$num);
        $num.= "Ko";
    } else {
        $num .= "o";
    }

    return $num;
}