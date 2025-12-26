<?php

namespace Nexus633\BootstrapUi\Services;

use Exception;
use Highlight\Highlighter;

class CodeHighlighterService
{
    /**
     * Hauptfunktion: Verarbeitet Code und gibt alle nötigen Varianten zurück.
     */
    public function process(string $content, string $language = 'blade'): array
    {
        // 1. Bereinigen
        $content = $this->cleanLivewireTags($content);

        // 2. Normalisieren
        $normalizedCode = $this->normalizeCode($content);

        // Standard-Rückgabe initialisieren
        $result = [
            'currentRaw' => $normalizedCode,
            'highlightedCode' => '',
            'jsonPrettyHtml' => null,
            'jsonMinifiedHtml' => null,
            'rawPretty' => null,
            'rawMinified' => null,
            'isJson' => false,
        ];

        // JSON Spezialbehandlung
        if (strtolower($language) === 'json') {
            $decoded = json_decode($normalizedCode);

            if (json_last_error() === JSON_ERROR_NONE) {
                $result['isJson'] = true;

                // A. Minified
                $result['rawMinified'] = json_encode($decoded);
                $result['jsonMinifiedHtml'] = $this->highlight($result['rawMinified'], 'json');

                // B. Pretty
                $result['rawPretty'] = json_encode($decoded, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES | JSON_UNESCAPED_UNICODE);
                $result['jsonPrettyHtml'] = $this->highlight($result['rawPretty'], 'json');

                // Startzustand
                $result['highlightedCode'] = $result['jsonMinifiedHtml'];
                $result['currentRaw'] = $result['rawMinified'];
            } else {
                // Fallback bei invalidem JSON
                $result['highlightedCode'] = $this->highlight($normalizedCode, 'json');
            }
        } else {
            // Andere Sprachen
            $result['highlightedCode'] = $this->highlight($normalizedCode, $language);
        }

        return $result;
    }

    private function cleanLivewireTags(string $content): string
    {
        // Early Return bei leerem Inhalt
        if (empty($content)) {
            return '';
        }

        $content = str_replace([
            '<!--[if BLOCK]><![endif]-->',
            '<!--[if ENDBLOCK]><![endif]-->',
            '@verbatim',
            '@endverbatim'
        ], '', $content);

        return preg_replace('/<!--\[if (BLOCK|ENDBLOCK)]><!\[endif]-->/', '', $content);
    }

    private function highlight(string $code, string $language): string
    {
        $hl = new Highlighter();
        $hl->setAutodetectLanguages(['html', 'php', 'css', 'js', 'json', 'xml', 'bash']);

        try {
            $langToUse = ($language === 'blade') ? 'php' : $language;
            $result = $hl->highlight($langToUse, $code);
            $html = $result->value;

            if ($language === 'blade') {
                $html = $this->enhanceBladeSyntax($html);
            }

            return $html;
        } catch (Exception) {
            return htmlspecialchars($code);
        }
    }

    private function enhanceBladeSyntax(string $html): string
    {
        // Deine Regex-Logik von vorhin...
        $html = preg_replace_callback(
            '/(&lt;\/?x-[\w\-]+)(::)([\w\-.]+)/',
            function ($matches) {
                return '<span class="hljs-bs-prefix">' . $matches[1] . '</span>' .
                    '<span class="hljs-bs-separator">' . $matches[2] . '</span>' .
                    implode('<span class="hljs-bs-separator">.</span>', array_map(fn($p) => '<span class="hljs-bs-name">' . $p . '</span>', explode('.', $matches[3])));
            },
            $html
        );

        $html = preg_replace('/(&lt;\/?x-[\w\-.]+)(?!::)/', '<span class="hljs-bs-prefix">$1</span>', $html);
        $html = preg_replace('/(\$[a-zA-Z_]\w*)/', '<span class="hljs-variable">$1</span>', $html);
        $html = preg_replace('/((?:wire:|:|@)[\w\-.]+)(?==)/', '<span class="hljs-bs-attribute">$1</span>', $html);
        return preg_replace('/(@[a-zA-Z]+)/', '<span class="hljs-keyword">$1</span>', $html);
    }

    private function normalizeCode(string $input): string
    {
        // Deine Normalize Logik...
        $input = str_replace("\t", "    ", $input);
        $lines = explode("\n", str_replace(["\r\n", "\r"], "\n", $input));

        if (count($lines) > 0 && trim($lines[0]) === '') array_shift($lines);
        if (count($lines) > 0 && trim(end($lines)) === '') array_pop($lines);

        if (empty($lines)) return '';

        $lines[0] = ltrim($lines[0]);
        $nonEmpty = array_filter(array_slice($lines, 1), fn($l) => trim($l) !== '');

        if (!empty($nonEmpty)) {
            $minIndent = min(array_map(fn($l) => strspn($l, " \t"), $nonEmpty));
            if ($minIndent > 0) {
                foreach ($lines as $k => $l) {
                    if ($k === 0) continue;
                    if (strlen($l) >= $minIndent) $lines[$k] = substr($l, $minIndent);
                }
            }
        }
        return implode("\n", $lines);
    }
}
