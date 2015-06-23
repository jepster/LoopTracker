# LoopTracker
This PHP class lets you create an object which will track the status of a loop by echoing it to your console.

This class is designed to track the status of a loop for you. So if you have
a loop like

    while ($while_loop_counter <= $total_num_property_values) {
         doSomething();
    }

you can integrate the LoopTracker here by modifying the code like so:

    $loop_tracker = new LoopTrackerController();
    while ($while_loop_counter <= $total_num_property_values) {
         doSomething();
         $loop_tracker->track("Property Value Fetching from DB", $total_num_property_values);
    }

Afterwards LoopTracker will produce echo's which are useful for the command line. They'll
look like so:

Processing property values..

    18300 of 642249 / 2% / elapsed seconds: 17 / expected seconds: 547 / expected finish at: 13:34:14