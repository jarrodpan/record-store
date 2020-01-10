<?php

class Template {
    
    private static $root = "";
    private static $path = "";
    private static $ends = true;
    
    public static function root($r = "", $p = "")
    {
        self::$root = $r;
        self::$path = $p;
    }
    
    public function __callStatic($method, $args = null)
    {
        self::out($method, (empty($args) ? null : $args[0]));
    }
    
    public function productView($data = null)
    {
        
    }
    
    public static function rootPath()
    {
        return self::$root.self::$path;
    }
    
    public static function out($page, $data = null)
    {
        include($_SERVER['DOCUMENT_ROOT'].self::$root."/template".self::$path.'/'.$page.".php");
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