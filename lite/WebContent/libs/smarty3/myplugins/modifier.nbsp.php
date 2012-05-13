<?php
/**
 * Smarty plugin
 * @package Smarty
 * @subpackage plugins
 */


/**
 * Smarty plugin
 *
 * Type:     modifier<br>
 * Name:     nbsp<br>
 * @return string
 */
function smarty_modifier_nbsp($value)
{
    return str_replace(' ', '&nbsp;', $value);
}

/* vim: set expandtab: */
