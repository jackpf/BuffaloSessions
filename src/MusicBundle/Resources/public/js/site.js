$('#search-link').click(function() {
    $box = $('#search-box');
    if (!$box.is(':visible')) {
        $box.slideDown();
        $box.children().first().focus();
        $(this).addClass('active');
    } else {
        $box.slideUp();
        $(this).removeClass('active');
    }
});