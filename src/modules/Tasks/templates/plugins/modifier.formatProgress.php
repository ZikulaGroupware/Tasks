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

function smarty_modifier_formatProgress($text)
{
    // Fixing for now the other args
    if($text == 100) {
        return '<div style="background-color:#00CC66;padding:2px;">'.$text.' %</div>';
    } else if($text >= 70) {
        return '<div style="background-color:#99FF00;padding:2px;">'.$text.' %</div>';
    } else if($text >= 40) {
        return '<div style="background-color:#FFCC66;padding:2px;">'.$text.' %</div>';
    } else  {
        return '<div style="background-color:#FF6633;padding:2px;">'.$text.' %</div>';
    }
}
