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
use Piwik\Plugins\RebelNotifications\API;
use Piwik\Tests\Framework\Mock\FakeAccess;
use Piwik\Tests\Framework\Fixture;

//use Piwik\Plugin;

/**
 * @group RebelNotifications
 * @group ApiTest
 * @group Plugins
 */
class ApiTest extends IntegrationTestCase
{
    /**
     * @var API
     */
    private $api;

    public function setUp(): void
    {
        parent::setUp();
        $this->setSuperUser();

        Fixture::createSuperUser();
        Fixture::createWebsite('2025-01-01 00:00:00');
        // Plugin\Manager::getInstance()->loadPlugin('RebelNotifications');
        // try {
        //     Plugin\Manager::getInstance()->activatePlugin('RebelNotifications');
        // } catch (\Exception $e) {
        // }
        $this->api = API::getInstance();
    }

    public function tearDown(): void
    {
        parent::tearDown();
    }

    public function testEnabledNotifications()
    {

        //$this->setSuperUser();
        // Create notification
        $this->api->insertNotification('1', 'foo', 'bar', 'warning', '25', 'persistent', '0');
        $result = $this->api->getEnabledNotifications();

        $this->assertIsArray($result[0]);

        $results = $result[0];

        $this->assertArrayHasKey('type', $results);
        $this->assertArrayHasKey('id', $results);
        $this->assertArrayHasKey('enabled', $results);
        $this->assertArrayHasKey('message', $results);
        $this->assertArrayHasKey('raw', $results);
        $this->assertArrayHasKey('context', $results);
        $this->assertArrayHasKey('title', $results);

        $this->assertEquals('foo', $results["title"]);
        $this->assertEquals('1', $results["enabled"]);
        $this->assertEquals('bar', $results["message"]);
        $this->assertEquals('warning', $results["context"]);
        $this->assertEquals('25', $results["priority"]);
        $this->assertEquals('persistent', $results["type"]);
    }

    public function testDisabledNotifications()
    {
        // Create notification
        $this->api->insertNotification('0', 'foo is bar', 'bar', 'warning', '25', 'persistent', '1');
        $result = $this->api->getDisabledNotifications();

        $this->assertIsArray($result[0]);
        $results = $result[0];

        $this->assertArrayHasKey('type', $results);
        $this->assertArrayHasKey('id', $results);
        $this->assertArrayHasKey('enabled', $results);
        $this->assertArrayHasKey('message', $results);
        $this->assertArrayHasKey('raw', $results);
        $this->assertArrayHasKey('context', $results);
        $this->assertArrayHasKey('title', $results);

        $this->assertEquals('foo is bar', $results["title"]);
        $this->assertEquals('0', $results["enabled"]);
        $this->assertEquals('bar', $results["message"]);
        $this->assertEquals('warning', $results["context"]);
        $this->assertEquals('25', $results["priority"]);
        $this->assertEquals('persistent', $results["type"]);
    }

    public function testAllNotifications()
    {
        // Create notification
        $this->api->insertNotification('0', 'foo is bar', 'bar', 'warning', '25', 'persistent', '1');
        $result = $this->api->getAllNotifications();

        $this->assertIsArray($result[0]);
        $results = $result[0];

        $this->assertArrayHasKey('type', $results);
        $this->assertArrayHasKey('id', $results);
        $this->assertArrayHasKey('enabled', $results);
        $this->assertArrayHasKey('message', $results);
        $this->assertArrayHasKey('raw', $results);
        $this->assertArrayHasKey('context', $results);
        $this->assertArrayHasKey('title', $results);

        $this->assertEquals('foo is bar', $results["title"]);
        $this->assertEquals('0', $results["enabled"]);
        $this->assertEquals('bar', $results["message"]);
        $this->assertEquals('warning', $results["context"]);
        $this->assertEquals('25', $results["priority"]);
        $this->assertEquals('persistent', $results["type"]);
    }


    public function testDeleteNotification()
    {

        $this->api->insertNotification('0', 'foo', 'bar', 'warning', '25', 'persistent', '1');
        $result = $this->api->deleteNotification('1');

        $this->assertIsBool($result);
        $this->assertEquals($result, 'true');
    }

    public function testDeleteNotificationThatDoesNotExist()
    {
        $result = $this->api->deleteNotification('42');

        $this->assertIsBool($result);
        $this->assertEquals($result, 'false');
    }


    protected function setSuperUser()
    {
        FakeAccess::$superUser = true;
    }
}
