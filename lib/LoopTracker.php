<?php

/**
 * LoopTracker is a simple PHP class to output the status of a loop into the standard
 * out via the "echo"-function.
 *
 * It allows you to track the progress via a very handy call:
 *
 * LoopTracker::track('My loop title', count($total_items_to_iterate));
 *
 * There's also a third parameter to pass, which sets the numbers of items which will be
 * processed by one iteration. Default is "1".
 *
 * Afterwards you get informative messages into your console output as such:
 *
 * Processing "One Item per Iteration Loop Tracker PHPUnit Test"
 * 1 of 100 / 1% / elapsed seconds: 0
 *
 * Processing "One Item per Iteration Loop Tracker PHPUnit Test"
 * 2 of 100 / 2% / elapsed seconds: 0
 *
 * ...
 *
 * Processing "One Item per Iteration Loop Tracker PHPUnit Test"
 * 99 of 100 / 99% / elapsed seconds: 0
 *
 * Processing "One Item per Iteration Loop Tracker PHPUnit Test"
 * 100 of 100 / 100% / elapsed seconds: 0
 * ----------------------
 * Finish at: 16:45:15 - 28.06.15
 * Start was: 16:45:15 - 28.06.15
 * ----------------------
 *
 *
 * @author     Peter Majmesku <p.majmesku@gmail.com>
 * @copyright  2015 Peter Majmesku
 * @license    http://www.opensource.org/licenses/lgpl-license.php LGPL
 *
 * @link       https://github.com/jepster/loop-tracker
 *
 * @version    1.0.0
 */
class LoopTracker
{
    // The start of the process in seconds.
    private static $process_start_timestamp;

    /**
     * This member counts the iterations of a process. Mostly the process is
     * something which is mostly handled inside a loop. This member counts the
     * number of the loops.
     */
    private static $num_iteration = 0;

    /**
     * The elapsed seconds since the process started.
     *
     * @var int
     */
    private static $elapsed_seconds = 0;

    /**
     * The number of items which are handled per one iteration. F.e. 100 rows
     * per SQL-query.
     */
    private static $num_items_per_iteration = 0;

    /**
     * Holds the total number of items which are part of the process.
     *
     * @var int
     */
    private static $total_num_items = 0;

    /**
     * Holds the name of the tracked process. If the tracker name is changed
     * via an new track()-method call, the counting members are reset.
     *
     * @var string
     */
    private static $process_name = '';

    /**
     * This member holds the number of finish messages. It's used in the unit tests.
     * By this number it will be checked, if the finish message was displayed only
     * once. There could be another method-call at the end of the tracked loop, but
     * then this class wouldn't provide such a handy functionality, with just using the
     * track() method.
     *
     * @var int
     */
    private static $debug__num_finish_messages = 0;

    /**
     * This value should always be 100, because for 100 percent. If this value
     * is higher, something is wrong.
     *
     * @var int
     */
    private static $debug__percent_output_at_the_end = 0;

    /**
     * When an unit test runs, we don't need the echo output to standard out.
     *
     * @var bool
     */
    private static $debug__no_output_to_standard_out = false;

    /**
     * @param bool $debug__no_output_to_standard_out
     */
    public static function setDebugNoOutputToStandardOut($debug__no_output_to_standard_out)
    {
        self::$debug__no_output_to_standard_out = $debug__no_output_to_standard_out;
    }

    /**
     * @return int
     */
    public static function getDebugPercentOutputAtTheEnd()
    {
        return self::$debug__percent_output_at_the_end;
    }

    /**
     * @param int $debug__percent_output_at_the_end
     */
    public static function setDebugPercentOutputAtTheEnd($debug__percent_output_at_the_end)
    {
        self::$debug__percent_output_at_the_end = $debug__percent_output_at_the_end;
    }

    /**
     * @return int
     */
    public static function getDebugNumFinishMessages()
    {
        return self::$debug__num_finish_messages;
    }

    /**
     * @param int $debug__num_finish_messages
     */
    public static function setDebugNumFinishMessages($debug__num_finish_messages)
    {
        self::$debug__num_finish_messages = $debug__num_finish_messages;
    }

    /**
     * This method increases the number of iterations.
     */
    private static function increaseIteration()
    {
        self::$num_iteration += self::$num_items_per_iteration;
    }

    /**
     * Sets a new tracking process, if self::$process_name which is passed to the
     * method call differs from the stored value inside the class member.
     *
     * @param $process_name
     * @param $total_num_items
     * @param $num_items_per_iteration
     */
    public static function set_new_tracking_process($process_name, $total_num_items, $num_items_per_iteration)
    {
        self::$process_name = $process_name;
        self::$total_num_items = $total_num_items;
        self::$num_items_per_iteration = $num_items_per_iteration;
        self::$process_start_timestamp = time();
        self::$num_iteration = 0;
    }

    /**
     * This method handles the output in the console.
     *
     * @param $process_name
     * @param $total_num_items
     */
    public static function track($process_name, $total_num_items, $num_items_per_iteration = 1)
    {

        // If the process name changes, an new process tracking begins.
        if ($process_name !== self::$process_name) {
            self::set_new_tracking_process($process_name, $total_num_items, $num_items_per_iteration);
        } else {
            /*
             * It's assumed that a loop starts incrementing the counter "after"
             * the first loop. Like the for-loop starts incrementing after the
             * first execution. F.e.:
             * for ($i = 0; $i <= 100; $i += 5) {
             * "5" is here after the first execution.
             */
            self::increaseIteration();

            // time status
            self::$elapsed_seconds = time() - self::$process_start_timestamp;

            $one_percent_total = $total_num_items / 100;
            $percent_current_output = self::$num_iteration / $one_percent_total;

            self::outputStatusMessage($total_num_items, $percent_current_output);

            /*
             * Send the "finish" message to standard out.
             */
            if (self::$num_iteration >= self::$total_num_items) {
                self::outputFinishMessage();
                static::$debug__num_finish_messages++;
                static::$debug__percent_output_at_the_end = $percent_current_output;
            }
        }
    }
    /**
     * Outputs the status message to the console.
     *
     * @param $total_num_items
     * @param $percent_current_output
     */
    private static function outputStatusMessage($total_num_items, $percent_current_output)
    {
        if (static::$debug__no_output_to_standard_out === false) {
            echo PHP_EOL.'Processing "'.self::$process_name.'"'.PHP_EOL;

            echo self::$num_iteration.' of '.$total_num_items
                .' / '.intval($percent_current_output).'%'
                .' / elapsed seconds: '.self::$elapsed_seconds
                .PHP_EOL;
        }
    }

    /**
     * Outputs a message to the console, after the process is finished.
     */
    private static function outputFinishMessage()
    {
        if (static::$debug__no_output_to_standard_out === false) {
            echo '----------------------'.PHP_EOL;
            echo 'Finish at: '.date('H:i:s - d.m.y', time()).PHP_EOL;
            echo 'Start was: '.date('H:i:s - d.m.y', self::$process_start_timestamp).PHP_EOL;
            echo '----------------------'.PHP_EOL;
        }
    }
}
