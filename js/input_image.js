$(function(){
    function readURL(input, image) {
        var validImageTypes = ["image/jpeg", "image/png"];

        if (input.files && input.files[0] && !($.inArray(input.files[0].type, validImageTypes) < 0)) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $(image).attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
        }
        else {
            alert("You can select only images");
        }
    }

    $("#book_image").click(function () {
        $("#book_file").trigger('click');
    });
    $("#author_image").click(function () {
        $("#author_file").trigger('click');
    });
    
    $("#book_file").change(function(){
        readURL(this, "#book_image");
    });   
    $("#author_file").change(function(){
        readURL(this, "#author_image");
    });
    
});