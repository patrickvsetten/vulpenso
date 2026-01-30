jQuery(document).ready(function($) {
    // Lordicon selector functionality
    $(document).on('click', '.acf-field-lordicon .lordicon-trigger', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Trigger clicked');
        
        var $dropdown = $(this).siblings('.lordicon-dropdown');
        var $otherDropdowns = $('.acf-field-lordicon .lordicon-dropdown').not($dropdown);
        
        // Close other dropdowns
        $otherDropdowns.removeClass('open');
        
        // Toggle current dropdown
        $dropdown.toggleClass('open');
    });
    
    // Close dropdown when clicking outside
    $(document).on('click', function(e) {
        if (!$(e.target).closest('.acf-field-lordicon .lordicon-select').length) {
            $('.acf-field-lordicon .lordicon-dropdown').removeClass('open');
        }
    });
    
    // Handle icon selection
    $(document).on('click', '.acf-field-lordicon .lordicon-option', function(e) {
        e.preventDefault();
        e.stopPropagation();
        
        console.log('Icon option clicked');
        
        var $option = $(this);
        var $select = $option.closest('.lordicon-select');
        var $trigger = $select.find('.lordicon-trigger');
        var $input = $select.find('.lordicon-value');
        var iconId = $option.data('id');
        var iconSrc = $option.data('src');
        
        console.log('Selected icon ID:', iconId);
        console.log('Icon src:', iconSrc);
        
        if (iconSrc) {
            // Create new lord-icon element
            var $newIcon = $('<lord-icon>', {
                src: iconSrc,
                colors: 'primary:#000000,secondary:#000000',
                trigger: 'hover',
                style: 'width:32px;height:32px'
            });
            
            console.log('Created new lord-icon element:', $newIcon);
            
            // Replace trigger content with the selected icon
            $trigger.empty().append($newIcon);
        }
        
        // Update hidden input
        $input.val(iconId);
        console.log('Updated input value:', $input.val());
        
        // Close dropdown
        $select.find('.lordicon-dropdown').removeClass('open');
        
        // Trigger change event for ACF
        $input.trigger('change');
        console.log('Triggered change event');
    });
}); 