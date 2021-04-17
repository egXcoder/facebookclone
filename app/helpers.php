<?php

if (!function_exists('fill_object_from_array')) {
    function fill_object_from_array(Object $object, array $array)
    {
        foreach ($array as $key=>$value) {
            $object->$key = $value;
        }
        return $object;
    }
}
