<?php
/**
 * Tasks
 *
 * @copyright  (c) Tasks Development Team
 * @link       https://github.com/phaidon/Tasks/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @category    Zikula_3rdParty_Modules
 * @package Tasks
 */

function smarty_function_formatParticipants($params, &$smarty)
{
    return ModUtil::apiFunc(
        ModUtil::getName(),
        'user',
        'formatParticipants',
        $params['p']
    );    
}
