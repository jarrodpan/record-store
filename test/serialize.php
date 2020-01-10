<?php

$obj = new stdClass();

$obj->asdf = "asdf";

$str = serialize($obj);

echo $str;

?>