<!doctype html>
<html>
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
  <meta name="_token" content="{{ csrf_token() }}" />
	<title>Sigma - Login</title>
	<link href="{{ URL::asset('css/loginregister.css') }}" rel="stylesheet" type="text/css">
  <link href="{{ URL::asset('css/animate.css') }}" rel="stylesheet" type="text/css">
  <link rel="stylesheet" href="{{ URL::asset('css/jquery.remodal.css') }}">
  <link href='https://fonts.googleapis.com/css?family=Open+Sans:400,300,700' rel='stylesheet' type='text/css'>
  <link href='https://fonts.googleapis.com/css?family=Gloria+Hallelujah' rel='stylesheet' type='text/css'>
</head>
<body>
    <!-- loading gif -->
    <div id="loader">
        <img id="loader_gif" src="{{ asset('img/ajax-loader.gif') }}">
    </div>

    <div id="this-is-a-temp-div-for-showing-login">
    <a id="i-want-to-login" class="white" href="#login_modal">Logga in!</a>

    <section id="intro">
      <video id="background" poster="{{ asset('video/intro.png') }}" autoplay loop muted>
        <source src="{{ asset('video/intro.webm') }}" type="video/webm">
        <source src="{{ asset('video/intro.mp4') }}" type="video/mp4">
      </video>
      <div id="intro-content">
        <div id="logo"></div>
        <h1>Om du vill, kan du!</h1>
        <h2>Det här är en kort och beskrivande text. Styckets syfte är att locka folk till att köpa tjänsten. Väldigt bra.</h2>
        <a id="more-information-please" class="white">Berätta mer.</a>
      </div>
    </section>
    <section id="main">
      <article id="article-1">
        <div class="content-wrapper">
          <h1>Över 100 000 uppgifter!</h1>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
        </div>
      </article>
      <article id="article-2">
        <div class="content-wrapper">
          <div class="function-content">
            <div class="function-description" style="float: right; text-align: right">
              <h1>Lösningar</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
            </div>
            <div class="function-image fadeInLeft" style="float: left;">
              <img src="{{ asset('img/example-1.png') }}" alt="" />
            </div>
            <div class="clear"></div>
          </div>

          <div class="function-content">
            <div class="function-description" style="float: left; text-align: left">
              <h1>Statistik</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
            </div>
            <div class="function-image fadeInRight" style="float: right;">
              <img src="{{ asset('img/example-1.png') }}" alt="" />
            </div>
            <div class="clear"></div>
          </div>

          <div class="function-content">
            <div class="function-description" style="float: right; text-align: right">
              <h1>Formelsamling</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
            </div>
            <div class="function-image fadeInLeft" style="float: left;">
              <img src="{{ asset('img/example-1.png') }}" alt="" />
            </div>
            <div class="clear"></div>
          </div>

          <div class="function-content">
            <div class="function-description" style="float: left; text-align: left">
              <h1>Klassrummet</h1>
              <p>Ett stöd för elever och lärare. Där man som elev enkelt kan se vilka uppgifter man har i läxa och när de ska vara klara. Lorem ipsum pipsum skräpus textus myckus braus tyckerus jagus visstus kristus snittus.</p>
            </div>
            <div class="function-image fadeInRight" style="float: right;">
              <img src="{{ asset('img/example-1.png') }}" alt="" />
            </div>
            <div class="clear"></div>
          </div>

          <div class="function-content">
            <div class="function-description" style="float: right; text-align: right">
              <h1>Genomgångar</h1>
              <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
            </div>
            <div class="function-image fadeInLeft" style="float: left;">
              <img src="{{ asset('img/example-1.png') }}" alt="" />
            </div>
            <div class="clear"></div>
          </div>
        </div>
      </article>
      <article id="article-3">
        <div class="content-wrapper">
          <div class="card">
          <h1>För elever</h1>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
          <a id="student-pay" class="white" href="#">från 99 kr / mån</a>
          </div>
          <div class="card">
          <h1>För lärare</h1>
          <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nulla luctus, tellus a pellentesque volutpat, nibh ligula interdum libero, eu varius est sem non quam. Praesent consectetur turpis sed turpis vestibulum.</p>
          <a id="teacher-pay" class="white" href="#">från 220 kr / mån</a>
          </div>
        </div>
      </article>
      <article id="article-4">
        <div class="content-wrapper">
          <h1>Registrera dig nu!</h1>
            {{ Form::open(array('route' => 'account-create-post', 'method' => 'post', 'id' => 'register_form')) }}

            <div class="form-input">
            {{ Form::text('first_name', Input::old('first_name'), array('placeholder' => 'Förnamn')) }}
            {{ $errors->has('first_name') ? $errors->first('first_name') : '' }}
            <p class="first_name-error errormsg arrow_box"></p>
            </div>

            <div class="form-input">
            {{ Form::text('last_name', Input::old('last_name'), array('placeholder' => 'Efternamn')) }}
            {{ $errors->has('last_name') ? $errors->first('last_name') : '' }}
            <p class="last_name-error errormsg arrow_box"></p>
            </div>

            <div class="form-input">
            {{ Form::email('email', Input::old('email'), array('placeholder' => 'Email')) }}
            {{ $errors->has('email') ? $errors->first('email') : '' }}
            <p class="email-error errormsg arrow_box"></p>
            </div>

            <div class="form-input">
            {{ Form::text('username', Input::old('username'), array('placeholder' => 'Användarnamn')) }}
            {{ $errors->has('username') ? $errors->first('username') : '' }}
            <p class="username-error errormsg arrow_box"></p>
            </div>

            <div class="form-input">
            {{ Form::password('password', array('placeholder' => 'Lösenord')) }}
            {{ $errors->has('password') ? $errors->first('password') : '' }}
            <p class="password-error errormsg arrow_box"></p>
            </div>

            <div class="form-input">
            {{ Form::password('password_again', array('placeholder' => 'Lösenord igen')) }}
            {{ $errors->has('password_again') ? $errors->first('password_again') : '' }}
            <p class="password_again-error errormsg arrow_box"></p>
            </div>

            {{ Form::submit('Skapa konto', ['class' => 'button blue']) }}

            {{ Form::close() }}

          <!-- SHOW ERRORS -->
          <p class="response_message"></p>
        </div>
      </article>
      <article id="article-5">
        <div class="content-wrapper">
          <div class="companies">
            <!--<img src="https://www.sanomautbildning.se/Content/2013/img/header_logo.png" alt="" />-->
          </div>
        </div>
      </article>
    </section>
  </div>

