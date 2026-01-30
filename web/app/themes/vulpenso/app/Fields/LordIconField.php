<?php

namespace App\Fields;

class LordIconField extends \acf_field {
    function __construct() {
        $this->name = 'lordicon';
        $this->label = __('Lordicon Selector', 'acf');
        $this->category = 'choice';
        $this->defaults = [
            'default_value' => '',
            'allow_null' => 0,
            'multiple' => 0,
            'ui' => 1,
            'return_format' => 'value',
        ];
        parent::__construct();
        
        // Add AJAX endpoints
        add_action('wp_ajax_get_lordicon_json', [$this, 'serve_lordicon_json']);
        add_action('wp_ajax_nopriv_get_lordicon_json', [$this, 'serve_lordicon_json']);
        add_action('wp_ajax_get_lordicon_list', [$this, 'serve_lordicon_list']);
        add_action('wp_ajax_nopriv_get_lordicon_list', [$this, 'serve_lordicon_list']);
    }
    
    function serve_lordicon_json() {
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');

        if (!isset($_GET['icon_id'])) {
            http_response_code(400);
            echo json_encode(['error' => 'Missing icon_id parameter']);
            exit;
        }

        $icon_id = sanitize_text_field($_GET['icon_id']);
        $json_path = get_theme_file_path('resources/icons/' . $icon_id . '.json');

        if (file_exists($json_path)) {
            header('Cache-Control: public, max-age=3600');
            echo file_get_contents($json_path);
        } else {
            http_response_code(404);
            echo json_encode(['error' => 'Icon not found', 'path' => $json_path]);
        }
        exit;
    }
    
    function serve_lordicon_list() {
        $icons = $this->get_icons();
        header('Content-Type: application/json');
        header('Access-Control-Allow-Origin: *');
        header('Cache-Control: public, max-age=3600');
        echo json_encode($icons);
        wp_die();
    }

    function render_field($field) {
        $icons = $this->get_icons();
        $value = $field['value'];

        echo '<div class="lordicon-select" data-field-name="' . esc_attr($field['name']) . '">';
        echo '<button type="button" class="lordicon-trigger">';
        if ($value) {
            echo '<lord-icon src="' . admin_url('admin-ajax.php?action=get_lordicon_json&icon_id=' . $value) . '" trigger="hover" colors="#000000,#000000" style="width:32px;height:32px"></lord-icon>';
        } else {
            echo '<span>Selecteer een icoon</span>';
        }
        echo '</button>';

        echo '<div class="lordicon-dropdown">';
        foreach ($icons as $icon) {
            echo '<div class="lordicon-option" data-id="' . esc_attr($icon['id']) . '" data-src="' . admin_url('admin-ajax.php?action=get_lordicon_json&icon_id=' . esc_attr($icon['id'])) . '">
                <lord-icon src="' . admin_url('admin-ajax.php?action=get_lordicon_json&icon_id=' . esc_attr($icon['id'])) . '" trigger="hover" colors="#000000,#000000" style="width:32px;height:32px"></lord-icon>
            </div>';
        }
        echo '</div>';
        echo '<input type="hidden" name="' . esc_attr($field['name']) . '" value="' . esc_attr($value) . '" class="lordicon-value">';
        echo '</div>';
        
        // Debug output
        echo '<script>console.log("PHP Debug - Icons found:", ' . count($icons) . ');</script>';
        echo '<script>console.log("PHP Debug - Current value:", "' . $value . '");</script>';
        echo '<script>console.log("PHP Debug - AJAX URL:", "' . admin_url('admin-ajax.php') . '");</script>';
    }
    
    private function get_icon_json($icon_id) {
        $json_path = get_theme_file_path('resources/icons/' . $icon_id . '.json');
        if (file_exists($json_path)) {
            return file_get_contents($json_path);
        }
        return false;
    }

    function input_admin_enqueue_scripts() {
        // Load lordicon library from CDN
        wp_enqueue_script('lordicon', 'https://cdn.lordicon.com/lordicon.js', [], null, true);
        
        // Load our custom scripts and styles
        wp_enqueue_script('acf-lordicon', get_theme_file_uri('resources/js/acf-lordicon.js'), ['jquery', 'lordicon'], '1.0', true);
        wp_enqueue_style('acf-lordicon', get_theme_file_uri('resources/css/acf-lordicon.css'), [], '1.0');
        
        // Add inline script to initialize lordicon after DOM is ready
        wp_add_inline_script('acf-lordicon', '
            document.addEventListener("DOMContentLoaded", function() {
                // Wait for lordicon library to be available
                setTimeout(function() {
                    if (typeof lottie !== "undefined") {
                        const lordIcons = document.querySelectorAll("lord-icon");
                        lordIcons.forEach(function(icon) {
                            if (icon.src && !icon.hasAttribute("data-initialized")) {
                                lottie.loadAnimation({
                                    container: icon,
                                    renderer: "svg",
                                    loop: true,
                                    autoplay: false,
                                    path: icon.src
                                });
                                icon.setAttribute("data-initialized", "true");
                            }
                        });
                    }
                }, 1000);
            });
        ');
    }

    private function get_icons() {
        $icons_dir = get_theme_file_path('resources/icons/');
        $json_path = $icons_dir . 'index.json';
        
        // Check if we need to rebuild the index
        $should_rebuild = false;
        
        if (!file_exists($json_path)) {
            $should_rebuild = true;
        } else {
            // Check if any .json files are newer than the index
            $index_time = filemtime($json_path);
            $json_files = glob($icons_dir . '*.json');
            
            foreach ($json_files as $file) {
                if (basename($file) !== 'index.json' && filemtime($file) > $index_time) {
                    $should_rebuild = true;
                    break;
                }
            }
        }
        
        // Rebuild index if needed
        if ($should_rebuild) {
            $this->rebuild_index();
        }
        
        // Read and return the index
        if (!file_exists($json_path)) {
            return [];
        }
        
        $content = file_get_contents($json_path);
        if ($content === false) {
            return [];
        }
        
        $icons = json_decode($content, true);
        return is_array($icons) ? $icons : [];
    }
    
    private function rebuild_index() {
        $icons_dir = get_theme_file_path('resources/icons/');
        $json_files = glob($icons_dir . '*.json');
        $icons = [];
        
        foreach ($json_files as $file) {
            $filename = pathinfo($file, PATHINFO_FILENAME);
            
            // Skip the index file itself
            if ($filename === 'index') {
                continue;
            }
            
            // Create a readable name from the filename
            $pretty_name = $this->create_pretty_name($filename);
            
            $icons[] = [
                'id' => $filename,
                'name' => $pretty_name,
            ];
        }
        
        // Sort icons by name
        usort($icons, function($a, $b) {
            return strcmp($a['name'], $b['name']);
        });
        
        // Write the new index
        file_put_contents(
            $icons_dir . 'index.json',
            json_encode($icons, JSON_PRETTY_PRINT)
        );
        
        return $icons;
    }
    
    private function create_pretty_name($filename) {
        // Remove "wired-outline-" prefix and numbers
        $pretty = preg_replace('/^wired-outline-\d+-/', '', $filename);
        
        // Replace hyphens with spaces
        $pretty = str_replace('-', ' ', $pretty);
        
        // Capitalize words
        $pretty = ucwords($pretty);
        
        return $pretty;
    }

    function update_value($value, $post_id, $field) {
        return sanitize_text_field($value);
    }

    function load_value($value, $post_id, $field) {
        return $value;
    }

    function format_value($value, $post_id, $field) {
        if (empty($value)) {
            return '';
        }
        return $value;
    }
}
