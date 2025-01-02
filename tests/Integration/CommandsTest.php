<?php

namespace Piwik\Plugins\RebelNotifications\tests\Integration;

use Piwik\Tests\Framework\TestCase\ConsoleCommandTestCase;
use Piwik\Tests\Framework\Fixture;

/**
 * @group RebelNotifications
 * @group RebelCommandTest
 * @group Plugins
 */
class CommandsTest extends ConsoleCommandTestCase
{
    public function setUp(): void
    {
        parent::setUp();
        Fixture::createSuperUser();
        Fixture::createWebsite('2025-01-01 00:00:00');
    }

    public function testHelpCreateNotification()
    {
        $code = $this->applicationTester->run([
            'command' => 'rebelnotifications:create-notification',
            '--help' => true,
            '-vvv' => true,
        ]);
        $this->assertEquals(0, $code);
        $this->assertStringContainsStringIgnoringCase(
            "rebelnotifications:create-notification [options]",
            $this->applicationTester->getDisplay()
        );
    }

    public function testCreateNotification()
    {
        $code = $this->applicationTester->run([
            'command' => 'rebelnotifications:create-notification',
            '--raw' => true,
            '--enabled' => true,
            '--title' => 'test title',
            '--message' => 'this is the message',
            '--context' => 'warning',
            '--priority' => '50',
            '--type' => 'persistent'
        ]);

        $this->assertEquals(0, $code);
        $this->assertStringContainsStringIgnoringCase(
            "Created notification: test title",
            $this->applicationTester->getDisplay()
        );
    }
}
