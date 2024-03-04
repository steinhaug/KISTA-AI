<?php

/**
 * openai__parse_vision_completion
 *
 * @param string $string_before Completion for ingredients
 * @return string Rewritten list of ingredients
 */
function openai__parse_vision_completion($string_before) {
    // Split the string into an array of lines
    $lines = explode("\n", $string_before);
    $result = "";

    foreach ($lines as $line) {
        // Check if the line starts with a number followed by a period
        if (preg_match('/^\d+\.\s*(.*)$/', $line, $matches)) {
            // Remove the markdown bold syntax
            $cleanLine = str_replace('**', '', $matches[1]);
            // Append the formatted line to the result string
            $result .= "- " . $cleanLine . "\n";
        }
    }

    return trim($result);
}
