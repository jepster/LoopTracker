<?php

require_once __DIR__.'/../lib/LoopTracker.php';

class LoopTrackerConsoleOutputTest extends PHPUnit_Framework_TestCase
{

    public function testProgressWithOneItemPerIteration()
    {
        for ($i = 0; $i <= 100; ++$i) {
            LoopTracker::track('One Item per Iteration Loop Tracker PHPUnit Test', 100);
        }

        // There should be only one finish message.
        $this->assertEquals(1, LoopTracker::getDebugNumFinishMessages(), 'There needs to be only one finish message.');
    }

}