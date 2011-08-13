<?php
/**
 * Copyright Zikula Foundation 2011 - Zikula Application Framework
 *
 * This work is contributed to the Zikula Foundation under one or more
 * Contributor Agreements and licensed to You under the following license:
 *
 * @license GNU/LGPLv3 (or at your option, any later version).
 * @package Zikula_View
 * @subpackage Template_Plugins
 *
 * Please see the NOTICE file distributed with this source code for further
 * information regarding copyright and licensing.
 */

/**
 * Zikula_View function to remove a entry
 *
 * This function obtains a page-specific variable from the Zikula system.
 *
 * Available parameters:
 *   - id:      The if of entry that should be removed
 *   - module:  Name of module
 *   - style:   Type of the style icon|button|link
 *   - title:   Title
 *   -function: Name of the remove function
 *
 * Example
 *   {remove id="1"}
 *
 * @param array       $params All attributes passed to this function from the template.
 * @param Zikula_View $view   Reference to the Zikula_View object.
 *
 * @return string
 */
function smarty_function_remove($params, $view)
{
    extract($params);
    if(empty($id)) {
        return;
    }
    if(empty($function)) {
        $function = 'remove';
    }
    if(empty($module)) {
        $module = ModUtil::getName();
    }
    if(empty($style)) {
        $style = 'icon';
    }
    if(empty($title)) {
        $title = __('Remove');
    }

    $output = array();

    if($style == 'icon') {
        $output[] = '<a id="r_'.$id.'">';
        $output[] = '<img src="images/icons/extrasmall/14_layer_deletelayer.png" alt="'.$title.'" title="'.$title.'" width="16" height="16"/>';
        $output[] = '</a>';
    } else if($style == 'button') {
        $output[] = '<div class="z-buttons">';
        $output[] = '<a id="r_'.$id.'">';
        $output[] = '<img src="images/icons/extrasmall/14_layer_deletelayer.png" alt="'.$title.'" title="'.$title.'" width="16" height="16"/>';
        $output[] = $title;
        $output[] = '</a>';
        $output[] = '</div>';

    } else if ($style == 'link') {
        $output[] = '<a id="r_'.$id.'">'.$title.'</a>';
    } else {
        return;
    }

    $output[] = '<script type="text/javascript">';
    $output[] = '    reloadIt = function() {';
    $output[] = '        window.location.href=window.location.href;';
    $output[] = '    }';
    $output[] = '    deleteIt = function(res) {';
    $output[] = '        if(res) {';
    $output[] = '            var pars = "module='.$module.'&func='.$function.'&id='.$id.'";';
    $output[] = '            var myAjax = new Ajax.Request(';
    $output[] = '                "ajax.php", {';
    $output[] = '                    method: \'get\',';
    $output[] = '                    parameters: pars,';
    $output[] = '                    onComplete: reloadIt';
    $output[] = '                }';
    $output[] = '            );';
    $output[] = '        }';
    $output[] = '    }';
    $output[] = '    $(\'r_'.$id.'\').observe(';
    $output[] = '        \'click\',';
    $output[] = '        Zikula.UI.IfConfirmed(';
    $output[] = '            \'Do you want to remove this entry?\',';
    $output[] = '            \'Confirm action\',';
    $output[] = '            deleteIt';
    $output[] = '        )';
    $output[] = '    );';
    $output[] = '</script>';

    return implode($output, "\n");
}
