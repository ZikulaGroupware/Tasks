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
function smarty_function_edit($params, &$smarty)
{
    extract($params);
    unset($params);
    if(!SecurityUtil::checkPermission('Tasks::', '::', ACCESS_EDIT) and $cr_uid != UserUtil::getVar('uid')) {
            return;
    }
    $smarty->assign('tid', $tid);
    return $smarty->fetch('plugins/edit.tpl');
}
