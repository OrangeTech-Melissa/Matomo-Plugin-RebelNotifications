# Matomo Rebel Notifications Plugin

With an API-first approach with Rebel Notifications you could easily automate notifications in your Matomo-instances. You could also display many notifications at once, use HTML with notifications, etc.

## What is Rebel?

Rebel is short for RebelMetrics. RebelMetrics is Matomo on super charged batteries from Digitalist Open Cloud, with preconfigured dashboards, SQL-lab and more. We offer 1 month free trial for organizations and companies. If you are interested, email us at <cloud@digitalist.com> to book a demo.

## Description

With Rebel Notifications you can add notifications to your users, with a range of settings:

- Type of notification
- Use HTML (links, images etc.)
- Priority
- Etc.

Rebel Notifications are using the built in Notifications in Matomo and adds a UI to it to create custom notifications.

## Inspiration

This plugin was inspired by the [Admin Notification](https://plugins.matomo.org/AdminNotification) plugin by [Josh Brule](https://github.com/jbrule).

## Using RebelNotifications with Matomo API

Examples with curl.

### Create a notification

```sh
curl -X POST "https://MATOMO.URL/index.php" \
     -d "module=API" \
     -d "method=RebelNotifications.insertNotification" \
     -d "enabled=1" \
     -d "title=bar" \
     -d "message=foo is bar" \
     -d "context=warning" \
     -d "priority=25" \
     -d "type=persistent" \
     -d "raw=0" \
     -d "token_auth=A_SECURE_TOKEN" \
     -d "format=JSON"
```

### Edit a notification

```sh
curl -X POST "https://MATOMO.URL/index.php" \
     -d "module=API" \
     -d "method=RebelNotifications.updateNotification" \
     -d "id=24" \
     -d "enabled=1" \
     -d "title=bar" \
     -d "message=Changing the message" \
     -d "context=warning" \
     -d "priority=25" \
     -d "type=persistent" \
     -d "raw=0" \
     -d "token_auth=A_SECURE_TOKEN" \
     -d "format=JSON"
```

### Delete a notification

```sh
curl -X POST "https://MATOMO.URL/index.php" \
     -d "module=API" \
     -d "method=RebelNotifications.deleteNotification" \
     -d "id=24" \
     -d "token_auth=A_SECURE_TOKEN" \
     -d "format=JSON"
```

### List enabled notifications

```sh
curl -X POST "https://MATOMO.URL/index.php" \
     -d "module=API" \
     -d "method=RebelNotifications.getEnabledNotifications" \
     -d "token_auth=A_SECURE_TOKEN" \
     -d "format=JSON"
```

### List disabled notifications

```sh
curl -X POST "https://MATOMO.URL/index.php" \
     -d "module=API" \
     -d "method=RebelNotifications.getDisabledNotifications" \
     -d "token_auth=A_SECURE_TOKEN" \
     -d "format=JSON"
```

### List all notifications

```sh
curl -X POST "https://MATOMO.URL/index.php" \
     -d "module=API" \
     -d "method=RebelNotifications.getAllNotifications" \
     -d "token_auth=A_SECURE_TOKEN" \
     -d "format=JSON"
```

## License

Copyright (C) Digitalist Open Cloud <cloud@digitalist.com>

This program is free software: you can redistribute it and/or modify it under the terms of the GNU General Public License as published by the Free Software Foundation, either version 3 of the License, or (at your option) any later version.

This program is distributed in the hope that it will be useful, but WITHOUT ANY WARRANTY; without even the implied warranty of MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the GNU General Public License for more details.

You should have received a copy of the GNU General Public License along with this program.  If not, see <https://www.gnu.org/licenses/>.
