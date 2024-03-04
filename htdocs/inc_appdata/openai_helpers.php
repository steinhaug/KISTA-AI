<?php

/**
 * openai__parse_vision_completion
 *
 * @param string $string Completion for ingredients
 * @return string Rewritten list of ingredients
 */
function openai__parse_vision_completion($string) {
    // Split the string into an array of lines
    $lines = explode("\n", $string);
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

/**
 * openai__extract_prompts
 *
 * @param string $string
 * @return void
 */
function openai__extract_prompts($string) {
    // Split the string into lines
    $lines = explode("\n", $string);
    $array = [];

    foreach ($lines as $line) {
        // Use regex to capture the title and description, ignoring the numbering
        if (preg_match('/^\d+\.\s*"([^"]+)"\s*:\s*(.*)$/', $line, $matches)) {
            // Add the title and description as a single entry to the array
            $array[] = '"' . $matches[1] . '": ' . $matches[2];
        }
    }

    return $array;
}