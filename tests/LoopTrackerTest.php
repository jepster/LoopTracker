<?php

require_once __DIR__.'/../lib/LoopTracker.php';

/**
 * Class LoopTrackerTest.
 *
 * This test class tests the progress with the LoopTracker class with no
 * output to the console. The benefit is, that there will be displayed only
 * the test result and the result of the assertion-functions.
 */
class LoopTrackerTest extends PHPUnit_Framework_TestCase
{
    /**
     * Config for "each" test.
     */
    public function setUp()
    {
        LoopTracker::setDebugNoOutputToStandardOut(true);
        LoopTracker::setDebugNumFinishMessages(0);
        LoopTracker::setDebugPercentOutputAtTheEnd(0);
    }

    /**
     * Tests the progress of the loop counter with one item per iteration.
     */
    public function testProgressWithOneItemPerIteration()
    {
        for ($i = 0; $i <= 100; ++$i) {
            LoopTracker::track('One Item per Iteration Loop Tracker PHPUnit Test', 100);
        }

        // There should be only one finish message.
        $this->assertEquals(1, LoopTracker::getDebugNumFinishMessages(), 'There needs to be only one finish message.');
    }

    /**
     * Test the progress of the loop counter with multiple items per iteration.
     */
    public function testProgressWithMultipleItemsPerIteration()
    {
        $this->assertEquals(0, LoopTracker::getDebugNumFinishMessages(), "At the beginning of a test, the 'finish
                                                                            messages number' must be 0.");

        for ($i = 0; $i <= 100; $i += 5) {
            LoopTracker::track('Multiple Items per Iteration Loop Tracker PHPUnit Test', 100, 5);
        }

        // There should be only one finish message.
        $this->assertEquals(1, LoopTracker::getDebugNumFinishMessages(), 'There needs to be only one finish message.');
        $this->assertEquals(100, LoopTracker::getDebugPercentOutputAtTheEnd(), 'There must be 100 percent at the end.');
    }
}
