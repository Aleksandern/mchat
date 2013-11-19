<?php

class Tmpl 
{
    // файл главного шаблона
    //protected $tmpl;
    protected $config;
    private $varpub;    
    private $vars = Array();
    private $file;
    // если $debug = false то из шаблона удаляются символы переноса строки и HTML-код получится одной строкой 
    private static $debug = true;
    // если $replace_m = true , то переменные в шаблонах вызываются через {ept} и через $this->ept
    // если false, то только $this->ept
    private static $replace_m = true;
    private static $func_js = false;

    function __construct () 
    {
        $this->config = Config::get();
        //$this->tmpl = site_path.DIRSEP.$this->config['tmpl'].DIRSEP.$this->config['tmpl_name'].DIRSEP.'index.php';
    }

  /*public function addvar ($var, $value) {
    $this->vars[$var] = $value;
    return $this;
  }*/
  
    public function __set ($var, $value) 
    {
        $this->vars[$var] = $value;
        return $this;
    }
  
    public function __get ($var) 
    {
        //echo 'query: '.' '.$var.'<br>';
        if (array_key_exists($var, $this->vars)) {
            if ($this->vars[$var] instanceof self) {
                $prepare = $this->vars[$var]->prepare();
                return $prepare['html'];
            } else {
                return $this->vars[$var];
            }
        }
        return NULL;    
    }
    //  получить массив переменных
    public function getAllVars () 
    {
        return $this->vars;
    }

    // сохранить массив переменных vars из другого экземпляра этого класса в этот
    public function addOtherVars ($vars) 
    {
        $lang='';

        if ($vars instanceof LangGet) {
            $lang = 'lang_';
            $det_module = $vars->detModule();
            if ($det_module!='') $lang .= 'm_';
            //echo 'asd!!!';
            //echo $det_module;
            //echo $lang.'<br>';
            $vars = $vars->getAll();
        }

        if (!empty($vars)) {
            foreach ($vars as $key => $val) {
                if ($lang!='') {
                    $key = $lang.$key;
                }
                $this->$key = $val;
                
            }
        }
        //print_r ($vars);
    }

    public function setFile ($file) 
    {
        $this->file = $file;
    }

    public function prepare() 
    {
        ob_start();
        ${__CLASS__} = $this;
        //if (file_exists($this->file)) {        
            include $this->file;
        //}
        unset(${__CLASS__});
        if (self::$replace_m) {
            return (self::$debug) ? $this->replVars(ob_get_clean()) : $this->zip($this->replVars(ob_get_clean()));
        } else {
            return (self::$debug) ? ob_get_clean() : $this->zip(ob_get_clean());
        }
        //return $this->repl_vars(ob_get_clean());  
        //return ob_get_clean();  
        //return (self::$debug) ? ob_get_clean() : $this->zip(ob_get_clean());
    }

    public function display () 
    {
        $prepare = $this->prepare();
        echo $prepare['html'];
        if (self::$func_js) {
            echo $this->funcJs($prepare['js']);
        }
    }

    private function replVars ($text) 
    {
        // массив для переменных которые совпали
        $matched = Array();
        $text_arr['html'] = $text;
        $text_arr['js'] = $text;


        // если есть форма в шаблоне то добавляем скрытое поле со значением token
        if ($this->config['token']) {
            $token = new Token();
            $token_gen = $token->gen();
            $text_arr['html'] = str_replace('</form>', '<input type="hidden" name="mch_token" value="'.$token_gen.'"></form>', $text_arr['html']);
        }        

        // ищем в шаблоне переменные для замены
        //preg_match_all ('/{(.*)}/', $text, $match);
        preg_match_all ('/{(.*)}/sUS', $text, $match);

        if (!empty($match[0])) {
            // убираем дубликаты
            $match[1] = array_unique($match[1]);      
            foreach ($this->vars as $varname => $varval) {
                foreach ($match[1] as $key => $val) {
                    if ($varname==$val) {
                        $matched[] = $varname;
                            if ($varval instanceof self) {
                                $varval= $this->$varname;
                            }
                        $text_arr['html']  = str_replace('{' . $varname . '}', $varval, $text_arr['html'], $count);
                        //if (self::$func_js) {
                            $text_arr['js'] = str_replace('{' . $varname . '}', "'+msg.".$varname."+'", $text_arr['js'], $count);
                        //}

                    }
                }
            }

            // ищем разницу в массивах (массив с совпавшими переменными и массив с указанными переменными в шаблоне)
            $diff = array_diff($match[1], $matched);
            // удаляем переменные из шаблона, которые не используются
            foreach ($diff as $val) {
                $text_arr['html'] = str_replace('{' . $val . '}', '', $text_arr['html']);
                $text_arr['js'] = str_replace('{' . $val . '}', '', $text_arr['js']);
            }      
        }
        return $text_arr;
    }

    public function funcJs($js)
    {
        $js = $this->zip($js);
        $pathinfo = pathinfo($this->file);
        $func_js = '
            <script type="text/javascript">
            function mch_add'.$pathinfo['filename'].'(msg, el) {';
        $func_js .= "el.append('".$js;
        $func_js .= "');} 
            </script>";

        return $func_js;
    }

    // удаляем всякие переносы и новые строки из html кода
    private function zip ($text) 
    {
        return (empty($text)) ? $text : str_replace (array("\t", "\n", "\r"), '', $text);
    }
}

