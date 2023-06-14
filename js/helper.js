$(document).ready(function() {
    $('body').on('dialogopen', function(event, ui) {
        var $popup = $(event.target);
        if ($popup.prop('id') !== 'action_tag_explain_popup') {
            // That's not the popup we are looking for...
           $popup1 = $('div[aria-describedby=\"action_tag_explain_popup\"]');
            if($popup1){
                $popup=$popup1;
            }
            else{
                return false;
            }
        }
        //定制申明
        // Aux function that checks if text matches the "@HIDECHOICE" string.
        var isDefaultLabelColumn = function() {
            return $(this).text() === '@CALCDATE';
        }

        // Getting @HIDECHOICE row from action tags help table.
        var $default_action_tag = $popup.find('td').filter(isDefaultLabelColumn).parent();
        if ($default_action_tag.length !== 1) {
            return false;
        }

        var tag_name = '@FILETYPE';

        // Create the help text
        var descr = $('<div></div>')
            .addClass('filetype-container')
            .html('When this action tag which value is a MIME file type name is added to a file type field, the upload default file type will be specified by the value. For example: @FILETYPE="image/*", will make the preferred file format for this uploading field is image files. And when enter data on your phone, the upload file button can directly open the album or camera for shooting. Click to view more MIME file types:<a target="_blank" href = "https://www.iana.org/assignments/media-types/media-types.xhtml">MIME file type</a>.');
        // Creating a new action tag row.
        var $new_action_tag = $default_action_tag.clone();
        var $cols = $new_action_tag.children('td');
        var $button = $cols.find('button');
        $cols.eq(1).css('color','green');
        // Column 1: updating button behavior.
        if($button.length){
            $button.attr('onclick', $button.attr('onclick').replace('@CALCDATE', tag_name));
            $button.css({'background':'green','border':'1px solid green'});
        }
        // Columns 2: updating action tag label.
        $cols.filter(isDefaultLabelColumn).text(tag_name);

        // Column 3: updating action tag description.
        $cols.last().html(descr);

        // Placing new action tag.
        $new_action_tag.insertBefore($default_action_tag);
    });
});
