<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\RebelNotifications;

use Piwik\Menu\MenuAdmin;
use Piwik\Piwik;

/**
 * This class allows you to add, remove or rename menu items.
 * To configure a menu (such as Admin Menu, Top Menu, User Menu...) simply call the corresponding methods as
 * described in the API-Reference http://developer.piwik.org/api-reference/Piwik/Menu/MenuAbstract
 */
class Menu extends \Piwik\Plugin\Menu
{
    public function configureAdminMenu(MenuAdmin $menu)
    {
        if (Piwik::isUserHasSomeAdminAccess()) {
            $menu->registerMenuIcon('RebelNotifications_RebelNotifications', 'icon-document');
            $menu->addItem(
                'RebelNotifications_RebelNotifications',
                null,
                $this->urlForAction('index'),
                $order = 42
            );
            $menu->addItem(
                'RebelNotifications_RebelNotifications',
                'RebelNotifications_RebelNotifications',
                $this->urlForAction('index'),
                $order = 43
            );
        }
    }
}
