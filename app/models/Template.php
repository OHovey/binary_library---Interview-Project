<?php  
 
class Template
{
    private $data; 


    public function load($url, $title = '')
    {
        if ($title != '') { $this->set_data('page_title', $title); } 
        include($url);
    }

    public function redirect($url) 
    {
        header('Location: ' . $url);
        exit;
    }

    public function set_data($name, $value, $clean = false)
    {
        if ($clean == true) { $this->data[$name] = htmlentities($value); } else { $this->data[$name] = $value; }
    }

    public function get_data($name, $echo = true)
    {
        $data = '';

        if (isset($this->data[$name]))
        {
            if ($echo == true)
            {
                echo $this->data[$name];
            }
            else
            {
                return $this->data[$name];
            }
        }
    }
}