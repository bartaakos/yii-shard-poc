<?php

class BaseDto
{
    protected static $safeAttributes = array();

    function __construct($attributes) {
        foreach ($attributes as $attr => $value) {
            if(in_array($attr, static::$safeAttributes)) {
                $this->$attr = $value;
            }
        }
    }
}