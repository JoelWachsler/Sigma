<!DOCTYPE HTML>
<html>
	<head>
		<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <meta name="_token" content="{{ csrf_token() }}" />
		<title>Sigma</title>

        <link rel="icon" type="image/png" href="{{ URL::asset('img/favicon.png') }}">
        <!--[if IE]><link rel="shortcut icon" href="{{ URL::asset('img/favicon.ico') }}"><![endif]-->
        <!--<link rel="shortcut icon" href="{{ URL::asset('img/favicon.ico') }}" type="image/x-icon" />-->

        <!-- Lägg till nya bilder för dessa! -->
        <link rel="apple-touch-icon-precomposed" sizes="144x144" href="{{ URL::asset('img/favicon.png') }}">
        <link rel="apple-touch-icon-precomposed" sizes="114x114" href="{{ URL::asset('img/favicon.png') }}">
        <link rel="apple-touch-icon-precomposed" sizes="72x72" href="{{ URL::asset('img/favicon.png') }}">
        <link rel="apple-touch-icon-precomposed" href="{{ URL::asset('img/favicon.png') }}">

		<link href="{{ URL::asset('css/main.css') }}" rel="stylesheet" type="text/css" />
		<link href="{{ URL::asset('css/slick.css') }}" rel="stylesheet" type="text/css" />
        <link href="{{ URL::asset('css/animate.css') }}" rel="stylesheet" type="text/css" />
        <link rel="stylesheet" href="{{ URL::asset('css/jquery.remodal.css') }}">
		<link rel="stylesheet" href="//code.jquery.com/ui/1.11.1/themes/smoothness/jquery-ui.css">
		<link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
	</head>
	<body>
        <!-- loading gif -->
        <div id="loader">
            <img id="loader_gif" src="{{ asset('img/ajax-loader.gif') }}">
        </div>
		@include('layouts.navigation')
		@yield('content')
        </div>


        <!--
        <div class="remodal" data-remodal-id="first-time">
            <h1>Hej! Välkommen till Sigma!</h1>
            <p></p>
            <input type="text" placeholder="Förnamn" /><input type="text" placeholder="Efternamn" />
            <br>
            <a class="button blue" href="#" style="text-decoration: none;">Spara</a>
        </div>
        -->

<script type="text/x-mathjax-config">
MathJax.Hub.Config({
	messageStyle: "none",
	showMathMenu: false,
	showMathMenuMSIE: false,
	"HTML-CSS": {
		scale: 85,
		//availableFonts: ["STIX","TeX"],
		//preferredFont: "TEX",
		undefinedFamily: "'Open Sans', 'Arial Unicode MS', sans-serif",
		mtextFontInherit: true,
	},
	jax: ["input/TeX","output/HTML-CSS"],
    displayAlign: "left",
	tex2jax: {
		inlineMath: [['$','$'], ['\\(','\\)']],
		processEscapes: true
	}
})


MathJax.Hub.Queue(
	function()
    {
   		$('section[role=main] article[role=solution] table tr').eq(0).show()
	}
)          
</script>
<script src="{{ asset('js/jquery-1.11.1.min.js') }}"></script>
<script src="//code.jquery.com/ui/1.11.1/jquery-ui.js"></script>
<script type="text/javascript" src="https://cdn.mathjax.org/mathjax/latest/MathJax.js?config=TeX-MML-AM_HTMLorMML&amp;locale=sv"></script>
<script type="text/x-mathjax-config;executed=true">MathJax.Hub.Config({ tex2jax: { inlineMath: [['$$','$$'], ['\\(','\\)']]}, "HTML-CSS": { preferredFont: "STIX", width: "container", mtextFontInherit: true } }); MathJax.Hub.Queue(function() { window.view.responsive.fixElementsOverflow(); });</script>
<script src="{{ asset('js/Chart.js') }}" charset="utf-8"></script>
<script src="{{ asset('js/jquery.knob.min.js') }}"></script>
<!--[if IE]><script type="text/javascript" src="{{ URL::asset('js/excanvas.js') }}"></script><![endif]-->
<script src="{{ asset('js/wow.min.js') }}"></script>
<script src="{{ asset('js/slick.min.js') }}"></script>
<script src="{{ asset('js/jquery.remodal.min.js') }}"></script>
<script src="{{ asset('js/custom_scripts/utility.js') }}"></script>
<script>
var url = '{{ URL::route('formula') }}'
var url_image   = '{{ URL::action('UtilController@return_image','') }}'
</script>
<script src="{{ asset('js/custom_scripts/formula_search.js') }}"></script>
<script>
var get_alert_url   = '{{ action('UtilController@get_alerts') }}'
var read_alert_url  = '{{ action('UtilController@read_alert') }}'
// This is needed for ajax posts to work
$.ajaxSetup({
    headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
})

