$(document).ready(function() {

    /* FUNCTION DELETE POST */
    var idPostDeleteValidation;
    var urlPostDeleteValidation;
    var comments = {};

    /* DETECT CLICK ON DELETE BUTTON & SAVE DATAS */
        $('.delete').click(function(e){
            e.preventDefault();
            idPostDeleteValidation = this.className.substring(7,this.className.length);
            urlPostDeleteValidation = this.href;
            $('#' + idPostDeleteValidation + ' .postfooter').append('<div class="popvalidation"><a href="" title="Delete this post">Do you really want to delete this post?</a></div>');
            $('#' + idPostDeleteValidation + ' .popvalidation').hide().slideDown();
            settee_delete();
        });

    function settee_delete(){
        /* DELETE POST WITH AJAX */
            $('.popvalidation').click(function(e){
                e.preventDefault();
                $.ajax({
                    url: urlPostDeleteValidation + '/js',
                    success: function(html) {
                        if(html.indexOf("Deleted") != -1){
                            $('#' + idPostDeleteValidation).slideUp();
                        }
                    }
                });
            });
    }

/* HIDE POPUP NOTIFICATION */
setTimeout(function() {
      $('.m-info').fadeOut(1000);
      $('.m-error').fadeOut(1000);
}, 5000);

/* HIDE AND SHOW COMMENT */
$('.comments').hide();
$('.opened').show();
for (var i = $('.comments').length - 1; i >= 0; i--) {
     comments[i] = 'closed';
};
$('.buttonComments').click(function(e){
    e.preventDefault();
    var idPost = $('#' + this.id).parent().parent().parent().parent().children('.comments');
    if(comments[$('.comments').index(idPost)] == 'closed'){
        idPost.slideDown();
        comments[$('.comments').index(idPost)] = 'open';
    }else{
        idPost.slideUp();
        comments[$('.comments').index(idPost)] = 'closed';
    }
});

/* ADD POST FORM */
    var addPost = false;
    var openPost = false;
    $('.addpost a').click(function(e){
        e.preventDefault();
        $.ajax({
            url: this.href + '/html',
            success: function(html) {
                if(!addPost){
                    addPost = true;
                    $('.feed').prepend(html);
                    $('#newpost').parent().hide().slideDown();
                    openPost = true;
                }else{
                    if(!openPost){
                        $('#newpost').parent().slideDown();
                        openPost = true;
                    }else{
                        $('#newpost').parent().slideUp();
                        openPost = false;
                    }
                }
            }
        });
    });

/* LIKE / DISLIKE POST */
$('.like').click(function(e){
    e.preventDefault();
    idToLike = this;
    urlToLike = this.firstChild.href;
    $.ajax({
        url: urlToLike + '/js',
        success: function(html) {
            if(!html){html = '[" "]';}
            var json = $.parseJSON(html);
            if(json[0] == "Liked"){
                $(idToLike).children(1).addClass('actived');
                var content = $(idToLike).children(1).children('span');
                content.text(parseInt(content.text()) + 1);
                var url = urlToLike.split('/');
                url[url.length-2] = "dislike";
                $(idToLike).children(1).get(0).href = url.join('/');
            }
            if(json[0] == "Disliked"){
                $(idToLike).children(1).removeClass('actived');
                var content = $(idToLike).children(1).children('span');
                content.text(parseInt(content.text()) - 1);
                var url = urlToLike.split('/');
                url[url.length-2] = "like";
                $(idToLike).children(1).get(0).href = url.join('/');
            }
        }
    });
});

});