$(function(){  
    $("#title_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");       
        }
        else {
            $(this).css("background-color", "#ffffff");
        }
    });
});

$(function(){
    
    $("#authors_input").chosen({
        no_results_text: "Oops, nothing found!"
    });
    
    $("#authors_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#authors_span').addClass("black");
            $('#authors_span').text("Author:");
            $('#authors_text').text($(this).val());
            if(!$('#authors_text').next("br").length){
                $( "<br>" ).insertAfter( "#authors_text" );
            }        
        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#authors_span').removeClass("black");
            $('#authors_span').text("");
            $('#authors_text').text("");
            $('#authors_text').next("br").remove();
            $('#authorsID_auto').val("0");
            $(".default").css("width", "200px");
        }
    });
});

$(function() {
    $('#series_input').autocomplete({
        source: "../db/series.php",
        minLength: 0,
        select: function(event, ui) {
            event.preventDefault(); 
            $(this).val(ui.item.label);
        }
    });
    
    $("#series_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $(this).css("width", "216px");
            $('#volume').css("visibility", "visible;");
            $('#volume').attr("type", "number");
            $('#series_span').addClass("black");
            $('#series_span').text("Series:");
            if($('#volume').val()){
                $('#series_text').text($(this).val()+" (volume: "+$('#volume').val()+")");
            }
            else{
                $('#series_text').text($(this).val());
            } 
            if(!$('#series_text').next("br").length){
                $( "<br>" ).insertAfter( "#series_text" );
            }
        }
        else {
            $(this).css("background-color", "#ffffff");
            $(this).css("width", "295px");
            $('#volume').attr("type", "hidden");
            $('#series_span').removeClass("black");
            $('#series_span').text("");
            $('#series_text').text("");
            $('#series_text').next("br").remove();
        }
    });
    
    $("#volume").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#series_text').css("text-transform", "capitalize");
            $('#series_text').text($('#series_input').val()+" (volume: "+$(this).val()+")");
        }
        else {
            $(this).css("background-color", "#ffffff");
            if($("#series_input").val()){
                $('#series_text').text($('#series_input').val());
            }
        }
    });
});

$(function() {
    $('#genre_input').autocomplete({
        source: "../db/genres.php",
        minLength: 1,
        select: function(event, ui) {
            event.preventDefault();       
            $(this).val(ui.item.label);
        }
    });
    
    $("#genre_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#genre_span').addClass("black");
            $('#genre_span').text("Genre:");
            $('#genre_text').text($(this).val());
            if(!$('#genre_text').next("br").length){
                $( "<br>" ).insertAfter( "#genre_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#genre_span').removeClass("black");
            $('#genre_span').text("");
            $('#genre_text').text("");
            $('#genre_text').next("br").remove();
        }
    });
});

$(function() {
    $("#original_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#original_span').addClass("black");
            $('#original_span').text("Original Title:");
            $('#original_text').text($(this).val());
            if(!$('#original_text').next("br").length){
                $( "<br>" ).insertAfter( "#original_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#original_span').removeClass("black");
            $('#original_span').text("");
            $('#original_text').text("");
            $('#original_text').next("br").remove();
        }
    });
});

$(function() {
    $('#publisher_input').autocomplete({
        source: "../db/publishers.php",
        minLength: 1,
        select: function(event, ui) {
            event.preventDefault(); 
            $(this).val(ui.item.label);
        }
    });
    $("#publisher_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#publisher_span').addClass("black");
            $('#publisher_span').text("Publisher:");
            $('#publisher_text').text($(this).val());
            if(!$('#publisher_text').next("br").length){
                $( "<br>" ).insertAfter( "#publisher_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#publisher_span').removeClass("black");
            $('#publisher_span').text("");
            $('#publisher_text').text("");
            $('#publisher_text').next("br").remove();
        }
    });
});

$(function() {
    $("#publication_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#publication_span').addClass("black");
            $('#publication_span').text("Date of publication:");
            $('#publication_text').text($(this).val());
            if(!$('#publication_text').next("br").length){
                $( "<br>" ).insertAfter( "#publication_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#publication_span').removeClass("black");
            $('#publication_span').text("");
            $('#publication_text').text("");
            $('#publication_text').next("br").remove();
        }
    });
});

$(function() {
    $('#language_input').autocomplete({
        source: "../db/languages.php",
        minLength: 1,
        select: function(event, ui) {
            event.preventDefault(); 
            $(this).val(ui.item.label);
        }
    });
    $("#language_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#language_span').addClass("black");
            $('#language_span').text("Language:");
            $('#language_text').text($(this).val());
            if(!$('#language_text').next("br").length){
                $( "<br>" ).insertAfter( "#language_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#language_span').removeClass("black");
            $('#language_span').text("");
            $('#language_text').text("");
            $('#language_text').next("br").remove();
        }
    });
});

$(function() {
    $("#pages_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#pages_span').addClass("black");
            $('#pages_span').text("Pages:");
            $('#pages_text').text($(this).val());
            if(!$('#pages_text').next("br").length){
                $( "<br>" ).insertAfter( "#pages_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#pages_span').removeClass("black");
            $('#pages_span').text("");
            $('#pages_text').text("");
            $('#pages_text').next("br").remove();
        }
    });
});

$(function() {
    $("#isbn_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#isbn_span').addClass("black");
            $('#isbn_span').text("ISBN:");
            $('#isbn_text').text($(this).val());
            if(!$('#isbn_text').next("br").length){
                $( "<br>" ).insertAfter( "#isbn_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#isbn_span').removeClass("black");
            $('#isbn_span').text("");
            $('#isbn_text').text("");
            $('#isbn_text').next("br").remove();
        }
    });
});

$(function() {
    $("#tags_input").change(function() {       
        if($(this).val()){
            $(this).css("background-color", "#ebebeb");
            $('#tags_span').addClass("black");
            $('#tags_span').text("Tags:");
            $('#tags_text').text($(this).val());
            if(!$('#tags_text').next("br").length){
                $( "<br>" ).insertAfter( "#tags_text" );
            }        }
        else {
            $(this).css("background-color", "#ffffff");
            $('#tags_span').removeClass("black");
            $('#tags_span').text("");
            $('#tags_text').text("");
            $('#tags_text').next("br").remove();
        }
    });
});

$(function(){
    $('#nationality_input').autocomplete({
        source: "../db/nationalities.php",
        minLength: 1,
        select: function(event, ui) {
            event.preventDefault();       
            $(this).val(ui.item.label);
        }
    });
    
    $("#nationality_input").change(function() {       
        if($(this).val()){
            $('#nationality_span').addClass("black");
            $('#nationality_span').text("Nationality:");
            $('#nationality_text').text($(this).val());
            if(!$('#nationality_text').next("br").length){
                $( "<br>" ).insertAfter( "#nationality_text" );
            }        }
        else {
            $('#nationality_span').removeClass("black");
            $('#nationality_span').text("");
            $('#nationality_text').text("");
            $('#nationality_text').next("br").remove();
        }
    });
});

$(function() {
    $("#birth_input").change(function() {       
        if($(this).val()){
            $('#birth_span').addClass("black");
            $('#birth_span').text("Birth date:");
            $('#birth_text').text($(this).val());
            if(!$('#birth_text').next("br").length){
                $( "<br>" ).insertAfter( "#birth_text" );
            }        }
        else {
            $('#birth_span').removeClass("black");
            $('#birth_span').text("");
            $('#birth_text').text("");
            $('#birth_text').next("br").remove();
        }
    });
});