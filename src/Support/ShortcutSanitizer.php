<?php

namespace Nexus633\BootstrapUi\Support;

use Illuminate\Support\Str;
use Exception;
use Illuminate\Support\Facades\Log;

class ShortcutSanitizer
{
    /**
     * Validates the given code against a blacklist to prevent usage of disallowed keywords.
     *
     * The method checks if the provided code is empty, in which case it returns an empty
     * string. If a feature toggle for security is disabled in the configuration, the
     * method returns the code as-is. Otherwise, it retrieves a blacklist of disallowed
     * keywords from the configuration and iterates through each keyword to determine
     * whether the code matches any of them.
     *
     * During the check, the method uses regular expressions to ensure that matches are
     * case-insensitive and account for potential word boundaries, while also handling
     * special cases for keywords containing non-alphanumeric characters.
     *
     * If a match is found within the provided code, a violation handler is invoked and
     * the result is returned. Otherwise, the original code is returned unchanged.
     *
     * @param string|null $code The code to be validated.
     *
     * @return string The validated code, or a processed result if a violation is detected.
     * @throws Exception If a violation is detected and configured to throw exceptions.
     */
    public static function validate(?string $code): string
    {
        if (empty($code)) {
            return '';
        }

        // Feature Toggle prüfen
        if (!config('bootstrap-ui.shortcuts.security.enabled', true)) {
            return $code;
        }

        $normalizedCode = self::normalize($code);

        $blacklist = config('bootstrap-ui.shortcuts.security.blacklist', []);

        foreach ($blacklist as $keyword) {
            // Regex: \b sorgt dafür, dass "evaluation" nicht bei "eval" anschlägt,
            // aber "eval(" oder " eval " schon.
            // 'i' modifier = case insensitive
            $pattern = "/\b" . preg_quote($keyword, '/') . "\b/i";

            // Spezialfall für nicht-Wort-Zeichen (z.B. <script)
            if (!ctype_alnum(str_replace(['<', '>'], '', $keyword))) {
                $pattern = "/" . preg_quote($keyword, '/') . "/i";
            }

            if (preg_match($pattern, $normalizedCode)) {
                return self::handleViolation($keyword, $code);
            }
        }

        return $code;
    }

    /**
     * Normalizes the given input string by resolving various escape sequences.
     *
     * The method processes the input string to convert encoded character sequences
     * into their respective Unicode, hexadecimal, or octal representations. It applies
     * the following transformations in sequence:
     *
     * 1. Unicode escapes (e.g., `\u0061` → `a`): Matches sequences of `\u` followed
     *    by four hexadecimal digits and converts them to their corresponding characters
     *    using `mb_convert_encoding`.
     * 2. Hexadecimal escapes (e.g., `\x61` → `a`): Matches sequences of `\x` followed
     *    by two hexadecimal digits and converts them into characters using `chr` and
     *    `hexdec`.
     * 3. Octal escapes (e.g., `\141` → `a`): Matches sequences of a backslash followed
     *    by one to three octal digits and converts them to characters using `chr` and
     *    `octdec`.
     *
     * These transformations ensure that escaped characters in the input string are
     * normalized to their standard representations.
     *
     * @param string $input The input string containing escape sequences to be normalized.
     *
     * @return string The normalized string with resolved escape sequences.
     */
    protected static function normalize(string $input): string
    {
        // 1. Unicode Escapes auflösen (z.B. \u0061 -> a)
        // Wir suchen nach \u gefolgt von 4 Hex-Ziffern
        $input = preg_replace_callback('/\\\\u([0-9a-fA-F]{4})/', function ($match) {
            return mb_convert_encoding(pack('H*', $match[1]), 'UTF-8', 'UCS-2BE');
        }, $input);

        // 2. Hex Escapes auflösen (z.B. \x61 -> a)
        // Wir suchen nach \x gefolgt von 2 Hex-Ziffern
        $input = preg_replace_callback('/\\\\x([0-9a-fA-F]{2})/', function ($match) {
            return chr(hexdec($match[1]));
        }, $input);

        // 3. Optional: Octal Escapes (selten, aber möglich in JS strings)
        // \141 -> a
        $input = preg_replace_callback('/\\\\([0-7]{1,3})/', function ($match) {
            return chr(octdec($match[1]));
        }, $input);

        return $input;
    }

    /**
     * Handles the detection of security risks by identifying the usage of a forbidden keyword.
     * Depending on the configuration, it may throw an exception or log the warning message.
     *
     * @param string $keyword The forbidden keyword detected in the code.
     * @param string $code The code snippet where the forbidden keyword was identified.
     *
     * @return string Returns a warning message to be displayed.
     * @throws Exception If configured to throw an exception on forbidden keyword detection.
     */
    protected static function handleViolation(string $keyword, string $code): string
    {
        $message = "Security Risk detected in Shortcut Component: usage of forbidden keyword '{$keyword}'.";

        if (config('bootstrap-ui.shortcuts.security.throw_exception', false)) {
            throw new Exception($message . " Code: " . Str::limit($code, 50));
        }

        // In Production: Loggen und Code unschädlich machen (leeren String oder Kommentar zurückgeben)
        Log::warning($message, ['code' => $code]);

        return "console.warn('Blocked unsafe shortcut action.');";
    }
}
