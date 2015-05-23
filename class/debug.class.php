<?php
class debug{
    private $store=array();
    private $count=0;
    public function __construct()
    {

    }
    public function has_data()
    {
        if ($this->count > 0)
            return 1;
        else
            return false;
    }
    public function collect($in)
    {
        $this->store[]=$in;
        $this->count++;
    }
    public function display()
    {
        global $conf;
        $html = "";
        if (isset($conf->debug_use_printf) && $conf->debug_use_printf)
        {
            foreach($this->store as $val)
            {
                $html .= print_r($val,1);
            }
        } else {
            foreach($this->store as $val)
            {
                $html .= $this->varDumpToString($val);
            }
        }
        return ($html);
    }
    private function varDumpToString($var) {
        ob_start();
        var_dump($var);
        $result = ob_get_clean();
        return $result;
    }
}