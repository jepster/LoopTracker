#LoopTracker

A simple PHP class to output the status of a loop into the standard out via the "echo"-function.
It allows you to track the progress via a very handy call:

    LoopTracker::track('My loop title', count($total_items_to_iterate));

There's also a third parameter to pass, which sets the numbers of items which will be
processed by one iteration. Default is "1".

So if you process 20 items per iteration, use the following line:

    LoopTracker::track('My loop title', count($total_items_to_iterate), 20);

The full code of the loop looks like so (copied from the PHPUnit testing):

    for ($i = 0; $i <= 100; ++$i) {
        LoopTracker::track('One Item per Iteration Loop Tracker PHPUnit Test', 100);
    }

*Of course you can do different things in your loop, than just track the loop.*

Afterwards you get informative messages into your console output as such:

    Processing "One Item per Iteration Loop Tracker PHPUnit Test"
    1 of 100 / 1% / elapsed seconds: 0
    
    Processing "One Item per Iteration Loop Tracker PHPUnit Test"
    2 of 100 / 2% / elapsed seconds: 0
    
    ...
    
    Processing "One Item per Iteration Loop Tracker PHPUnit Test"
    99 of 100 / 99% / elapsed seconds: 0
    
    Processing "One Item per Iteration Loop Tracker PHPUnit Test"
    100 of 100 / 100% / elapsed seconds: 0
    ----------------------
    Finish at: 16:45:15 - 28.06.15
    Start was: 16:45:15 - 28.06.15
    ----------------------