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
    $dom = ZLanguage::getModuleDomain('Tasks');
    
    extract($params);
    $unames = array();
    foreach($p as $participant) {
        if(is_array($participant)) {
            $uname = $participant['uname'];
            $unames[$uname] = $uname;
        } else {
            $unames[$participant] = $participant; 
        }
    }

    $uname = UserUtil::getVar('uname');
    $number_of_participants = count($unames);
    if(array_key_exists($uname, $unames)) {
        if($number_of_participants == 1 ) {
            return __('You', $dom);
        } else if( count($unames) == 2 ) {
            unset($unames[$uname]);
            return __f('You and %s', array_shift($unames), $dom);
        } else {
            $number_of_participants = $number_of_participants-1;
            return __f('You and %s others', $number_of_participants, $dom);
        }
    } else {
        if($number_of_participants < 3) {
            return implode(' '.__('and', $dom).' ', $unames);
        } else {
            sort($unames);
            $number_of_participants = $number_of_participants-1;
            return __f(
                '%s and %t others',
                array(array_shift($unames), $number_of_participants),
                $dom
            );
        }
    }

    return implode(',', $unames);
}
