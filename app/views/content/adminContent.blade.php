<div class="rightWrapper">
	<section role="main">
		<nav id="subBar">
			<h2>Admin - Settings</h2>
		</nav>
        <div class="rightSmallerWrapper">
            <div id="account-information" class="fancy fadeIn" style="opacity: 0;">
                <table style="border-spacing: 5px;">
                    <tr>
                        <td>Username</td>
                        <td>First name</td>
                        <td>Last name</td>
                        <td>Email</td>
                        <td>Active</td>
                        <td>Account type</td>
                    </tr>
                @foreach($data->accounts as $item)
                    <form method="post" action="{{ action('AdminController@update_user') }}">
                        <input type="hidden" name="id" value="{{ $item->id }}">
                        <input type="hidden" name="_token" value="{{ csrf_token(); }}">
                        <tr class="tr-account-info">
                            <td><input type="text" name="username"      value="{{ $item->username }}"></td>
                            <td><input type="text" name="first_name"    value="{{ $item->first_name }}"></td>
                            <td><input type="text" name="last_name"     value="{{ $item->last_name }}"></td>
                            <td><input type="text" name="email"         value="{{ $item->email }}"></td>
                            <td><input type="text" name="active"        value="{{ $item->active }}"></td>
                            <td><input type="text" name="account_type"  value="{{ $item->account_type }}"></td>
                            <td><input type="submit" value="Update"></td>
                        </tr>
                    </form>
                @endforeach
                </table>
                <div class="clear"></div>
            </div>
        </div>
	</section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script>
var data = {{ json_encode($data) }}
$(function() {
    console.log(data)
})
</script>

