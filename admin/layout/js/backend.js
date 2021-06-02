$(document).ready(function() {
    'use strict';

    //hide placeholder in focus
    $('[placeholder]').focus(function(){

        $(this).attr('data-text', $(this).attr('placeholder'));
        $(this).attr('placeholder','');
    }).blur(function(){
        $(this).attr('placeholder', $(this).attr('data-text'));
    });

    //for delete button
    $('.confirm').click(function(){
        return confirm('Are you sure?');

    });
    
});