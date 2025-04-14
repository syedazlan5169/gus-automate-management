<?php

require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

// Path to the Excel file
$filePath = 'storage/app/public/template/shipping_instruction_template.xlsx';

try {
    // Load the spreadsheet
    $spreadsheet = IOFactory::load($filePath);
    
    // Get the first worksheet
    $worksheet = $spreadsheet->getActiveSheet();
    
    // Convert to array
    $rows = $worksheet->toArray();
    
    echo "Total rows: " . count($rows) . "\n\n";
    
    // Print the first 20 rows
    echo "First 20 rows:\n";
    for ($i = 0; $i < min(20, count($rows)); $i++) {
        echo "Row " . ($i + 1) . ": ";
        echo implode(", ", $rows[$i]) . "\n";
    }
    
    // Check if there's a second sheet
    echo "\nTotal sheets: " . $spreadsheet->getSheetCount() . "\n";
    
    if ($spreadsheet->getSheetCount() > 1) {
        $containerSheet = $spreadsheet->getSheet(1);
        $containerRows = $containerSheet->toArray();
        
        echo "\nContainer sheet - Total rows: " . count($containerRows) . "\n\n";
        
        // Print the first 10 rows of the container sheet
        echo "First 10 rows of container sheet:\n";
        for ($i = 0; $i < min(10, count($containerRows)); $i++) {
            echo "Row " . ($i + 1) . ": ";
            echo implode(", ", $containerRows[$i]) . "\n";
        }
    } else {
        echo "\nNo container sheet found.\n";
    }
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
} 