<?php
/**
 * Wikula
 *
 * @copyright  (c) Wikula Development Team
 * @link       https://github.com/phaidon/Wikula/
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * category    Zikula_3rdParty_Modules
 * @subpackage Wiki
 * @subpackage Wikula
 */

function smarty_modifier_formatDeadline($deadline)
{
    $dom = ZLanguage::getModuleDomain('Tasks');
    
    $today = new DateTime(date('Y-m-d'));
    $interval = $today->diff($deadline);
    $interval = (int)$interval->format('%R%a');
    
    if( $interval == 0 ) {
        return __('Today', $dom);
    } else if( $interval == 1) {
        return __('Tomorrow', $dom);
    } else if( $interval < 0) {
        return __('Overdue', $dom);
    } else if( $interval < 20) {
        return __f('In %s days', $interval, $dom);
    } else {
        return $deadline->format('d. M. Y');
    }
    
}
