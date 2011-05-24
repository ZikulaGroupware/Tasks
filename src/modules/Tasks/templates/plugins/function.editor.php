<?php
/**
 * Wikula
 *
 * @copyright  (c) Wikula Development Team
 * @link       http://code.zikula.org/wikula/
 * @version    $Id: function.wikkaedit.php 107 2009-02-22 08:51:33Z mateo $
 * @license    GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * category    Zikula_3rdParty_Modules
 * @subpackage Wiki
 * @subpackage Wikula
 */

function smarty_function_editor($params, &$smarty)
{
    if (ModUtil::available('LuMicuLa'))  {
        $args = array(
            'textfieldname' => 'description',
            'modname'       => 'Tasks'
        );
        return ModUtil::apiFunc('LuMicuLa', 'plugin', 'editor', $args);
    }
}
