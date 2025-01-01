<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\RebelNotifications;

use Piwik\Db;
use Exception;
use Piwik\Common;
use Piwik\Notification;
use Piwik\Container\StaticContainer;
use Piwik\Log\LoggerInterface;

class RebelNotifications extends \Piwik\Plugin
{
    public function registerEvents()
    {
        return [
            'Login.authenticate.successful' => 'getNotifications'
        ];
    }

    public function getNotifications()
    {
        $this->logger()->info("Get notifications");
        try {
            // Call your API function to get enabled notifications
            $api = new API();
            $enabledNotifications = $api->getEnabledNotifications();
            $this->logger()->info("Ok, we are here");

            // Loop through the notifications
            foreach ($enabledNotifications as $notification) {
                // Create a new Notification object
                $this->logger()->info("Title {$notification['title']}");
                $notification = new Notification($notification['message']); // Pass message as constructor argument
                $notification->title = $notification['title'];
                $notification->context = $notification['context'];
                $notification->priority = $notification['priority'];
                $notification->raw = $notification['raw'];
                $notification->type = $notification['type'];
                Notification\Manager::notify('RebelNotifications_notice', $notification);
            }
        } catch (\Exception $e) {
            $this->logger()->info("Ok, failed {$e->getMessage()}");
        }


    }

    public function install()
    {
        $db = $this->getDb();
        $query = "CREATE TABLE " . Common::prefixTable('rebel_notifications') . " (
            `id` int(24) NOT NULL AUTO_INCREMENT,
            `enabled` int NOT NULL,
            `title` varchar(255) NOT NULL,
            `message` text,
            `context` varchar(128) NOT NULL,
            `priority` varchar(128) NOT NULL,
            `type` varchar(128) NOT NULL,
            `raw` int NOT NULL,
            `flags` varchar(255),
            `timestamp` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`)
            )
            ";
        try {
            $db->exec($query);
        } catch (Exception $e) {
            if (!$db->isErrNo($e, '1050')) {
                throw $e;
            }
        }
    }

    private function getDb()
    {
        return Db::get();
    }

    public function logger()
    {
        return StaticContainer::get(LoggerInterface::class);
    }

}
