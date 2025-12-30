<?php

class ErrorHandler
{
    public static function register(): void
    {
        error_reporting(E_ALL);
        ini_set('display_errors', '1');

        set_error_handler(function (int $severity, string $message, string $file, int $line): bool {
            echo "<h2>PHP Error</h2>";
            echo "<p><b>Message:</b> " . htmlspecialchars($message) . "</p>";
            echo "<p><b>File:</b> " . htmlspecialchars($file) . "</p>";
            echo "<p><b>Line:</b> " . (int)$line . "</p>";
            return true; // prevent default PHP handler output
        });

        set_exception_handler(function (Throwable $e): void {
            echo "<h2>Unhandled Exception</h2>";
            echo "<p><b>Message:</b> " . htmlspecialchars($e->getMessage()) . "</p>";
            echo "<p><b>File:</b> " . htmlspecialchars($e->getFile()) . "</p>";
            echo "<p><b>Line:</b> " . (int)$e->getLine() . "</p>";
        });
    }
}
