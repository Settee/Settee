var comments = $('#asiderightwrap');
var clickiconcomment = false;
var old;

// Set default items
comments.hide();
$('section').css("margin-right", "0");

function url(){
    if(document.URL.indexOf("post") != -1){
        var id = document.URL.split("/").reverse()[0];
        return document.URL.substring(window.location.protocol.length + 2 + window.location.host.length,document.URL.length - id.length - 5);
    }
}

// Show comments
function showcomment() {
    $('.comments').click(function (e) {
        e.preventDefault();
        if (clickiconcomment == false) {
            comments.show("slide", {
                direction: "right"
            }, 500);
            $('section').animate({
                marginRight: "20em"
            }, 500);

            if ($('.listcomments ul li.addcomment').length != 0) {
                $('.listcomments ul li.addcomment form')[0].action = $('.listcomments ul li.addcomment form')[0].action + this.id;
                old = $('.listcomments ul li.addcomment')[0].outerHTML;
            }
            $('.listcomments ul')[0].innerHTML = "";
            
            console.log(url() + "comments/" + this.id);

            $.getJSON(url() + "comments/" + this.id, function (data) {
                var comment;
                for (var i = 0; i <= data.length - 1; i++) {
                    var comment = comment + '<li><div class="leftcomment"><div class="avatar"><a href="" title=""><img src="' + data[i].avatar + '" alt="avatar" /></a></div></div><div class="rightcomment"><div class="headcomment"><a href="" title="" class="name">' + data[i].surname + '</a> ' + data[i].date + '</div><div class="contentcomment">' + data[i].post + '</div></div></li>';
                };
                $('.listcomments ul')[0].innerHTML = comment + old;
            });
            clickiconcomment = true;
        } else {
            comments.hide("slide", {
                direction: "right"
            }, 500);
            $('section').animate({
                marginRight: "0"
            }, 500);
            clickiconcomment = false;
        }
    });
}
showcomment();

// Hide comments
$('.closecomments').click(function (e) {
    e.preventDefault();
    comments.hide("slide", {
        direction: "right"
    }, 500);
    $('section').animate({
        marginRight: "0"
    }, 500);
    clickiconcomment = false;
});

// Scrollbar custom
$('.postcomments').slimscroll({
    height: $(window).height() - $('header').height() - $('.closecomments').height() - 50,
    width: '100%'
});
if ($(window).width() >= '1025') {
    $('.sidecontainer').slimscroll({
        height: $(window).height() - $('header').height(),
        width: '100%'
    });
} else {
    $('.sidecontainer').slimscroll({
        height: $(window).height(),
        width: '100%'
    });
}
// Support Scrollbar with resize screen
$(window).resize(function () {
    $('.postcomments').css("height", $(window).height() - $('header').height() - $('.closecomments').height() - 5 + "px");
    if ($(window).width() >= '1025') {
        $('aside').show();
        $('.sidecontainer').css("height", $(window).height() - $('header').height() + "px");
        $('aside .slimScrollDiv').css("height", $(window).height() - $('header').height() + "px");
        $('#asideright .slimScrollDiv').css("height", $(window).height() - $('header').height() - $('.closecomments').height() - 5 + "px");
    } else {
        $('aside .slimScrollDiv').css("height", $(window).height());
        $('.sidecontainer').css("height", $(window).height());
        $('#asideright .slimScrollDiv').css("height", $(window).height());
    }
});

// CATEGORIES MENU

var aside = $('aside');
var clickiconaside = false;

// Show categories on mobiles
$('.menubutton').click(function (e) {
    e.preventDefault();
    if (clickiconaside == false) {
        aside.show();
        clickiconaside = true;
    } else {
        aside.hide();
        clickiconaside = false;
    }
});

// Hide categories on mobiles
$('#headercatmobile').click(function (e) {
    e.preventDefault();
    aside.hide();
    clickiconaside = false;
});

// Disable categories links
$('aside a').click(function (e) {
    e.preventDefault();
});


// NAV MENU

var nav = $('nav');
var clickiconnav = false;

// Show nav on mobiles
$('#navhamburger a').click(function (e) {
    e.preventDefault();
    if (clickiconnav == false) {
        nav.show("slide", {
            direction: "left"
        }, 500);
        clickiconnav = true;
    } else {
        nav.hide("slide", {
            direction: "left"
        }, 500);
        clickiconnav = false;
    }
});

// Post loader
$(window).scroll(function () {
    var end = false;
    var baseurl;
    if (document.URL.indexOf("profile") != -1) {
        var url = document.URL.split('/');
        baseurl = "http://" + url[2] + "/" + url[3] + "/";
    } else {
        baseurl = "";
    }
    if ($(window).scrollTop() == $(document).height() - $(window).height()) {
        $.ajax({
            url: baseurl + "post/list/last_id/" + $('article')[$('article').length - 1].id,
            success: function (html) {
                if (html) {
                    $('#feed').append(html);
                    showcomment();
                } else {

                }
            }
        });
    }
});


// Share modalbox
function share_this(url) {
    window.prompt("Copy to clipboard: Ctrl+C, Enter", url);
}

function permalink(){
    $('.permalink a').click(function(e){
        e.preventDefault();
        var url = this.href;
        var html = '<div class="popup"><div class="wrap"><div class="title"><h3>Share this post with this link</h3><div class="closecatmobile"><a href="#" title="Close Categories">&times;</a></div><div class="clearfloat"></div></div><form><input type="text" value="' + url + '" readonly=""></form><a href="#" onClick="share_this(\'' + url + '\');" title="Copy the link" id="copy-button" class="copy">Copy to clipboard</a><div class="clearfloat"></div></div></div>';
        var old = $(".content")[0].innerHTML;
        $(".content")[0].innerHTML = html + old;

        $('.popup .closecatmobile a').click(function(e){
            e.preventDefault();
            $('.popup').remove();
        });
        permalink();
    });
}
permalink();

// Add image form
var VIGET = VIGET || {};
VIGET.fileInputs = function () {
    var $this = $(this),
        $val = $this.val(),
        valArray = $val.split('\\'),
        newVal = valArray[valArray.length - 1],
        $button = $this.siblings('.button'),
        $fakeFile = $this.siblings('.uploadfile');
    if (newVal !== '') {
        $button[0].innerHTML = '<i>Photo Chosen</i>';
    }
};

$(document).ready(function () {
    $('.postfooterleft input[type=file]').bind('change focus click', VIGET.fileInputs);
});