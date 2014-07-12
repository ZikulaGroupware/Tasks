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
 *   {add}
 *
 * @author       Fabian Wuertz
 * @since        18 February 2009
 * @return       string the atom ID
 */
function smarty_function_add($params, &$smarty)
{
    if( !SecurityUtil::checkPermission('Tasks::', '::', ACCESS_ADD) ) {
        return;
    }
    extract($params);
    unset($params);
    return $smarty->fetch('plugins/add.tpl');
}
