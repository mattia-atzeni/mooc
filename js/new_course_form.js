$(document).ready( function() {
    
    $('#new_course_form').hide();
    
    $('#new_course_button').click( function(event) {
        event.preventDefault();
        $('#new_course_form').show();
    });
    
    $('#cancel_button').click( function(event) {
        event.preventDefault();
        $('#new_course_form').hide();
    });
    
});
         
     


