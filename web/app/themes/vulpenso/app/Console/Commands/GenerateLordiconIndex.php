<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GenerateLordiconIndex extends Command
{
    protected $signature = 'lordicon:index';
    protected $description = 'Generate index.json from local Lordicon JSON files';

    public function handle()
    {
        $iconsPath = get_theme_file_path("resources/icons");

        if (!is_dir($iconsPath)) {
            $this->error("Folder not found: {$iconsPath}");
            return Command::FAILURE;
        }

        $files = glob($iconsPath . '/*.json');
        $icons = [];

        foreach ($files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            
            // Skip the index file itself
            if ($filename === 'index') {
                continue;
            }

            // Create a readable name from the filename
            $pretty = preg_replace('/^wired-outline-\d+-/', '', $filename);
            $pretty = str_replace('-', ' ', $pretty);
            $pretty = ucwords($pretty);

            $icons[] = [
                'id'   => $filename,
                'name' => $pretty,
            ];
        }

        // Sort icons by name
        usort($icons, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });

        file_put_contents(
            "{$iconsPath}/index.json",
            json_encode($icons, JSON_PRETTY_PRINT)
        );

        $this->info("Generated index.json with " . count($icons) . " icons.");
        return Command::SUCCESS;
    }
}
