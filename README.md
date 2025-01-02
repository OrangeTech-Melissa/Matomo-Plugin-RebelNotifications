# Matomo Rebel Notifications Plugin

With an API-first approach with Rebel Notifications you could easily automate notifications in your Matomo-instances. You could also display many notifications at once, use HTML with notifications, etc.

## Status for tests

![matomo plugin tests](https://github.com/Digitalist-Open-Cloud/Matomo-Plugin-RebelNotifications/actions/workflows/matomo.yaml/badge.svg) ![semgrep oss scan](https://github.com/Digitalist-Open-Cloud/Matomo-Plugin-RebelNotifications/actions/workflows/semgrep.yaml/badge.svg) ![phpcs](https://github.com/Digitalist-Open-Cloud/Matomo-Plugin-RebelNotifications/actions/workflows/phpcs.yaml/badge.svg)

"Test plugin with Matomo" is done with the fork [Matomo GitHub Action Tests](https://github.com/Digitalist-Open-Cloud/Matomo-github-action-tests), which tests the plugin with Integration-tests against the least (8.2) and highest (8.4) supported PHP-version together with the least (5.0.0) and highest available version of Matomo.

## What is Rebel?

Rebel is short for RebelMetrics. RebelMetrics is Matomo on super charged batteries from Digitalist Open Cloud, with pre-configured dashboards, SQL-lab and more. We offer 1 month free trial for organizations and companies. If you are interested, email us at <cloud@digitalist.com> to book a demo.

## Description

With Rebel Notifications you can add notifications to your users, with a range of settings:

- Type of notification
- Use HTML (links, images etc.)
- Priority
- Etc.

Rebel Notifications are using the built in Notifications in Matomo and adds a UI to it to create custom notifications.

## Inspiration

This plugin was inspired by the [Admin Notification](https://plugins.matomo.org/AdminNotification) plugin by [Josh Brule](https://github.com/jbrule).

## Installation

Install the plugin as you normally install any Matomo plugin.

## Usage

After installation, a new menu item is visible in the admin part of Matomo - "Rebel Notifications".
At "Manage" you can add, edit and delete notifications.

When you add or change a notification, nothing is changed until you logout and login. The triggering event for the notifications is `Login.authenticate.successful` - which means that nothing updates until you login.

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
