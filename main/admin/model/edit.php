<?php
class model_edit extends admin_common
{

    public $id;
    public $loadedTab = false;
    public $type;
    public $dir;
    private $loadedTabType=array('js' => 1,"css" => 0,"page" => 2);
    public $message = false;

    public function run()
    {
        $array = array();
        $array['css']=$this->load_all_css_file();
        $array['js'] = $this->load_all_js_file();
        return $array;
    }
    private function load_all_css_file()
    {
        return $this->parse_dir("css");
    }
    private function load_all_js_file()
    {
        return $this->parse_dir("js");
    }
    private function parse_dir($dir_asked)
    {
        $html="<select id='".$dir_asked."_list' name='".$dir_asked."_list'>";
        if ($this->id && $this->id."x" != "x")
        {
            $html.= "<option value='0'>Select-></option>";
        } else {
            $html.= "<option value='0' SELECTED>Select-></option>";
        }
        foreach(array("main","customer") as $dir_root)
        {
            $html.="<optgroup id='".$dir_root."' label='".$dir_root."'>";
            if (is_dir($dir_root."/".$dir_asked))
            {
                $dir = new DirectoryIterator($dir_root."/".$dir_asked);
                foreach ($dir as $fileinfo)
                {
                    if (!$fileinfo->isDot() && !preg_match('/^\./',$fileinfo->getFilename()))
                    {
                        if ($this->type && $this->type==$dir_asked && $this->dir && $this->dir == $dir_root && $this->id && $this->id."x" != "x" && $this->id == $fileinfo->getFilename())
                        {
                            $html.= "<option SELECTED value='".$fileinfo->getFilename()."'>".$fileinfo->getFilename()."</option>";
                        } else {
                            $html.= "<option value='".$fileinfo->getFilename()."'>".$fileinfo->getFilename()."</option>";
                        }
                    }
                }
                $html.="</optgroup>";
            }
        }
        $html .= "</select>";
        return $html;
    }
    public function get_content($post)
    {
        global $conf;
        $file = $conf->main_document_root."/".$post['dir']."/".basename($post['id']);
        $c = file_get_contents($file);
        if ($c."x"=="x"){
            $c=" ";
        }
        return $c;
    }
    public function save($post)
    {
        global $conf;
        $this->set_loaded_tab($post['type']);
        $dir = $post['dir'];
        if (preg_match('/^\/main\//',$post['dir']))
        {
            $dir = preg_replace('/^\/main\//','/customer/',$dir);
        }
        if (!is_dir($conf->main_document_root."/".$dir))
        {

            mkdir($conf->main_document_root."/".$dir,0777,true);
        }
        $file = $conf->main_document_root."/".$dir."/".basename($post['id']);
        $data = 'textarea_'.$post['type'];
        $res = file_put_contents($file, $post[$data]);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $res;

    }
    public function validate($post)
    {
        global $conf;
        $ext = "js";
        if ($post['type'] == "css") $ext = "css";
        $file = $conf->main_document_root."/customer/".$post['type']."/".$post['name'];
        if (!preg_match('/\.'.$ext.'$/', $post['name'])){
            $file.= '.'.$ext;
        }
        if (is_file($file))
        {
           return (json_encode('Un fichier avec ce nom existe déjà'));
        } else {
            return (json_encode(true));
        }
    }
    public function clone_file($post)
    {
        global $conf;
        $this->set_loaded_tab($post['type']);
        if (!is_dir($conf->main_document_root."/customer/".$post['dir']))
        {
            mkdir($conf->main_document_root."/customer/".$post['dir'],0777,true);
        }
        $dest=sanatize_string($post['name']);
        $destdir = preg_replace('/^\/main\//','/customer/',$post['dir']);
        $source = $conf->main_document_root."/".$post['dir'].'/'.$post['id'];
        $dest =  $conf->main_document_root."".$destdir.'/'.$post['name'];
        $res = copy($source,$dest);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function delete_file($post)
    {
        global $conf;
        $file =  $conf->main_document_root.$post['dir']."/".basename($post['id']);
        if (preg_match('/^\/main\//',$post['dir']))
        {
            $this->message = _("Opération impossible");
            return false;
        }
        $res = unlink($file);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    public function new_file($post)
    {
        global $conf;
        $this->set_loaded_tab($post['type']);
        if (!is_dir($conf->main_document_root."/customer/".$post['type']))
        {
            mkdir($conf->main_document_root."/customer/".$post['type'],0777,true);
        }
        $ext = "js";
        if ($post['type'] == "css") { $ext = "css"; }
        $file=sanatize_string($post['name']);

        $file = $conf->main_document_root."/customer/".$post['type']."/".$file;
        if (!preg_match('/\.'.$ext.'$/', $post['name'])){
            $file.= '.'.$ext;
        }
        //var_dump($file);
        $res = touch($file);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }
    private function set_loaded_tab($type)
    {
        $this->loadedTab = $this->loadedTabType[$type];
    }
}

// @CHARSET "UTF-8";

// h5 { color: #000FEF; }

// /*custom font*/
// @import url(http://fonts.googleapis.com/css?family=Merriweather+Sans);

//         * {margin: 0; padding: 0;}

// html, body {min-height: 100%;}

// .breadcrumbs {
//     /*centering*/
//     display: inline-block;
//     box-shadow: 0 0 15px 1px rgba(0, 0, 0, 0.35);
//     overflow: hidden;
//     border-radius: 5px;
//     /*Lets add the numbers for each link using CSS counters. flag is the name of the counter. to be defined using counter-reset in the parent element of the links*/
//     counter-reset: flag;
// }

// .breadcrumbs a {
//     text-decoration: none;
//     outline: none;
//     display: block;
//     float: left;
//     font-size: 12px;
//     line-height: 36px;
//     color: white;
//     /*need more margin on the left of links to accomodate the numbers*/
//     padding: 0 10px 0 60px;
//     background: #666;
//     background: linear-gradient(#666, #333);
//             position: relative;
// }
// /*since the first link does not have a triangle before it we can reduce the left padding to make it look consistent with other links*/
// .breadcrumbs a:first-child {
//     padding-left: 46px;
//     border-radius: 5px 0 0 5px; /*to match with the parent's radius*/
// }
// .breadcrumbs a:first-child:before {
//     left: 14px;
// }
// .breadcrumbs a:last-child {
//     border-radius: 0 5px 5px 0; /*this was to prevent glitches on hover*/
//     padding-right: 20px;
// }

// /*hover/active styles*/
// .breadcrumbs a.active, .breadcrumbs a:hover{
//     background: #333;
//     background: linear-gradient(#333, #000);
// }
// .breadcrumbs a.active:after, .breadcrumbs a:hover:after {
//     background: #333;
//     background: linear-gradient(135deg, #333, #000);
// }

// /*adding the arrows for the breadcrumbss using rotated pseudo elements*/
// .breadcrumbs a:after {
//     content: '';
//     position: absolute;
//     top: 0;
//     right: -18px; /*half of square's length*/
//     /*same dimension as the line-height of .breadcrumbs a */
//     width: 36px;
//     height: 36px;
//     /*as you see the rotated square takes a larger height. which makes it tough to position it properly. So we are going to scale it down so that the diagonals become equal to the line-height of the link. We scale it to 70.7% because if square's:
//      length = 1; diagonal = (1^2 + 1^2)^0.5 = 1.414 (pythagoras theorem)
//      if diagonal required = 1; length = 1/1.414 = 0.707*/
//     transform: scale(0.707) rotate(45deg);
//     /*we need to prevent the arrows from getting buried under the next link*/
//     z-index: 1;
//     /*background same as links but the gradient will be rotated to compensate with the transform applied*/
//     background: #666;
//     background: linear-gradient(135deg, #666, #333);
//             /*stylish arrow design using box shadow*/
//             box-shadow:
//             2px -2px 0 2px rgba(0, 0, 0, 0.4),
//             3px -3px 0 2px rgba(255, 255, 255, 0.1);
//     /*
//      5px - for rounded arrows and
//     50px - to prevent hover glitches on the border created using shadows*/
//     border-radius: 0 5px 0 50px;
// }
// /*we dont need an arrow after the last link*/
// .breadcrumbs a:last-child:after {
//     content: none;
// }
// /*we will use the :before element to show numbers*/
// .breadcrumbs a:before {
//     content: counter(flag);
//     counter-increment: flag;
//     /*some styles now*/
//     border-radius: 100%;
//     width: 20px;
//     height: 20px;
//     line-height: 20px;
//     margin: 8px 0;
//     position: absolute;
//     top: 0;
//     text-align: center;
//     left: 30px;
//     background: #444;
//     background: linear-gradient(#444, #222);
//             font-weight: bold;
// }


// .flat a, .flat a:after {
//     background: white;
//     color: black;
//     transition: all 0.5s;
// }
// .flat a:before {
//     background: white;
//     box-shadow: 0 0 0 1px #ccc;
// }
// .flat a:hover, .flat a.active,
// .flat a:hover:after, .flat a.active:after{
//     background: #9EEB62;
// }
