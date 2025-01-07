<?php

// The root directory where your Blade view files are located
$directory = 'app/';

// The JSON output file for storing all extracted strings
$jsonOutputFile = 'lang/en_logic.json';

// Array to collect all extracted strings
$translations = [];

// Array of folders to exclude (relative to the views folder)
$excludedFolders = [

];

// Normalize excluded folder paths
$excludedFolders = array_map('realpath', $excludedFolders);

// Recursively iterate through all files in the views directory
$iterator = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($directory));
foreach ($iterator as $file) {
    // Skip directories and non-Blade files
    if ($file->isDir() || $file->getExtension() !== 'php' ) {
        continue;
    }

    // Skip files in excluded folders
    $filePath = realpath($file->getPathname());
    $isExcluded = false;
    foreach ($excludedFolders as $excludedFolder) {
        if (strpos($filePath, $excludedFolder) === 0) {
            $isExcluded = true;
            break;
        }
    }

    // If the file is in an excluded folder, skip processing
    if ($isExcluded) {
        continue;
    }

    // Read the content of the Blade file
    $content = file_get_contents($file->getPathname());

    // Regular expression to find strings in {{ __('...') }} or {{ trans('...') }}
    $pattern = '/\s*(?:__|trans)\s*\(\s*\'(.*?)\'\s*\)\s*/';

    // Perform the regex match
    preg_match_all($pattern, $content, $matches);

    // If matches are found, add them to the translations array
    foreach ($matches[1] as $string) {
        $translations[$string] = $string; // Add to array as key-value pair
    }

    // Display a message indicating that the file has been processed
    echo "Processed: " . $file->getPathname() . "\n";
}

// Save the collected translations to a JSON file
file_put_contents($jsonOutputFile, json_encode($translations, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE));

// Display a success message
echo "Extraction completed.\n";
echo "Extracted translations saved to: $jsonOutputFile\n";
