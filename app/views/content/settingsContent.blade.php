<div class="rightWrapper">
	<section role="main">
		<nav id="subBar">
			<h2>Inställningar</h2>
		</nav>
		<article role="settings">
			<div class="rightSmallerWrapper">
				<div id="account-information" class="fancy fadeIn" style="opacity: 0;">
                    <div id="settings-profile-picture-wrapper">
                    <div id="settings-profile-picture">
                        <img src="{{ Auth::user()->img_id == "" ? URL::asset('img/default-profile.png') : URL::action('UtilController@return_image', Auth::user()->img_id )}}" alt="" />
                    </div>
                    {{ Form::open(array('url' => 'upload_img', 'files' => true, 'id' => 'MyUploadForm')) }}
                    <button id="upload-picture" class="button blue" type="button">Choose picture ...</button>
                    {{ Form::file('img', array('id' => 'imageFile')) }}
                    {{ Form::hidden('profile_pic', 'true') }}
                    {{ Form::close() }}
                    </div>
                    <div id="settings-account-info-wrapper">
                    <form method="post">
                        <input type="hidden" name="_token" value="<?php echo csrf_token(); ?>">
                        <p><label>Förnamn: </label><input type="text" name="first_name" placeholder="Förnamn" value="{{ Auth::user()->first_name }}" /></p>
                        <p><label>Efternamn: </label><input type="text" name="last_name" placeholder="Efternamn" value="{{ Auth::user()->last_name }}" /></p>
                        <p><label>E-mail: </label><input type="text" name="email" placeholder="E-mail" value="{{ Auth::user()->email }}" /></p>
                        <div id="change-password">
                        <p><label>Nytt lösenord: </label><input type="password" name="new_password" placeholder="Nytt lösenord" /></p>
                        <p><label>Nytt lösenord igen: </label><input type="password" name="new_password_again" placeholder="Nytt lösenord igen" /></p>
                        <p><label>Nuvarande lösenord: </label><input type="password" name="old_password" placeholder="Lösenord"></p>
                        </div>
                        <button id="button-update-account-settings" class="button blue">Updatera</button>
                        <!--<a href="#">Close account</a>-->
                    </form>
                    </div>
                    <ul>
                    @if (Session::has('update_email'))
                        <li>{{ Session::get('update_email') }}</li>
                    @endif
                    @if (Session::has('update_first_name'))
                        <li>{{ Session::get('update_first_name') }}</li>
                    @endif
                    @if (Session::has('update_last_name'))
                        <li>{{ Session::get('update_last_name') }}</li>
                    @endif
                    @if (Session::has('update_password'))
                    <?php
                    if (is_array(Session::get('update_password')))
                        foreach (Session::get('update_password') as $item)
                            foreach($item as $item_deep)
                                echo '<li>'.$item_deep.'</li>';
                    else
                        echo '<li>'.Session::get('update_password').'</li>';
                    ?>
                    @endif
                    </ul>
                    <div class="clear"></div>
				</div>
			</div>
		</article>
	</section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
$(function() {
    $("#upload-picture").click(function() {
        $("#imageFile").click();
    })

    $('#imageFile').change(function() {
        $('#MyUploadForm').submit();
    });
});
</script>
