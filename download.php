<?php

require __DIR__.'/bootstrap/helpers.php';

/*
|--------------------------------------------------------------------------
| Entrance
|--------------------------------------------------------------------------
|
| Make sure that the script is executed from the command-line.
|
*/
if (! isset($argv)) {
    shutdown('You must run the script from the command-line.');
}

/*
|--------------------------------------------------------------------------
| Register the handles
|--------------------------------------------------------------------------
|
| Allow the user to stop the abort of the script.
|
*/
message('Registering the handles... ');
declare(ticks=1);
pcntl_signal(SIGTERM, 'fireShutdown');
pcntl_signal(SIGINT, 'fireShutdown');
success('OK!');

/*
|--------------------------------------------------------------------------
| Script Options
|--------------------------------------------------------------------------
|
| Parse the options provided by the user and make sure the required options
| has been provided.
|
*/
foreach ($argv as $arg) {
    if (! preg_match('/--([^=]+)=([^\s]+)/', $arg, $matches)) {
        continue;
    }

    ${$matches[1]} = $matches[2];
}

if (! isset($url) || filter_var($url, FILTER_VALIDATE_URL) === false) {
    shutdown('You must provide the url of the GT Metrix report.');
}

if (! isset($domain) || filter_var($domain, FILTER_VALIDATE_URL) === false) {
    shutdown('You must provide the domain of your website.');
}
$domain = rtrim($domain, '/');

/*
|--------------------------------------------------------------------------
| Send Request
|--------------------------------------------------------------------------
|
| Retrieve the images to optimize from GT Metrix.
|
*/
message(sprintf('Sending the request to %s%s%s...', COLOR_YELLOW, $url, COLOR_END));
$dom = new DOMDocument;
@$dom->loadHTML(file_get_contents($url));
success('OK!');

message('Retrieving the nodes from the report... ');
$path = new DOMXPath($dom);
$nodes = $path->query("//a[@href='/optimize-images.html']/following-sibling::*[1] //li");
success('OK!');

$totalNodes = count($nodes);
if ($totalNodes == 0) {
    shutdown('No image needs optimization.', COLOR_GREEN);
}
line(sprintf('Found %s%d%s node%s!', COLOR_YELLOW, $totalNodes, COLOR_END, $totalNodes > 1 ? 's' : ''));

/*
|--------------------------------------------------------------------------
| Download
|--------------------------------------------------------------------------
|
| Loop through the nodes to download the images.
|
*/
foreach ($nodes as $index => $node) {
    message(PHP_EOL.sprintf('Parsing node #%d... ', $index + 1));
    $links = $path->query('./a', $node);

    if (count($links) !== 2) {
        line('FAILED!', COLOR_RED);
        continue;
    }
    success('OK!');

    list($sourceNode, $newNode) = $links;
    $source = $sourceNode->getAttribute('href');
    $new = 'https://gtmetrix.com'.$newNode->getAttribute('href');

    message(sprintf('Checking source image %s%s%s... ', COLOR_YELLOW, $source, COLOR_END));
    if (strpos($source, $domain) !== 0) {
        line('SKIPPED!', COLOR_YELLOW);
        continue;
    }
    success('OK!');

    $destination = str_replace($domain, __DIR__, $source);
    message(sprintf('Saving the image from %s%s%s to %s%s%s... ', COLOR_YELLOW, $new, COLOR_END, COLOR_YELLOW, $destination, COLOR_END));
    file_put_contents($destination, file_get_contents($new));
    success('OK!');
}