<div class="remodal" id="lrf-modal" data-remodal-id="login_modal">
      <div id="login">
        <h1>Logga in</h1>
      {{ Form::open(array('route' => 'account-sign-in', 'method' => 'post', 'id' => 'login_form')) }}

      <div class="form-input">
      {{ Form::text('username', Input::old('username'), array('placeholder' => 'Användarnamn')) }}
      {{ $errors->has('username') ? $errors->first('username') : '' }}
      <p class="username-error errormsg arrow_box"></p>
      </div>

      <div class="form-input">
      {{ Form::password('password', array('placeholder' => 'Lösenord')) }}
      {{ $errors->has('password') ? $errors->first('password') : '' }}
      <p class="password-error errormsg arrow_box"></p>
      </div>

      {{ Form::submit('Logga in', ['class' => 'button blue']) }}

      {{ Form::close() }}
      <p><a class="show-forgoten-form">Har du glömt lösenordet?</a></p>
      <p>Inget konto? <a class="show-register-form">Registrera dig!</a></p>
      </div>
      <div id="register" style="display: none;">
        <h1>Registrera</h1>
        {{ Form::open(array('route' => 'account-create-post', 'method' => 'post', 'id' => 'modal_register_form')) }}

        <div class="form-input">
        {{ Form::text('first_name', Input::old('first_name'), array('placeholder' => 'Förnamn')) }}
        {{ $errors->has('first_name') ? $errors->first('first_name') : '' }}
        <p class="first_name-error errormsg arrow_box"></p>
        </div>

        <div class="form-input">
        {{ Form::text('last_name', Input::old('last_name'), array('placeholder' => 'Efternamn')) }}
        {{ $errors->has('last_name') ? $errors->first('last_name') : '' }}
        <p class="last_name-error errormsg arrow_box"></p>
        </div>

        <div class="form-input">
        {{ Form::email('email', Input::old('email'), array('placeholder' => 'Email')) }}
        {{ $errors->has('email') ? $errors->first('email') : '' }}
        <p class="email-error errormsg arrow_box"></p>
        </div>

        <div class="form-input">
        {{ Form::text('username', Input::old('username'), array('placeholder' => 'Användarnamn')) }}
        {{ $errors->has('username') ? $errors->first('username') : '' }}
        <p class="username-error errormsg arrow_box"></p>
        </div>

        <div class="form-input">
        {{ Form::password('password', array('placeholder' => 'Lösenord')) }}
        {{ $errors->has('password') ? $errors->first('password') : '' }}
        <p class="password-error errormsg arrow_box"></p>
        </div>

        <div class="form-input">
        {{ Form::password('password_again', array('placeholder' => 'Lösenord igen')) }}
        {{ $errors->has('password_again') ? $errors->first('password_again') : '' }}
        <p class="password_again-error errormsg arrow_box"></p>
        </div>

        {{ Form::submit('Skapa konto', ['class' => 'button blue']) }}

        {{ Form::close() }}

        <p>Har du redan ett konto? <a class="show-login-form">Logga in!</a></p>
      </div>
      <div id="forgot" style="display: none;">
        <h1>Glömt lösenordet?</h1>
      {{ Form::open(array('route' => 'account-create-post', 'method' => 'post', 'id' => 'forgot_form')) }}

      <div class="form-input">
      {{ Form::email('email', Input::old('email'), array('placeholder' => 'Email')) }}
      {{ $errors->has('email') ? $errors->first('email') : '' }}
      <p class="email-error errormsg arrow_box"></p>
      </div>

      {{ Form::submit('Återställ lösenord', ['class' => 'button blue']) }}

      {{ Form::close() }}
      <p>Har du redan ett konto? <a class="show-login-form">Logga in!</a></p>
      </div>
      <div class="clear"></div>
      <p class="response_message"></p>
