<?php

namespace Piwik\Plugins\RebelNotifications;

use Piwik\Common;
use Piwik\View;
use Piwik\Db;
use Piwik\Plugins\RebelNotifications\API;
use Piwik\Notification;
use Piwik\Container\StaticContainer;
use Piwik\Log\LoggerInterface;
use Piwik\Request;
use Piwik\Piwik;
use Piwik\Plugin\ControllerAdmin;

class Controller extends ControllerAdmin
{
    /**
     * Display the list of existing notifications and a form to create new ones.
     */
    public function index($siteID = 0, $notificationList = null)
    {
        Piwik::checkUserHasSuperUserAccess();
        if ($siteID == 0) {
            $request = Request::fromRequest();
            $siteID = $request->getIntegerParameter('idSite', 0);
        }
        $db = Db::get();
        $query = "SELECT * FROM `" . Common::prefixTable('rebel_notifications') . "`";
        $notifications = $db->fetchAll($query);

        $view = new View('@RebelNotifications/manageNotifications');
        $this->setBasicVariablesView($view);
        $view->assign('messages', $notifications);
        $view->assign('success', true);
        $view->assign('error', false);
        $view->assign('contexts', $this->contexts());
        $view->assign('types', $this->types());
        $view->assign('priorities', $this->priorities());
        $view->assign('notificationList', $notificationList);

        echo $view->render();
    }

    /**
     * Handle form submission for creating a new notification.
     */
    public function createNotification()
    {
        $enabled = trim(Request::fromRequest()->getStringParameter('enabled', 'string'));
        $title = trim(Request::fromRequest()->getStringParameter('title', 'string'));
        $message = trim(Request::fromRequest()->getStringParameter('message', 'string'));
        $context = trim(Request::fromRequest()->getStringParameter('context', 'string'));
        $priority = trim(Request::fromRequest()->getStringParameter('priority', 'string'));
        $type = trim(Request::fromRequest()->getStringParameter('type', 'string'));
        $raw = trim(Request::fromRequest()->getStringParameter('raw', 'string'));

        try {
            API::getInstance()->insertNotification(
                $enabled,
                $title,
                $message,
                $context,
                $priority,
                $type,
                $raw
            );
            $this->logger()->debug(
                'Created notification: {message}',
                ['message' => 'success']
            );
            $notificationList[] = "Notification {$title} was created";
            $this->index(0, $notificationList);
        } catch (\Exception $e) {
            $this->logger()->error(
                'Error creating notification: {message}',
                ['message' => $e->getMessage()]
            );
            $notificationList[] = $e->getMessage();
            $this->index(0, $notificationList);
        }
    }

    public function logger()
    {
        return StaticContainer::get(LoggerInterface::class);
    }

    private function contexts()
    {
        return [
            Notification::CONTEXT_INFO,
            Notification::CONTEXT_ERROR,
            Notification::CONTEXT_SUCCESS,
            Notification::CONTEXT_WARNING,
        ];
    }

    private function priorities()
    {
        return [
            Notification::PRIORITY_MIN,
            Notification::PRIORITY_LOW,
            Notification::PRIORITY_HIGH,
            Notification::PRIORITY_MAX
        ];
    }

    private function types()
    {
        return [
            Notification::TYPE_PERSISTENT,
            Notification::TYPE_TRANSIENT,
            Notification::TYPE_TOAST
        ];
    }

    public function deleteNotification()
    {
        try {
            Piwik::checkUserHasSuperUserAccess();
            $notificationId = trim(Request::fromRequest()->getStringParameter('notificationId', ''));

            $API = new API();
            $API->deleteNotification($notificationId);

            $notificationList[] = 'Notification ' . $notificationId . ' deleted';
            $this->index(0, $notificationList);
        } catch (\Exception $e) {
            echo $e;
        }
    }

    public function editNotification()
    {
        Piwik::checkUserHasSuperUserAccess();

        $notificationId = trim(Request::fromRequest()->getStringParameter('notificationId', 'string'));

        // Fetch the notification details from the database
        $db = Db::get();
        $notification = $db->fetchRow("SELECT * FROM `" . Common::prefixTable('rebel_notifications') . "` WHERE id = ?", [$notificationId]);

        if (empty($notification)) {
            throw new \Exception('Notification not found');
        }


        $view = new View('@RebelNotifications/editNotification');
        $this->setBasicVariablesView($view);
        $view->assign('notification',$notification);
        $view->assign('success', true);
        $view->assign('error', false);
        $view->assign('contexts', $this->contexts());
        $view->assign('types', $this->types());
        $view->assign('priorities', $this->priorities());

        echo $view->render();

    }

        /**
     * Handle the submission of the edit form.
     */
    public function updateNotification()
    {
        Piwik::checkUserHasSuperUserAccess();

        $notificationId = trim(Request::fromRequest()->getStringParameter('id', 'string'));
        $enabled = trim(Request::fromRequest()->getStringParameter('enabled', 'string'));
        $title = trim(Request::fromRequest()->getStringParameter('title', 'string'));
        $message = trim(Request::fromRequest()->getStringParameter('message', 'string'));
        $context = trim(Request::fromRequest()->getStringParameter('context', 'string'));
        $priority = trim(Request::fromRequest()->getStringParameter('priority', 'string'));
        $type = trim(Request::fromRequest()->getStringParameter('type', 'string'));
        $raw = trim(Request::fromRequest()->getStringParameter('raw', 'string'));

        try {
            API::getInstance()->updateNotification($notificationId, $enabled, $title, $message, $context, $priority, $type, $raw);
            $notificationList[] = 'Notification ' . $notificationId . ' updated';
            $this->index(0, $notificationList);
        } catch (\Exception $e) {
            $notificationList[] = 'Notification ' . $notificationId . ' not updated. Error: ' . $e->getMessage();
            $this->index(0, $notificationList);
        }
    }
}