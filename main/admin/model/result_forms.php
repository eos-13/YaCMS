<?php
class model_result_forms extends admin_common
{
    public $id;
    public $responce;
    public $message = false;
    public $forms_list;
    public $stats;

    public function run()
    {
        $this->forms_list = $this->make_list_forms();
        if ($this->id > 0)
            $this->stats = $this->make_stats();

        return (true);
    }
    public function del($post)
    {
        $requete = "DELETE FROM forms_result
                          WHERE forms_refid = ".$post['id'];
        $sql = $this->db->query($requete);
        if ($sql) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
        return $sql;
    }
    private function make_stats()
    {
        $requete = "SELECT *
                      FROM forms_result
                     WHERE forms_refid = ".$this->id;
        $sql = $this->db->query($requete);
        $count_tot = 0;
        $count_by_date=array();
        $last_submit=false;
        $first_submit = false;
        while($res = $this->db->fetch_object($sql))
        {
            $count_tot ++;
            $d = new DateTime($res->tms);
            if (isset($count_by_date[$d->format('Y-m-d')]))
            {
                $count_by_date[$d->format('Y-m-d')] ++ ;
            } else {
                $count_by_date[$d->format('Y-m-d')] = 1;
            }
            $d1 = $d->format('U');
            if (!$last_submit) $last_submit = $d1;
            if (!$first_submit) $first_submit = $d1;
            if ($d1 > $last_submit) $last_submit = $d1;
            if ($d1 < $first_submit) $first_submit = $d1;
        }
        $last_submit = date('d/m/Y H:i:s',$last_submit);
        $first_submit = date('d/m/Y H:i:s',$first_submit);
        return array(
                'total' => $count_tot,
                'by_date' => $count_by_date,
                'last' => $last_submit,
                'first' => $first_submit
                );
    }
    public function list_results_jqGrid($post)
    {
        return $this->jqGrid($post);
    }
    public function make_list_forms()
    {
        $requete = "SELECT DISTINCT forms_refid as id
                      FROM forms_result ";
        $sql = $this->db->query($requete);
        $a = array();
        while ($res = $this->db->fetch_object($sql))
        {
            $a[]=$res->id;
        }
        load_alternative_class('class/form.class.php');
        $b = array();
        foreach($a as $key=>$val)
        {
            $f = new form($this->db);
            $f->fetch($val);
            $b[]=array(
                    'id' => $f->get_id(),
                    'title' => $f->get_title()
            );
        }
        return $b;
    }
    public function make_jqgrid($post)
    {
        $a = $this->prepare_data($post);
        $colArray = $a['col'];
        //générer le code js
        $ret = <<<EOF
        jQuery(document).ready(function(){
            jQuery('#jqGrid').jqGrid({
EOF;
        $ret .= "        url: 'result_forms?action=list&id=".$post['id']."',\n";
        $ret .= <<<EOF
                mtype: 'POST',
                datatype: 'json',
                colModel:
                [
                    {   label: i18n.translate('id').fetch(),
                        name: 'id',
                        key: true,
                        hidden: true,
                        width: 75,
                    },
EOF;
        foreach($colArray as $key=>$val)
        {
            if ($val == _('Date soumission'))
            {
                $ret .= "{\n   label: '".$val."',\n";
                $ret .= "    name: '".$val."',\n";
                $ret .= "    width: 150,\n";
                $ret .= "    align:'center',\n";
                $ret .= "    sorttype:'date',\n";
                $ret .= "    formatter:'date', \n";
                $ret .= "    formatoptions: { \n";
                $ret .= "        srcformat:'Y-m-d h:i:s',\n";
                $ret .= "        newformat:'d/m/Y h:i' \n";
                $ret .= "    }, \n";
                $ret .= "    searchoptions:{";
                $ret .= "        dataInit: function (elem) {\n";
                $ret .= "             jQuery(elem).datepicker();\n";
                $ret .= "        }\n";
                $ret .= "    }\n";
                $ret .= "},\n";
            } else {
                $ret .= "{\n   label: '".$val."',\n";
                $ret .= "    name: '".$val."',\n";
                $ret .= "    width: 150,\n";
                $ret .= "    align:'center',\n";
                $ret .= "},\n";
            }
        }
        $ret .= <<<EOF
                ],
                viewrecords: true,
                height: 550,
                rowNum: 20,
                page:1,
                rownumbers: true,
                hoverrows: true,
                altRows: true,
                loadonce: true,
                caption: i18n.translate('User').fetch(),
                pager: '#jqGridPager',
            });
            jQuery('#jqGrid').navGrid('#jqGridPager',
            {
                edit: false,
                add: false,
                del: false,
                search: true,
                refresh: true,
                view: true,
                position: 'left',
                cloneToTop: false,
            });

        });
EOF;
        return $ret;
    }
    private function prepare_data($post)
    {
        load_alternative_class('class/form.class.php');
        $f = new form($this->db);
        $f->fetch($post['id']);
        $j = json_decode($f->get_jsonData());
        $trans = array();
        foreach($j as $key=>$val){
            $title = $val->title;
            if (isset($val->fields->id->value))
            {
                $key = $val->fields->id->value;
                $trans[$key]=$title;
            }
        }
        $requete = "SELECT *
                      FROM forms_result
                     WHERE forms_refid = ".$post['id'];
        $sql = $this->db->query($requete);
        $iter = 0;
        $all_result = array();
        while ($res = $this->db->fetch_object($sql))
        {
            $a[_('Date soumission')]=$res->tms;
            if (isset($res->user_refid) && $res->user_refid>0)
            {
                $u = new user($this->db);
                $u->fetch($res->user_refid);
                $a[_('Username')] = $u->get_name();
                $a[_('Firstname')] = $u->get_firstname();
                $a[_('Email')] = $u->get_email();
            } else {
                $a[_('Username')] = _("Anonyme");
                $a[_('Firstname')] = "";
                $a[_('Email')] = "";
            }
            $json_result = json_decode($res->result,true);
            $json_result_reformatted = array();
            foreach($json_result as $key=>$val)
            {
                if (isset($trans[$key])) $key = $trans[$key];
                $json_result_reformatted[$key]=$val;
            }
            $a = array_merge($a,$json_result_reformatted);
            $all_result[] = $a;
        }

        foreach($all_result as $key=>$val)
        {
            foreach($val as $key1=>$val1)
            {
                $allCol[]=$key1;
            }
        }
        $allCol = array_unique($allCol);
        $arr = array();
        foreach($all_result as $key1=>$val1)
        {
            foreach($allCol as $key=>$val)
            {
                $arr[$key1][$val]=isset($val1[$val])?$val1[$val]:"";
            }
        }
        return array('col' => $allCol , 'data' => $arr);
    }
    public function make_false_form()
    {
    }
    public function make_excel($post)
    {
        $a = $this->prepare_data($post);
        $arr = $a['data'];
        $allCol = $a['col'];

        require_once('lib/PHPExcel.php');
        $objPHPExcel = new PHPExcel();

        global $current_user,$conf;
        $objPHPExcel->getProperties()->setCreator($current_user->get_firstname()." ".$current_user->get_name())
        ->setLastModifiedBy($current_user->get_firstname()." ".$current_user->get_name())
        ->setTitle(_("Resultats du formulaire"))
        ->setSubject(_("Resultats du formulaire"))
        ->setDescription(_("Resultats du formulaire du site ".$conf->site_name))
        ->setKeywords(_("Formulaire")." ".$conf->site_name)
        ->setCategory(_("Formulaire"));

        $s = $objPHPExcel->setActiveSheetIndex(0);
        $col = 1;
        $styleArrayCol = array(
                'borders' => array(
                        'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '00000000'),
                        ),
                ),
                'font' => array(
                        'bold' => true,
                        'size' => 14
                ),
                'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
        );
        $maxcol = 0;
        foreach($allCol as $key=>$val)
        {
            $s->setCellValueByColumnAndRow($col,10,$val);
            $s->getStyleByColumnAndRow($col,10)->applyFromArray($styleArrayCol);
            $col++;
            $maxcol++;
        }
        $styleArrayRow = array(
                'borders' => array(
                        'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '00000000'),
                        ),
                ),
                'font' => array(
                        'bold' => false,
                        'size' => 11
                ),
                'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
        );
        $row = 11;
        foreach($arr as $key=>$val)
        {
            $col = 1;
            foreach ($val as $key1=>$val1)
            {
                if ($key1 == "Date soumission")
                {
                    $timestamp = strtotime($val1);
                    $s->setCellValueByColumnAndRow($col,$row,PHPExcel_Shared_Date::PHPToExcel($timestamp));
                    $s->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArrayRow);
                    $s->getStyleByColumnAndRow($col,$row)->getNumberFormat()->setFormatCode('dd/mm/yyyy hh:mm:ss');
                } else {
                    $s->setCellValueByColumnAndRow($col,$row,$val1);
                    $s->getStyleByColumnAndRow($col,$row)->applyFromArray($styleArrayRow);
                }
                $col++;
            }
            $row++;
        }
        $objPHPExcel->getActiveSheet()->setTitle(_('Résultats'));

        $tmp = tempnam ( "/tmp/" , "cms-" );

        //Add title
        load_alternative_class('class/form.class.php');
        $f = new form($this->db);
        $f->fetch($post['id']);
        $title = $f->get_title();
        $styleArray = array(
                'borders' => array(
                        'outline' => array(
                                'style' => PHPExcel_Style_Border::BORDER_THIN,
                                'color' => array('argb' => '00000000'),
                        ),
                ),
                'font' => array(
                        'bold' => true,
                        'size' => 24
                ),
                'alignment' => array(
                        'horizontal' => PHPExcel_Style_Alignment::HORIZONTAL_CENTER,
                        'vertical' => PHPExcel_Style_Alignment::VERTICAL_CENTER,
                ),
        );
        $s->setCellValue("B6",_("Résultats pour le formulaire")." ".$title);
        $objPHPExcel->getActiveSheet()->mergeCells('B6:K8');
        $s->getStyle('B6:K8')->applyFromArray($styleArray);


        //Add logo
        global $conf;
        if (is_file($conf->main_document_root."/". preg_replace('/^\//','',$conf->logo_xlsx)) || is_file($conf->main_document_root."/".preg_replace('/^\//','',$conf->logo)))
        {
            $objDrawing = new PHPExcel_Worksheet_Drawing();
            $objDrawing->setName('Logo');
            $objDrawing->setDescription('Logo');
            if (is_file($conf->main_document_root."/".preg_replace('/^\//','',$conf->logo_xlsx)))
                $objDrawing->setPath($conf->main_document_root."/".preg_replace('/^\//','',$conf->logo_xlsx));
            else
                $objDrawing->setPath($conf->main_document_root."/".preg_replace('/^\//','',$conf->logo));
            $objDrawing->setHeight(57);
            $objDrawing->setCoordinates('B2');
            $objDrawing->setWorksheet($objPHPExcel->getActiveSheet());
        }

        foreach(range(1,$maxcol) as $columnID)
        {
            $objPHPExcel->getActiveSheet()->getColumnDimensionByColumn($columnID)
            ->setAutoSize(true);
        }

        $objPHPExcel->setActiveSheetIndex(0);

        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel2007');
        $objWriter->save($tmp);

        header("Content-type: application/ms-excel" );

        header("Content-Length: " . filesize($tmp));
        header("Content-Disposition: attachment; filename=form-result_".date("Ydm").".xlsx");
        header('Content-Transfer-Encoding: binary');
        header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
        echo file_get_contents($tmp);
        unlink($tmp);

    }

    private function jqGrid($post)
    {
        $a = $this->prepare_data($post);
        $colArray = $a['col'];
        $datArray = $a['data'];
        global $current_user;
        //Make temp table
        $tmp_table_name = "form_result_".$post['id']."_".$current_user->get_id();
        $requete  = "CREATE TEMPORARY TABLE `".$tmp_table_name."`";
        $requete .= " (";
        $requete .= " `id` int(11) NOT NULL AUTO_INCREMENT,";

        foreach($colArray as $key => $val)
        {
            $requete .= " `".$val."` varchar(250) DEFAULT NULL, ";
        }


        $requete .= " PRIMARY KEY (`id`) ";
        $requete .= " )  ENGINE=MyISAM DEFAULT CHARSET=utf8 ";
        $sql = $this->db->query($requete);

        foreach($datArray as $key_data => $val_data)
        {
            $requete = "INSERT INTO ".$tmp_table_name." (";
            $col_insert_array = array();
            foreach(($val_data) as $key => $val)
            {
                $col_insert_array[]= "`".$key."`";
            }
            $requete .= join(',',$col_insert_array);
            $requete .= ') VALUES (';
            $data_insert_array=array();
            foreach($val_data as $key=>$val)
            {
                $data_insert_array[]="'".addslashes($val)."'";
            }
            $requete .= join(',',$data_insert_array);
            $requete .= ') ';
            $sql = $this->db->query($requete);
        }

        $page = $_REQUEST['page']; // get the requested page
        $limit = (isset($_REQUEST['rows'])?$_REQUEST['rows']:10); // get how many rows we want to have into the grid
        $sidx = $_REQUEST['sidx']; // get index row - i.e. akismet click to sort
        $sord = $_REQUEST['sord']; // get the direction
        $_search = $_REQUEST['_search'];
        $searchField = (isset($_REQUEST['searchField'])?$_REQUEST['searchField']:false);
        $searchOper = (isset($_REQUEST['searchOper'])?$_REQUEST['searchOper']:false);
        $searchString = (isset($_REQUEST['searchString'])?$_REQUEST['searchString']:"");

        if ($searchString."x" != "x" && preg_match('/([0-9]{2})\/([0-9]{2})\/([0-9]{4})/',$searchString,$arrMatch))
        {
            $searchString = $arrMatch['3']."-".$arrMatch['2']."-".$arrMatch['1'];
            $searchField =  "date_format(".$searchField.",'%Y-%m-%d')";
        }
        if ($searchOper){
            switch ($searchOper){
                case "eq":
                    $searchOper = '=';
                break;
                case "ne":
                    $searchOper = '<>';
                break;
                case "bw":
                    $searchOper = 'LIKE';
                    $searchString = $searchString."%";
                break;
                case "bn":
                    $searchOper = 'NOT LIKE';
                    $searchString = $searchString."%";
                break;
                case "ew":
                    $searchOper = 'LIKE';
                    $searchString = "%".$searchString;
                break;
                case "en":
                    $searchOper = 'NOT LIKE';
                    $searchString = "%".$searchString;
                break;
                case "cn":
                    $searchOper = 'LIKE';
                    $searchString = "%".$searchString."%";
                break;
                case "nc":
                    $searchOper = 'NOT LIKE';
                    $searchString = "%".$searchString."%";
                break;
                case "nu":
                    $searchOper = 'IS NULL';
                    $searchString = "";
                break;
                case "nn":
                    $searchOper = 'IS NOT NULL';
                    $searchString = "";
                break;
                case "in":
                    $searchOper = 'IN';
                    $searchString = "(".$searchString.")";
                break;
                case "ni":
                    $searchOper = 'NOT IN';
                    $searchString = "(".$searchString.")";
                break;
                case "lt":
                    $searchOper = '<';
                break;
                case "le":
                    $searchOper = '<=';
                break;
                case "gt":
                    $searchOper = '>';
                break;
                case "ge":
                    $searchOper = '>=';
                break;

            }
        }

        if(!$sidx) $sidx =1; // connect to the database
        global $conf;

        $requete = "SELECT COUNT(*) AS count
                      FROM ".$tmp_table_name."
                     WHERE 1=1
                   ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }

        $sql = $this->db->query($requete);
        $res = $this->db->fetch_object($sql);
        $count = $res->count;
        if( $count >0 ) {
            $total_pages = ceil($count/$limit);
        } else {
            $total_pages = 0;
        }
        if ($page > $total_pages) $page=$total_pages;
        $start = $limit*$page - $limit; // do not put $limit*($page - 1)
        if ($start < 0) $start = 0;

        $requete = "SELECT *
                      FROM ".$tmp_table_name."
                     WHERE 1=1
                   ";
        if ($_search == "true") {
            $requete .= " AND ". $searchField. " ".$searchOper." '".$searchString."'";
        }
        $requete .= "
            ORDER BY $sidx $sord
            LIMIT $start , $limit";
        $sql = $this->db->query($requete);
        $this->responce = new response();
        $this->responce->page = $page;
        $this->responce->total = $total_pages;
        $this->responce->records = $count;
        $iter=0;

        while($res = $this->db->fetch_object($sql))
        {
            $this->responce->rows[$iter]['id']=$res->id;
            $a =array();
            $a[] = $res->id;
            foreach($colArray as $key => $val)
            {
                $a[] = $res->$val;
            }
            $this->responce->rows[$iter]['cell']=$a;
            $iter++;
        }
        return json_encode($this->responce);
    }
}
class response{
    public $page;
    public $total;
    public $records;
    public $rows=array();
}