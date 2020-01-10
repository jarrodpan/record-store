<?php

class Template {
    
    private static $root;
    private static $ends = true;
    
    public static function root($r)
    {
        self::$root = $r;
    }
    
    public function __callStatic($method, $args = null)
    {
        self::out($method, (empty($args) ? null : $args[0]));
    }
    
    public function productView($data = null)
    {
        
    }
    
    public static function out($page, $data = null)
    {
        include($_SERVER['DOCUMENT_ROOT'].self::$root."/template/".$page.".php");
    }
    
    public static function header($data = null)
    {
        if(self::$ends) self::out("header", $data);
    }
    
    public static function footer($data = null)
    {
        if(self::$ends) self::out("footer", $data);
    }
    
    public static function tagManager($data = null)
    {
        self::out("tags_btn", $data);
        //self::out("tags", $data);
    }
    
    /*public static function barcodes($data = null)
    {
        self::out("barcodes", $data);
        //self::out("tags", $data);
    }
    */
    public static function product($data = null)
    {
        self::out("product", $data);
        //self::out("tags", $data);
    }
        
    public static function addEnds(bool $x)
    {
        self::$ends = $x;
    }
}

?>