$('.pagination').on('click', ' li > a', function (e) {
    e.preventDefault();
    var url = $(this).attr("href");
    if(url){
        LoadPageContentBody(url);
    }
});