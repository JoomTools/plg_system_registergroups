<?php
/**
 * @package      Joomla.Plugin
 * @subpackage   System.Jtregistergroups
 *
 * @author       Guido De Gobbis <support@joomtools.de>
 * @copyright    2018 JoomTools.de - All rights reserved.
 * @license      GNU General Public License version 3 or later
 */

namespace Joomla\CMS\Form\Field;

defined('JPATH_PLATFORM') or die;

use Joomla\CMS\Component\ComponentHelper;
use Joomla\CMS\Form\FormHelper;
use Joomla\CMS\Helper\UserGroupsHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Plugin\PluginHelper;
use Joomla\Registry\Registry;

if (version_compare(JVERSION, '4', 'lt')) {
    \JLoader::registerAlias('\\Joomla\\CMS\\Form\\Field\\ListField', 'JFormFieldList');
    \JLoader::applyAliasFor('JFormFieldList');
    FormHelper::loadFieldClass('list');
}

/**
 * Class to show allowed groups in menu item
 * selected in plugin params
 *
 * @since  1.0.0
 */
class AllowedgroupsField extends ListField
{
    /**
     * The form field type.
     *
     * @var    string
     * @since  1.0.0
     */
    protected $type = 'Allowedgroups';

    /**
     * Method to get the field options.
     *
     * @return  array  The field option objects.
     * @since   1.0.0
     */
    protected function getOptions()
    {
        $pluginRegisterglobals = PluginHelper::getPlugin('system', 'jtregistergroups');
        $pluginParams          = new Registry($pluginRegisterglobals->params);
        $allowedGroups         = (array) $pluginParams->get('set_allowed_usertypes', '');
        $groups                = UserGroupsHelper::getInstance();
        $options               = array();

        // Set Global
        $userParams       = ComponentHelper::getParams('com_users');
        $globalGroup      = (int) $userParams->get('new_usertype');
        $globalGroupTitle = $groups->get($globalGroup)->title;
        $options[]        = (object) array(
            'text'  => Text::_('JGLOBAL_USE_GLOBAL') . ' (' . $globalGroupTitle . ')',
            'value' => 0,
        );

        foreach ($allowedGroups as $allowed) {
            if (!$group = $groups->get($allowed)) {
                continue;
            }

            $options[] = (object) array(
                'text'  => $group->title,
                'value' => $group->id,
            );
        }

        return $options;
    }
}
