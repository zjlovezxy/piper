<?php
// Patches the MyPHP checkout the app builds against, for what the framework's
// release the workflow pins still gets wrong.
//
// Run: php ci/patch-framework.php ../myphp   (the framework dir, from the repo root)
//
// The fixes go away when a newer framework is pinned; this script then says so
// by name instead of patching nothing.
declare(strict_types=1);

$dir = $argv[1] ?? '../myphp';
if (!is_dir($dir)) {
    fwrite(STDERR, "patch-framework: no framework at $dir\n");
    exit(1);
}

/**
 * Replaces $old with $new in $file (once), tolerating it already being there.
 */
function patch(string $dir, string $file, string $old, string $new): void
{
    $path = "$dir/$file";
    $source = @file_get_contents($path);
    if ($source === false) {
        throw new RuntimeException("cannot read $path");
    }
    if (str_contains($source, $new)) {
        echo "$file: already fixed\n";
        return;
    }
    if (!str_contains($source, $old)) {
        throw new RuntimeException("$file moved: the framework changed, drop this patch");
    }
    file_put_contents($path, str_replace($old, $new, $source));
    echo "$file: patched\n";
}

// Linux's App::region() hands the `char*` of libc's nl_langinfo_l to a string
// cast; the micro runtime a built app carries (PHP 8.5) refuses that cast with
// "Object of class FFI\CData could not be converted to string", so every launch
// with LC_TIME set died before the first window. FFI::string() is the
// documented way to read a `char*`, and PHP 8.4 takes it too.
patch(
    $dir,
    'src/Platform/Linux/App.php',
    '$fmt = (string)$libc->nl_langinfo_l(0x2002A, $loc);',
    '$fmt = \FFI::string($libc->nl_langinfo_l(0x2002A, $loc));',
);
