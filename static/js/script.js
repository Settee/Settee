$(document).ready(function() {

    /* FUNCTION DELETE POST */
    var idPostDeleteValidation;
    var urlPostDeleteValidation;

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
                    url: urlPostDeleteValidation,
                    success: function(html) {
                        if(html == "Deleted"){
                            $('#' + idPostDeleteValidation).slideUp();
                        }
                    }
                });
            });
    }

/* ADD POST FORM */
    var addPost = false;
    var openPost = false;
    $('.addpost a').click(function(e){
        e.preventDefault();
        var html = '<article><div id="newpost"><form><textarea placeholder="Write something" id="addtext"></textarea><table><tbody><tr><td class="upinput"><div class="upload"><label><input type="file" name="upload" id="addimage" multiple=""><i class="fa fa-picture-o"></i> Choose images</label></div><div class="upload"><label><input type="file" id="addfile"><i class="fa fa-file-text"></i> Choose a file</label></div><select name="categories"><option value="1">Test 1</option><option value="2">Test 2</option><option value="3">Test 3</option></select></td><td class="send"><input type="submit" value="Send"></td></tr></tbody></table></form></div></article>';
        if(!addPost){
            addPost = true;
            $('.feed').prepend(html);
            $('#newpost').hide().slideDown();
            openPost = true;
        }else{
            if(!openPost){
                $('#newpost').slideDown();
                openPost = true;
            }else{
                $('#newpost').slideUp();
                openPost = false;
            }
        }
    });
});