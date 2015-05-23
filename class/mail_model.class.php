<?php

/**
 \file       class/mailmodel.class.php
\ingroup    mail
\brief      Fichier de la classe permettant la gestion des modèles de mail
\version    1
*/


/**
 \class      mailmodel
\brief      Classe de gestion des modèles de mail
*/
class mail_model extends common_object{
    private $pId;
    private $pTitle;
    private $pContent;
    private $pActive;

    public $id;
    public $title;
    public $content;
    public $active;

    protected $table = 'mail_model';

    /**
     \brief        Recupère les données d'un modèle de mail dans la base
     \param        id              Id SQL du modèle de mail
     \return       bool            This si OK; False si KO
     */
    public function fetch($id)
    {
        $this->id = $id;
        $this->pId = $id;
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE id = ".$id;
        $sql = $this->db->query($requete);
        if ($sql){
            $res = $this->db->fetch_object($sql);
            $this->pTitle = $res->title;
            $this->pContent = $res->content;
            $this->pActive = $res->active;
            return($id);
        } else {
            return (false);
        }
    }
    public function get_all()
    {
        if ($this->get_id() > 0)
        {
            $this->id = $this->pId;
            $this->content = $this->pContent;
            $this->active = $this->pActive;
            $this->title = $this->pTitle;
        }
    }
    public function create()
    {
        $requete = "INSERT INTO ".$this->table." () VALUES ()";
        $sql = $this->db->query($requete);
        if ($sql)
        {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
        } else {
            $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
        }
        return $this->db->last_insert_id($this->table);
    }
    public function del()
    {
        if ($this->get_id() > 0)
        {
            $requete="DELETE FROM ".$this->table."
                            WHERE id=".$this->id;
            $sql = $this->db->query($requete);
            if ($sql)
            {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_DEBUG);
            } else {
                $this->log->log(get_class($this)." - ".$requete . " res: ".print_r($sql,1),LOG_ERR);
            }
            return $sql;
        } else {
            return false;
        }
    }
    public function free_title($title)
    {
        $new_title = $this->find_new_title($title,$title);
        return $new_title;
    }
    private function find_new_title($base_title,$title,$iter=false)
    {
        if ($iter)
        {
            $title = $base_title."_".$iter;
        }
        $requete = "SELECT *
                      FROM ".$this->table."
                     WHERE title = '".addslashes($title)."'";

        $sql = $this->db->query($requete);
        if ($sql && $this->db->num_rows($sql) > 0)
        {
            if (!$iter) { $iter = 1;} else { $iter++; }

            $free = $this->find_new_title($base_title,$title,$iter++);
            return $free;
        } else {
            return $title;
        }
    }
    public function get_id()
    {
        if ($this->pId > 0)
        {
            $this->id = $this->pId;
            return $this->pId;
        } else {
            return false;
        }
    }
    public function get_title()
    {
        if ($this->get_id() > 0)
        {
            return $this->pTitle;
        } else {
            return false;
        }
    }
    public function get_content()
    {
        if ($this->get_id() > 0)
        {
            return $this->pContent;
        } else {
            return false;
        }
    }
    public function get_active()
    {
        if ($this->get_id() > 0)
        {
            return $this->pActive;
        } else {
            return false;
        }
    }
    public function set_title($title)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field('title', $title);
        } else {
            return false;
        }
    }
    public function set_content($content)
    {
        if ($this->get_id() > 0)
        {
            return $this->update_field('content', $content);
        } else {
            return false;
        }
    }
    public function set_active($active)
    {
        if (!$active || empty($active) || is_null($active))
        {
            $active = "0";
        }
        if ($this->get_id() > 0)
        {
            return $this->update_field('active', $active);
        } else {
            return false;
        }
    }

    /**
     \brief        Prépare un mail pour l'envoi
     \param        id              Id SQL du modèle de mail
     \param        to_id           Id SQL du destinataire
     \param        from_id         Id SQL de l'envoyeur
     \param        data            Misc datas
     \return       bool            True si OK; False si KO
     */
    public function prepare_mail($to_id,$from_id,$data=false)
    {
        load_alternative_class('class/user.class.php');
        $to_user = new user($this->db);
        $to_user->fetch($to_id);
        $from_user = new user($this->db);
        $from_user->fetch($from_id);

        $loader['content'] = hash_loader(array(
                "mail.html" => $this->get_content()
        ));
        $loader['subject'] = hash_loader(array(
                "subject.html" => $this->get_title()
        ));

        $template['subject'] = new H2o('subject.html', array('loader' => $loader['subject'], 'cache' => $this->get_cache()));
        $template['content'] = new H2o('mail.html', array('loader' => $loader['content'], 'cache' => $this->get_cache()));

        global $conf;

        $mail_content['subject'] = $template['subject']->render(array(
                'to_user' => $to_user->get_all(),
                'from_user' => $from_user->get_all(),
                'data' => $data,
                'conf' => $conf
        ));
        $mail_content['content'] = $template['content']->render(array(
                'to_user' => $to_user->get_all(),
                'from_user' => $from_user->get_all(),
                'data' => $data,
                'conf' => $conf
        ));
        return $mail_content;
    }
    /**
     \brief        Prépare un mail pour l'envoi
     \param        id              Id SQL du modèle de mail
     \param        to_id           Id SQL du destinataire
     \param        from_id         Id SQL de l'envoyeur
     \param        data            Misc datas
     \return       bool            True si OK; False si KO
     */
    public function prepare_mail_external($from_id,$data=false)
    {
        load_alternative_class('class/user.class.php');
        $from_user = new user($this->db);
        $from_user->fetch($from_id);

        $loader['content'] = hash_loader(array(
                "mail.html" => $this->get_content()
        ));
        $loader['subject'] = hash_loader(array(
                "subject.html" => $this->get_title()
        ));

        $template['subject'] = new H2o('subject.html', array('loader' => $loader['subject'], 'cache' => $this->get_cache()));
        $template['content'] = new H2o('mail.html', array('loader' => $loader['content'], 'cache' => $this->get_cache()));

        global $conf;

        $mail_content['subject'] = $template['subject']->render(array(
                'from_user' => $from_user->get_all(),
                'data' => $data,
                'conf' => $conf
        ));
        $mail_content['content'] = $template['content']->render(array(
                'from_user' => $from_user->get_all(),
                'data' => $data,
                'conf' => $conf
        ));
        return $mail_content;
    }
    /**
     \brief        Prépare un mail pour l'envoi
     \param        id              Id SQL du modèle de mail
     \param        to_id           Id SQL du destinataire
     \param        from_id         Id SQL de l'envoyeur
     \param        data            Misc datas
     \return       bool            True si OK; False si KO
     */
    public function prepare_external_2($to,$from,$data=false)
    {
        load_alternative_class('class/user.class.php');
        if (is_numeric($to))
        {
            $to_user = new user($this->db);
            $to_user->fetch($to_id);
        } else {
            $to_user = false;
        }
        if (is_numeric($from))
        {
            $from_user = new user($this->db);
            $from_user->fetch($from_id);
        } else {
            $from_user = false;
        }

        $loader['content'] = hash_loader(array(
                "mail.html" => $this->get_content()
        ));
        $loader['subject'] = hash_loader(array(
                "subject.html" => $this->get_title()
        ));

        $template['subject'] = new H2o('subject.html', array('loader' => $loader['subject'], 'cache' => $this->get_cache()));
        $template['content'] = new H2o('mail.html', array('loader' => $loader['content'], 'cache' => $this->get_cache()));

        global $conf;

        $mail_content['subject'] = $template['subject']->render(array(
                'to_user' => $to_user->get_all(),
                'from_user' => $from_user->get_all(),
                'data' => $data,
                'conf' => $conf
        ));
        $mail_content['content'] = $template['content']->render(array(
                'to_user' => $to_user->get_all(),
                'from_user' => $from_user->get_all(),
                'data' => $data,
                'conf' => $conf
        ));
        return $mail_content;
    }
}