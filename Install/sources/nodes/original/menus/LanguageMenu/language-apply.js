$(document).ready(function() {
    $('#app-language-select').find('.lang-selector__item').each(function(i,e){
        $(e).click(function(){
            var tmp = $(this).children('input[name="use-only-language"]').val();
            if(typeof tmp !== 'undefined' && tmp !==null){
                $.ajax({
                    type: "POST",
                    url: window.location.origin+'/settings/',
                    data: {"use-only-language" : tmp}//parseInt(tmp)}
                }).done(function(resp) {
                    console.log(resp);
                    console.log(window.location);
                    if(JSON.parse(resp)==='language-was-successfully-changed'){
                        window.location.href=window.location.href.replace(/#$/, '');
                        window.location.replace(window.location.pathname);
                        window.location = document.URL.replace(/#$/, '');
                        window.location.href=window.location.search.replace(/#$/, '');
                    }
                });
            }
        })
    });
});