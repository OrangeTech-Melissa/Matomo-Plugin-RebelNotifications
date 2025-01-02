<?php

/**
 * The Rebel Notification plugin for Matomo.
 *
 * Copyright (C) Digitalist Open Cloud <cloud@digitalist.com>
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program.  If not, see <https://www.gnu.org/licenses/>.
 */

namespace Piwik\Plugins\RebelNotifications\tests\Integration;

use Piwik\Tests\Framework\TestCase\IntegrationTestCase;
use Piwik\Plugins\RebelNotifications\Controller;
use Piwik\Plugins\RebelNotifications\API;
use Piwik\Tests\Framework\Fixture;

/**
 * @group RebelNotifications
 * @group ControllerTest
 * @group Plugins
 */
class ControllerTest extends IntegrationTestCase
{
    /**
     * @var Controller
     */
    private $controller;

    /**
     * @var API
     */
    private $api;

    public function setUp(): void
    {
        parent::setUp();
        Fixture::createWebsite('2025-01-01 00:00:00');
        $this->controller = new Controller();
        $this->api = API::getInstance();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testIndex()
    {
        $result = $this->controller->index();
        $this->assertIsString($result);
        $this->assertStringContainsString('</html>', $result);
        $this->assertStringContainsString('<select name="priority"', $result);
        $this->assertStringContainsString('<option value="toast">toast</option>', $result);
    }

    public function testAddingNotificationAndGetItListed()
    {
        $this->api->insertNotification('1', 'Title to check for', 'bar', 'warning', '25', 'persistent', '0');
        $result = $this->controller->index();
        $this->assertIsString($result);
        $this->assertStringContainsString('Title to check for', $result);
    }

    public function testAddingNotificationAndEdit()
    {
        $this->api->insertNotification('1', 'Title to edit', 'bar', 'warning', '25', 'persistent', '0');
        $result = $this->controller->editNotification('1');
        $this->assertIsString($result);
        $this->assertStringContainsString('Title to edit', $result);
    }

    public function testAddingNotificationAndDelete()
    {
        $this->api->insertNotification('1', 'To delete', 'bar', 'warning', '25', 'persistent', '0');
        $this->api->insertNotification('1', 'To keep', 'bar', 'warning', '25', 'persistent', '0');
        $this->controller->deleteNotification('1');
        $result = $this->api->getAllNotifications();
        $this->assertIsArray($result[0]);
        $results = $result[0];
        $this->assertNotContains('To delete', $results);
        $this->assertContains('To keep', $results);
    }
}
