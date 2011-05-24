<?php
/**
 * Wikula
 *
 * @copyright  (c) Wikula Development Team
 * @link       http://code.zikula.org/wikula/
 * @version    $Id: modifier.wakka.php 107 2009-02-22 08:51:33Z mateo $
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * category    Zikula_3rdParty_Modules
 * @subpackage Wiki
 * @subpackage Wikula
 */

function smarty_modifier_transform($text)
{
    if( ModUtil::available('LuMicuLa') ) {
        return ModUtil::apiFunc('LuMicuLa', 'user', 'transform', array(
            'text'   => $text,
            'modname' => 'Tasks')
        );
    } else {
        return $text;
    }
}
