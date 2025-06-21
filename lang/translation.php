<?php

function getTranslation($key, $lang = 'en') {
    // Load the JSON file
    $json = file_get_contents(__DIR__ . '/translations.json');
    $translations = json_decode($json, true);

    // Check if the requested language exists, else fallback to English
    if (!isset($translations[$lang])) {
        $lang = 'en';
    }

    // Return the translated text or the key if not found
    return $translations[$lang][$key] ?? $key;
}
?>
