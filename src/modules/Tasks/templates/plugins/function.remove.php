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
function smarty_function_remove($params, &$smarty)
{
    extract($params);
    
    if(!ModUtil::apiFunc(ModUtil::getName(), 'user', 'isAllowedToEdit', $params)) {
        return;
    }
    
    $smarty->assign($params);
    if( isset($style) and $style == 'button') {
        return $smarty->fetch('plugins/remove_button.tpl');
    } else {
        return $smarty->fetch('plugins/remove_icon.tpl');
    }
    
}
