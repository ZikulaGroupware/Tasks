<?php

/**
 * Tasks
 *
 * @copyright (c) 2011, Fabian Wuertz
 * @author Fabian Wuertz
 * @link http://fabian.wuertz.org
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package Tasks
 */

/**
 * Smarty function to show a link to the user settings
 *
 * Example
 *
 *   {edit tid=1}
 *
 * @author       Fabian Wuertz
 * @since        18 February 2009
 * @return       string the atom ID
 */
function smarty_function_actions($params, &$smarty)
{
    extract($params);
    
    if(ModUtil::apiFunc(ModUtil::getName(), 'user', 'isAllowedToEdit', $params)) {
        $smarty->assign('tid', $tid);
        if( isset($style) and $style == 'button') {
            $output  = $smarty->fetch('plugins/edit_button.tpl');
            $output .= $smarty->fetch('plugins/remove_button.tpl');
        } else {
            $output  = $smarty->fetch('plugins/edit_icon.tpl');
            $output .= $smarty->fetch('plugins/remove_icon.tpl');
        }
        return $output;
    }
    
}
