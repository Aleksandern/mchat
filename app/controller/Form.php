<?php

class Form
{
    private $vars = Array();
    private $config;
    private $nick;
    private $msg;
    private $tmpl_form;
    private $tmpl_msgs;
    private $tmpl_send;
    private static $only_reg = false;

    function __construct()
    {
        $this->config = Config::get();       
        $this->nick = new Nick();
        $this->msg = new Msg();
        //$this->tmpl = site_path.DIRSEP.'html'.DIRSEP.'form.php';
        $this->tmpl_form = new Tmpl();
        $this->tmpl_msgs = new Tmpl();
        $this->tmpl_send = new Tmpl();
        //$this->vars['module_path'] = mch_path.'/html';
        $this->tmpl_form->module_path = mch_path.'/html';
        $this->tmpl_send->module_path = mch_path.'/html';
        //$this->vars['action'] = mch_path.'/action.php';
        $this->tmpl_form->action = mch_path.'/action.php';
        //$this->vars['nick'] = '';
        $this->tmpl_send->nick = '';
        //$this->vars['nick_profile'] = '';
        $this->tmpl_send->nick_profile = '';
    }

    private function prepare()
    {
        //if (file_exists($this->tmpl)) {
            //extract($this->vars, EXTR_OVERWRITE);
            //include $this->tmpl;
        //} else return 'Files does not exists!';
        
        $form_file = site_path.DIRSEP.'html'.DIRSEP.'form.php';
        $send_file = site_path.DIRSEP.'html'.DIRSEP.'send.php';

        $this->tmpl_send->setFile($send_file);
        $this->tmpl_form->send = $this->tmpl_send;

        $msgs_file = site_path.DIRSEP.'html'.DIRSEP.'msgs.php';
        $this->tmpl_msgs->setFile($msgs_file);

        $msg_all = $this->msg->listAll();
        $msgs_html = '';
        foreach ($msg_all['msgs'] as $key => $val) {
            $this->tmpl_msgs->id = $val['id'];
            $this->tmpl_msgs->date = $val['date'];
            $this->tmpl_msgs->id_nick = $val['id_nick'];
            $this->tmpl_msgs->nick = $val['nick'];
            $this->tmpl_msgs->textmsg = $val['textmsg'];

            $prepare = $this->tmpl_msgs->prepare();
            $msgs_html .= $prepare['html'];
        }
        //$this->tmpl_msgs->funcJs();

        $this->tmpl_form->msgs = $msgs_html;

        $this->tmpl_form->setFile($form_file);
        $this->tmpl_form->display();
    }

    public function get()
    {
        //$msg_all = $this->msg->listAll();
        //$this->vars['msgs'] = $this->msg->listHtml($msg_all['msgs']);
        //$this->tmpl_form->msgs = $this->msg->listHtml($msg_all['msgs']);
        //$this->vars['time'] = Date::time();
        $this->tmpl_send->time = Date::time();
        $this->tmpl_form->time = Date::time();

        if (empty($this->vars['nick'])) {
            $nick_info = $this->nick->add($this->config['nick']);
        } else {
            $nick_info = $this->nick->add($this->vars['nick'], $this->vars['nick_profile'], true);
        }
        //$this->vars['nick'] = $nick_info['nick'];
        $this->tmpl_send->nick = $nick_info['nick'];

        $this->prepare();
    }

    public function nick($nick, $profile = '')
    {
        //$this->vars['nick'] = $nick;
        $this->tmpl_send->nick = $nick;
        //$this->vars['nick_profile'] = $profile;
        $this->tmpl_send->nick_profile = $profile;
    }

    public function onlyReg($val = true)
    {
        if ($val) self::$only_reg = true;
        else self::$only_reg = false;
    }

}