$(document).ready(function() {
    wow = new WOW(
    {
        boxClass:     'fancy',               // default
        animateClass: 'animated',           // default
        offset:       0,                   // default
        mobile:       true,               // default
        live:         true,              // default
        callback:     function(box) {
            //console.log($(box).attr('id'));
        }
    }
    )
    wow.init();

    //var options = {};
    //$('[data-remodal-id=first-time]').remodal(options).open();

    // Get alert-notifications
    var ajax = new Ajax(get_alert_url, [], 'GET', false)
    var data = ajax.send()
    // Update notification counter
    $('#notification-counter').html(data.unread)
    // Go through each alert and append it to the alert window
    for(var x in data.messages)
    {
        if (x > 4) // Get the 5 first messages
            break
        var data_to_append  = '<li>'
        data_to_append      += '<a href="'+ data.messages[x].link_to_alert +'" class="'+ data.messages[x].type +'">'
        data_to_append      += '<span class="icon">'
        data_to_append      += '<img src="{{ asset('img/happy.png') }}" alt="" />'
        data_to_append      += '</span>'
        data_to_append      += '<p class="description">'+ data.messages[x].message +'</p>'
        data_to_append      += '</a>'
        data_to_append      += '</li>'
        $('#notification-list').append(data_to_append)
    }
        
    $('#more').click(function()
    {
        $('#notification-list').hide()
        if($('#more-list').is(":visible"))
            $('#more-list').hide();
        else
            $('#more-list').slideToggle(700,"easeOutBounce")
    })

    $('#notification').click(function()
    {
        $('#more-list').hide()
        if($('#notification-list').is(":visible"))
            $('#notification-list').hide()
        else
        {
            $('#notification-list').slideToggle(700,"easeOutBounce")
            // Set messages to `read`
            var ajax = new Ajax(read_alert_url, [], 'POST', false)
            var res  = ajax.send()
            // Set unread to zero
            $('#notification-counter').html(0)
        }
    })

    $("html").mouseup(function(e)
    {
        var more = $("#more"); 
        var morelist = $("#more-list"); 
        var noti = $("#notification"); 
        var notilist = $("#notification-list"); 

        if(e.target.id != morelist.attr('id') && e.target.id != more.attr('id') && !morelist.has(e.target).length)
        {
            morelist.hide()
        }

        if(e.target.id != notilist.attr('id') && e.target.id != noti.attr('id') && !morelist.has(e.target).length)
        {
            notilist.hide()
        }
    })
})

var currentTableRow = 0;
$(function() {
	$( document ).tooltip({
		content: function () {
            return $(this).prop('title')
        }
	})

	if($('.prompt') != null)
    {
		$('.prompt').delay(4000).fadeOut(1000, function()
        {
			$('.prompt').remove()
		})
	}

	if($('section[role=main] article[role=solution] table').length)
    {
		currentTableRow = 1
		$('section[role=main] article[role=solution] table tr:not(first-child)').hide()
	}

    $('#next-step').click(function()
    {
        if(currentTableRow < $('section[role=main] article[role=solution] table tr').length) {
            $('section[role=main] article[role=solution] table tr').eq(currentTableRow).show();
            currentTableRow++;
        }
        if(currentTableRow == $('section[role=main] article[role=solution] table tr').length) {
            $(this).hide();
            $('#all-steps').hide();
        }
        resize()
        $("html, body").animate({ scrollTop: $(document).height() }, "slow")
    })

    $('#all-steps').click(function()
    {
        $('section[role=main] article[role=solution] table tr').show()
        $(this).hide()
        $('#next-step').hide()
        resize()
    })

    $('.top-level-link').mouseenter(function()
    {
        if($(this).attr('id') != null)
        {
            $(this).addClass('topnav-active')
            var id = $(this).attr('id').split('-')[1]
            $('#submenu-' + id).css('display', 'block')
            $('#submenu-' + id).css('left', - ($('#submenu-' + id).outerWidth() - $(this).outerWidth()))
        }
    })

    $('.top-level-link').mouseleave(function()
    {
        if($(this).attr('id') != null)
        {
            $(this).removeClass('topnav-active')
            var id = $(this).attr('id').split('-')[1]
            $('#submenu-' + id).css('display', 'none')
        }
    })

    $('.submenu').mouseenter(function()
    {
        if($(this).attr('id') != null)
        {
            var id = $(this).attr('id').split('-')[1]
            $('#menu-' + id).addClass('topnav-active')
            $(this).css('display', 'block')
        }
    })

    $('.submenu').mouseleave(function()
    {
        if($(this).attr('id') != null)
        {
            var id = $(this).attr('id').split('-')[1]
            $('#menu-' + id).removeClass('topnav-active')
            $(this).css('display', 'none')
        }
    })

    $('#change').click(function()
    {
        $('#picture-upload').trigger('click')
    })
})

</script>
	</body>
</html>
