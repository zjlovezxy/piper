<?php
// Loads MyPHP: from ./myphp inside a built app, else from $MYPHP_HOME, which myphp dev and myphp run set,
// else from the checkout myphp.json names, a path from this folder when it is not absolute.
declare(strict_types=1);

// loaded already in a phone's app, whose launcher loads the framework it carries
if (class_exists(\MyPHP\App::class)) {
    return;
}
$framework = is_dir(__DIR__ . '/myphp') ? __DIR__ . '/myphp'
    : (getenv('MYPHP_HOME') ?: json_decode((string)file_get_contents(__DIR__ . '/myphp.json'), true)['framework']);
// ./myphp is a phar:// URL in a built app, which is already absolute: taking it
// for the relative path myphp.json names doubled the prefix and the app died on
// its first require, on every platform.
if (!str_starts_with($framework, 'phar://') && preg_match('#^(/|[A-Za-z]:[\\\\/])#', $framework) !== 1) {
    $framework = __DIR__ . '/' . $framework;
}
require $framework . '/src/autoload.php';
