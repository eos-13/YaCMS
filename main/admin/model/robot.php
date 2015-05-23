<?php
class model_robot extends admin_common
{
    public $message = false;

    public function run()
    {
        $array=array();
        $f = "";
        if (is_file("robot.txt"))
        {
            return file_get_contents("robot.txt");
        } else {
            return "";
        }
        return $array;
    }
    public function set_robot($robot)
    {
        $res = file_put_contents('robot.txt', $robot);
        if ($res) $this->message = _("Opération effectuée");
        else $this->message = _("Opération echouée");
    }

}