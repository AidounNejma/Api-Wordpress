(function ($) {

    $(document).ready(function () {
        $.ajax({
            url: "http://starwars.local/wp-json/kadence-child/v1/random-memes/",
            type: "GET"
        }).done(function (response) {
            $('#showMeme').append(response.title, response.thumbnail);
        });
    });
})(jQuery);