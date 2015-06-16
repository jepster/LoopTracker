<?php

namespace app\controllers;


/**
 * Class ProductCacheController
 * @package app\controllers
 */
class LoopTrackerController
{

    // The start of the process in seconds.
    private $process_start_timestamp;

    // The start of the process in microseconds.
    private $process_start_microtime;

    /**
     * This member counts the iterations of a process. Mostly the process is
     * something which is mostly handled inside a loop. This member counts the
     * number of the loops.
     */
    private $num_iteration = 0;

    /**
     * The elapsed seconds since the process started.
     *
     * @var int
     */
    private $elapsed_seconds = 0;

    /**
     * The number of items which are handled per one iteration. F.e. 100 rows
     * per SQL-query.
     */
    private $num_items_per_iteration = 0;

    /**
     * Holds the expected end time which is rated after the first run happened.
     *
     * @var int
     */
    private $expected_end_time = 0;

    /**
     * Holds the number of seconds which are expected until the process is finished.
     *
     * @var int
     */
    private $expected_seconds_to_finish = 0;

    /**
     * Holds the total number of items which are part of the process.
     *
     * @var int
     */
    private $total_num_items = 0;

    /**
     * Indicates whether the first run happened or not.
     *
     * @var bool
     */
    private $first_run_happened = false;

    function __construct($num_items_per_iteration = 100) {
        $this->num_items_per_iteration = $num_items_per_iteration;
        $this->num_iteration = 0;
        $this->process_start_timestamp = time();
        $this->process_start_microtime = microtime();
    }

    /**
     * Setter for $this->num_items_per_iteration.
     *
     * @param $num_items_per_iteration
     */
    public function setNumItemsPerIteration($num_items_per_iteration){
        $this->num_items_per_iteration = $num_items_per_iteration;
    }


    /**
     * This method increases the number of iterations.
     */
    public function increaseIteration(){
        $this->num_iteration += $this->num_items_per_iteration;
    }

    /**
     * This method is executed after the very first iteration happened
     * to rate the expected finish time by calculating the number of
     * items per one iteration against the total item number and the
     * start- and end-time.
     */
    public function measurementsAfterFirstLoop($total_num_items){
        if ($this->first_run_happened === false) {
            $elapsed_microseconds = microtime() - $this->process_start_microtime;
            $this->expected_seconds_to_finish = intval(number_format($total_num_items / $this->num_items_per_iteration * $elapsed_microseconds, 3));
            $this->first_run_happened = true;
            $this->expected_end_time = strtotime('now') + $this->expected_seconds_to_finish;
        }
    }

    /**
     * This method handles the output in the console.
     *
     * @param $process_name
     * @param $total_num_items
     */
    public function track($process_name,
                            $total_num_items
                            ){

        if($this->num_iteration == 0){
            $this->measurementsAfterFirstLoop($total_num_items);
        }

        $this->increaseIteration();

        if($total_num_items >= $this->num_iteration){

            // time status
            $this->elapsed_seconds = time() - $this->process_start_timestamp;

            // console status output
            echo 'Processing "' . $process_name . '"..' . PHP_EOL;

            $one_percent_total = $total_num_items / 100;
            $percent_current_output = $this->num_iteration / $one_percent_total;

            // console status output
            echo $this->num_iteration . ' of ' . $total_num_items
                . ' / ' . intval($percent_current_output) . '%'
                . ' / elapsed seconds: ' . $this->elapsed_seconds
                . ' / expected seconds: ' . $this->expected_seconds_to_finish
                . ' / expected finish at: ' . date('H:i:s', $this->expected_end_time)
                . PHP_EOL;
        } else {
            $this->outputFinishMessage();
        }

    }

    /**
     * Outputs a message to the console, after the process is finished.
     */
    public function outputFinishMessage(){
        echo 'Finish at: ' . date('H:i:s', time()) . PHP_EOL;
        echo 'Start was: ' . date('H:i:s', $this->process_start_timestamp) . PHP_EOL;
    }

}