</div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.2/jquery.min.js"></script>
    <script src="https://ajax.googleapis.com/ajax/libs/jqueryui/1.11.3/jquery-ui.min.js"></script>
    <script src="{{ asset('js/custom_scripts/utility.js') }}"></script>
    <script src="{{ URL::asset('js/wow.min.js') }}"></script>
    <script src="{{ URL::asset('js/jquery.remodal.min.js') }}"></script>
    <script>
    var before = []
    function message(msg)
    {
        var final_msg = ""
        if (typeof msg == 'object')
        {
            // Remove the text foreach item
            for (x in before)
            {
                $('.' + before[x] + '-error').empty()
                before.splice(x, 1)
            }
            $.each(msg, function(key, value) {
                if (before.indexOf(key) == -1)
                    before.push(key)
                $('input[name=' + key + ']').addClass('error')
                $('.' + key + '-error').text(value);
            })
        }
        else
            final_msg = msg

        $('.response_message').html(final_msg)
    }
    $(document).ready(function()
    {
        wow = new WOW(
            {
                boxClass:     'function-image',     // default
                animateClass: 'animated',           // default
                offset:       0,                    // default
                mobile:       true,                 // default
                live:         true                  // default
            })
        wow.init()
        
        $('a#more-information-please').click(function(){
            $('html, body').animate({
            scrollTop: $('section#main').offset().top
            }, 700)
            return false
        })

        $('a#student-pay').click(function(){
            $('html, body').animate({
            scrollTop: $('article#article-4').offset().top
            }, 700)
            return false
        })

        $('a#teacher-pay').click(function(){
            $('html, body').animate({
                scrollTop: $('article#article-4').offset().top
            }, 700);
            return false
        })

        $('.show-register-form').click(function() {
            $('#lrf-modal input').removeClass('error')
            $('.errormsg').text("")
            $('#login').hide()
            $('#forgot').hide()
            $('#register').show()
        })

        $('.show-forgoten-form').click(function() {
            $('#lrf-modal input').removeClass('error')
            $('.errormsg').text("")
            $('#login').hide()
            $('#register').hide()
            $('#forgot').show()
        })

        $('.show-login-form').click(function() {
            $('#lrf-modal input').removeClass('error')
            $('.errormsg').text("")
            $('#register').hide()   
            $('#forgot').hide()
            $('#login').show()
        })

        // Catch submit so we will use the ajax call instead
        $(document).on('submit', '#login_form, #register_form, #forgot_form, #modal_register_form', function(e)
        {
            // Prevent the form from submitting
            e.preventDefault()
            // Prepare data
            var data = $('#' + e.target.id + ' :input').serialize()
            // Prepare the url
            var url = $(this).attr('action')
            // Send data to the server
            var ajax = new Ajax(url, data, 'POST', false)
            var msg = ajax.send()
            if (msg == 'true')
                // Successful login
                location.reload()
            else
            {
                try
                {
                    msg = JSON.parse(msg)
                    message(msg)
                }
                catch(error)
                {
                    console.log('TRUE')
                    // The message is an object and nothing
                    // further is needed here
                }
                //if (msg == 'Ditt konto har blivit skapad, gå till din email för att aktivera det')
                message(msg)
            }

            return false
        })
    })
    </script>
</body>
</html>
