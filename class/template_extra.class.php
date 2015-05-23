<?php
class template_extra extends common_object
{
    protected $db;
    protected $page;
    public $log;
    public function __construct($db,$page)
    {
        $this->db = $db;
        $this->page = $page;
        global $log;
        $this->log = $log;
    }
    public function available_extra_params()
    {
        return array(
            "image" => array('name' => "Image", 'id' => "image"),
            "image_thumb" => array('name' => "Image avec thumbnail", 'id' => "image_thumb"),
            "image_path" => array('name' => "Image Path", 'id' => "image_path"),
            "file" => array('name' => "Download file", "id" => "file"),
            "file_path" => array('name' => "Download file in dir", "id" => "file_path"),
            "video" => array('name' => "Vidéo", "id" => "video"),
            "video_path" => array('name' => "Vidéos contenues dans un repertoire", "id" => "video_path"),
            "external_link" => array('name' => "Lien externe", "id" => "external_link"),
            "image_section" => array('name' => "Section avec image", "id" => "image_section"),
            "col_gauche" => array('name' => "Colonne de gauche", "id" => "col_gauche"),
            "col_droite" => array('name' => "Colonne de droite", "id" => "col_droite"),
        );
    }
    public function col_droite($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table  style='border-collapse: collapse;' width=100%><tr>" ;
        $html .= "<td>Colonne de droite</td></tr>";
        $html .= "<tr><td><textarea name='col_droite' id='col_droite'> ".$val."</textarea></td></tr>";
        $html .= "<tr><td><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = " var all_tinymce = all_tinymce + ',col_droite'; ";
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function col_gauche($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table  style='border-collapse: collapse;' width=100%><tr>" ;
        $html .= "<td>Colonne de gauche</td></tr>";
        $html .= "<tr><td><textarea name='col_gauche' id='col_gauche'> ".$val."</textarea></td></tr>";
        $html .= "<tr><td><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = " var all_tinymce = all_tinymce + ',col_gauche'; ";

        return array('html' => $html, 'js_code' => $js_code);
    }
    public function image_path($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td rowspan=2><div id="fileTree_image_path" class="style_filetree"></div></td></tr>';
        $html .= "<tr><td >Image Path</td>";
        $html .= "<td><input name='image_path' id='image_path' value='".$val."'/></td></tr>";
        $html .= '<tr><td rowspan=2><div id="fileTree_thb_path" class="style_filetree"></div></td></tr>';
        $html .= "<tr><td>Répertoire des thumbnails</td><td><input name='thb_path' id='thb_path' value='".($extra && isset($extra['thb_path'])?$extra['thb_path']:"")."'/></td></tr>";
        $html .= "<tr><td>Préfixe des thumbnails</td><td colspan=2><input name='thb_prefix' id='thb_prefix' value='".($extra && isset($extra['thb_prefix'])?$extra['thb_prefix']:"")."'/></td></tr>";
        $html .= "<tr><td>Use cache</td><td colspan='2'><select name='image_path_use_cache' id='image_path_use_cache'><option value='0'>Non</option><option ".($extra && isset($extra['use_cache']) && $extra['use_cache']==1?"SELECTED":"")." value='1'>Oui</option></select></td></tr>";
        $html .= "<tr><td>Thumbnail use cache</td><td colspan='2'><select name='thb_path_use_cache' id='thb_path_use_cache'><option value='0'>Non</option><option ".($extra && isset($extra['thb_path_use_cache']) && $extra['thb_path_use_cache']==1?"SELECTED":"")." value='1'>Oui</option></select></td></tr>";
        $html .= "<tr><td colspan='3'><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code("image_path","path");
        $js_code .= $this->file_browser_code("thb_path","path");
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function image_thumb($val,$load,$extra=false)
    {
        global $conf;
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table  style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td rowspan=2><div id="fileTree_image_thumb" class="style_filetree"></div></td></tr>';
        $html .= "<tr><td>Image</td>";
        $html .= "<td><input name='image_thumb' id='image_thumb' value='".$val."'/></td>";
        $html .= "<td><div id='image_thumb_preview' style='background-position: center center; background-image: url(\"".(isset($val) && $val."x" != "x"?$conf->main_base_path."/files/".$val."\")":$conf->main_base_path."/img/No_Image.png\")" ). "; background-repeat: no-repeat; background-size: contain; height: 180px;min-width:180px; min-wheight:180px;'></div></td></tr>";
        $html .= '<tr><td rowspan=2><div id="fileTree_thumb" class="style_filetree"></div></td></tr>';
        $html .= "<tr><td>thumbnail</td><td><input name='thumb' id='thumb' value='".($extra && isset($extra['thumb'])?$extra['thumb']:"")."'/></td>";
        $html .= "<td><div id='thumb_preview' style='background-position: center center; background-image: url(\"".(isset($val) && $val."x" != "x"?$conf->main_base_path."/files/".$extra['thumb']."\")":$conf->main_base_path."/img/No_Image.png\")" ). "; background-repeat: no-repeat; background-size: contain; height: 180px;min-width:180px; min-wheight:180px;'></div></td></tr>";
        $html .= "<tr><td colspan='3'><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code_with_preview("image_thumb","file");
        $js_code .= $this->file_browser_code_with_preview("thumb","file");
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function file($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table  style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td><div id="fileTree_file" class="style_filetree"></div></td>';
        $html .= "<td colspan=2>File</td>";
        $html .= "<td><input name='file' id='file' value='".urldecode($val)."'/></td><td><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code("file","file");
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function image($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td><div id="fileTree_image" class="style_filetree"></div></td>';
        $html .= "<td colspan=2>Image</td>";
        $html .= "<td><input name='image' id='image' value='".urldecode($val)."'/></td><td><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code("image","file");
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function file_path($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table  style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td><div id="fileTree_file_path" class="style_filetree"></div></td>';
        $html .= "<td colspan=2>Répertoire</td>";
        $html .= "<td><input name='file_path' id='file_path' value='".$val."'/></td><td><button>Save</button></td></tr>";
        $html .= "<tr><td>Use cache</td><td colspan='2'><select name='file_path_use_cache' id='file_path_use_cache'><option value='0'>Non</option><option ".($extra && isset($extra['use_cache']) && $extra['use_cache']==1?"SELECTED":"")." value='1'>Oui</option></select></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code("file_path","path");

        return array('html' => $html, 'js_code' => $js_code);
    }
    public function video($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td><div id="fileTree_video" class="style_filetree"></div></td>';
        $html .= "<td colspan=2>Vidéo</td>";
        $html .= "<td><input name='video' id='video' value='".$val."'/></td><td><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code("video","file");
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function video_path($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table  style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr><td><div id="fileTree_video_path" class="style_filetree"></div></td>';
        $html .= "<td colspan=2>Répertoire conteant les vidéos</td>";
        $html .= "<td><input name='video_path' id='video_path' value='".$val."'/></td><td><button>Save</button></td></tr>";
        $html .= "<tr><td>Use cache</td><td colspan='2'><select name='video_path_use_cache' id='image_path_use_cache'><option value='0'>Non</option><option ".($extra && isset($extra['use_cache']) && $extra['use_cache']==1?"SELECTED":"")." value='1'>Oui</option></select></td></tr>";
        $html .= "</table>";
        $html .= "</form>";
        $js_code = $this->file_browser_code("video_path","path");
        return array('html' => $html, 'js_code' => $js_code);
    }
    public function external_link($val,$load,$extra=false)
    {
        $html = "<form method='post' action='".$this->page."&load=".$load."'>";
        $html .= "<table style='border-collapse: collapse;' CELLPADDING=10>" ;
        $html .= '<tr>';
        $html .= "<td colspan=2>Lien externe</td>";
        $html .= "<td><input name='external_link' id='external_link' value='".$val."'/></td><td><button>Save</button></td></tr>";
        $html .= "</table>";
        $html .= "</form>";

        return array('html' => $html, 'js_code' => false);
    }
    public function image_section($val,$load,$extra=false)
    {
        return array('html' => "Section avec images. Disponible dans la tab Sections", 'js_code' => false);
    }
    private function file_browser_code($part,$type=false)
    {
        global $conf;
        $js_code =  "jQuery(document).ready(function(){";
        $js_code .=  "    jQuery('#fileTree_".$part."').fileTree({";
        $js_code .=  "        root : '/',";
        $js_code .=  "        script : '".$conf->main_base_path."/lib/jQueryFileTree/connector.php',";
        $js_code .=  "        folderEvent : 'click',";
        $js_code .=  "        expandSpeed : 750,";
        $js_code .=  "        collapseSpeed : 750,";
        $js_code .=  "        multiFolder : false";
        $js_code .=  "     }, function(file,dir) {";
        if ($type == "path")
        {
            $js_code .=  "         jQuery('#".$part."').val(dir);";
        } else {
            $js_code .=  "         jQuery('#".$part."').val(file);";
        }
        $js_code .=  "     });";
        $js_code .=  "});";
        return $js_code;
    }
    private function file_browser_code_with_preview($part,$type=false)
    {
        global $conf;
        $js_code =  "jQuery(document).ready(function(){";
        $js_code .=  "    jQuery('#fileTree_".$part."').fileTree({";
        $js_code .=  "        root : '/',";
        $js_code .=  "        script : '".$conf->main_base_path."/lib/jQueryFileTree/connector.php',";
        $js_code .=  "        folderEvent : 'click',";
        $js_code .=  "        expandSpeed : 750,";
        $js_code .=  "        collapseSpeed : 750,";
        $js_code .=  "        multiFolder : false";
        $js_code .=  "     }, function(file,dir) {";
        if ($type == "path")
        {
            $js_code .=  "         jQuery('#".$part."').val(dir);";

        } else {
            $js_code .=  "         jQuery('#".$part."').val(file);";
        }
        $js_code .= '          if (null==file) {';
        $js_code .= "            jQuery('#".$part."_preview').css('background-image','url(\"".$conf->main_base_path."/img/No_Image.png\")');";
        $js_code .= "         }else{";
        $js_code .= "            jQuery('#".$part."_preview').css('background-image','url(\"".$conf->main_base_path."/files/"."'+file+'\")');";
        $js_code .= "         }";
        $js_code .=  "     });";
        $js_code .=  "});";
        return $js_code;
    }
}