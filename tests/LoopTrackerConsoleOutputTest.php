<?php

require_once __DIR__.'/../lib/LoopTracker.php';

/**
 * Class LoopTrackerConsoleOutputTest.
 *
 * This test class outputs the loop counter status messages to the standard
 * out via the echo-function.
 */
class LoopTrackerConsoleOutputTest extends PHPUnit_Framework_TestCase
{
    /**
     * Tests the progress with one time per iteration.
     */
    public function testProgressWithOneItemPerIteration()
    {
        for ($i = 0; $i <= 100; ++$i) {
            LoopTracker::track('One Item per Iteration Loop Tracker PHPUnit Test', 100);
        }

        // There should be only one finish message.
        $this->assertEquals(1, LoopTracker::getDebugNumFinishMessages(), 'There needs to be only one finish message.');
    }
}
