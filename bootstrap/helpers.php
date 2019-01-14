<?php

define("COLOR_END", "\033[0m");
define("COLOR_GREEN", "\033[0;32m");
define("COLOR_RED", "\033[0;31m");
define("COLOR_YELLOW", "\033[1;33m");
define("COLOR_WHITE", "\033[1;37m");

/**
 * Abort the script.
 *
 * @return void
 */
function fireShutdown()
{
    shutdown('The script has been stopped.');
}

/**
 * Print a message on the screen.
 *
 * @param  string  $message
 * @param  string  $color
 * @return void
 */
function message(string $message, string $color = COLOR_WHITE) : void
{
    echo $color.$message.COLOR_END;
}

/**
 * Print a message followed by a line break on the screen.
 *
 * @param  string  $message
 * @param  string  $color
 * @return void
 */
function line(string $message, string $color = COLOR_WHITE) : void
{
    message($message.PHP_EOL, $color);
}

/**
 * Print a success message followed by a line break on the screen.
 *
 * @param  string  $message
 * @param  string  $color
 * @return void
 */
function success(string $message, string $color = COLOR_GREEN) : void
{
    line($message, $color);
}

/**
 * Halt the execution of the script with a message.
 *
 * @param  string  $message
 * @param  string  $color
 * @return void
 */
function shutdown(string $message, string $color = COLOR_RED) : void
{
    line($message, $color);
    exit;
}
