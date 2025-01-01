<?php

/**
 * Matomo - free/libre analytics platform
 *
 * @link    https://matomo.org
 * @license https://www.gnu.org/licenses/gpl-3.0.html GPL v3 or later
 */

namespace Piwik\Plugins\RebelNotifications;

use Piwik\Common;
use Piwik\Piwik;
use Piwik\Db;
use Exception;

/**
 * API for plugin RebelNotifications
 *
 * @method static \Piwik\Plugins\RebelNotifications\API getInstance()
 */
class API extends \Piwik\Plugin\API
{
    public function insertNotification(
        string $enabled,
        string $title,
        string $message,
        string $context,
        string $priority,
        string $type,
        string $raw
    ): void {
        Piwik::checkUserHasSuperUserAccess();
        $query = "INSERT INTO `" . Common::prefixTable('rebel_notifications') . "`
            (enabled, title, message, context, priority, type, raw) VALUES (?,?,?,?,?,?,?)";
        $params = [$enabled, $title, $message, $context, $priority, $type, $raw];

        $db = $this->getDb();

        try {
            $db->query($query, $params);
        } catch (Exception $e) {
            if (!$db->isErrNo($e, '1050')) {
                throw $e;
            }
        }
    }

    public function deleteNotification($notificationId)
    {
        Piwik::checkUserHasSuperUserAccess();
        try {
            $db = self::getDb();
            $query = $db->query(
                "DELETE FROM `" .
                Common::prefixTable('rebel_notifications') .
                "` WHERE `id` = ?",
                [$notificationId]
            );
            return true;
        } catch (\Exception $e) {
            throw $e;
            return false;
        }
    }

    public function updateNotification(
        string $id,
        string $enabled,
        string $title,
        string $message,
        string $context,
        string $priority,
        string $type,
        string $raw
    ): void {
        Piwik::checkUserHasSuperUserAccess();

        $query = "UPDATE `" . Common::prefixTable('rebel_notifications') . "`
                  SET enabled = ?, title = ?, message = ?, context = ?, priority = ?, type = ?, raw = ?
                  WHERE id = ?";
        $params = [$enabled, $title, $message, $context, $priority, $type, $raw, $id];

        $db = $this->getDb();

        try {
            $db->query($query, $params);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function getEnabledNotifications(): array
    {
        Piwik::checkUserHasSuperUserAccess(); // Ensure only authorized users can access this method

        $db = Db::get();
        $query = "SELECT * FROM `" . Common::prefixTable('rebel_notifications') . "` WHERE `enabled` = ?";
        $params = [1];  // Only get notifications where `enabled` is set to 1

        try {
            $notifications = $db->fetchAll($query, $params);
            return $notifications;
        } catch (\Exception $e) {
            throw new Exception("Error fetching enabled notifications: " . $e->getMessage());
        }
    }

    private function getDb()
    {
        return Db::get();
    }
}
