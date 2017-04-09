<nav id="topBar">
	<div class="leftWrapper">
		<div id="nav-logo">
			<a href="{{ URL::route('home') }}">Sigma</a>
		</div>
	</div>
	<div class="rightWrapper">
		<div id="math-search" class="left">
			<input type="text" placeholder="Sök efter formler, geometriska figurer..." id="tags" />
		</div>
		<div id="nav-items" class="right">
			<ul>
				<li id="notification-button-list-item">
					<a id="notification" class="icon">
						<span id="notification-counter">0</span>
					</a>
					<ul id="notification-list" class="arrow_box">
						<h2>Händelser</h2>
					</ul>
				</li>
				<li id="more-button-list-item">
					<a id="more" class="icon"></a>
					<ul id="more-list" class="arrow_box">
                        @if (Auth::user()->account_type == 3)
						<li><a href="{{ action('AdminController@home') }}">Admin Inställningar</a></li>
                        @endif
						<li><a href="{{ URL::route('settings') }}">Inställningar</a></li>
						<li><a href="{{ URL::route('account-sign-out') }}">Logga ut</a></li>
					</ul>
				</li>
			</ul>
		</div>
		<div class="clear"></div>
	</div>
</nav>

<div id="main-wrapper">
<div id="side-nav-wrapper">
<aside id="sideNav">
	<div id="profile-info">
		<div id="profile-picture">
			<a href="{{ URL::route('profile') }}"><img src="{{ Auth::user()->img_id == "" ? URL::asset('img/default-profile.png') : URL::action('UtilController@return_image', Auth::user()->img_id )}}" alt="" /></a>
		</div>
		<div id="profile-info-wrapper">
		<a id="profile-name" href="{{ URL::route('profile') }}">{{ ucfirst(Auth::user()->username) }}</a>
		<!--<p id="profile-stats">Nivå: 1</p>
    	<div class="course-progress">
    		<div class="course-progressbar-container">
    			<span>30/100</span>
    			<div class="course-progressbar-progress" style="width: 30%;"></div>
    		</div>
    	</div>-->
		</div>
		<div class="clear"></div>
	</div>
	<ul>
		<li id="small-screen-profile"><a class="{{ Request::is('profile*') ? 'active' : '' }}" href="{{ URL::route('profile') }}"><span>Kurser</span></a></li>
		<li><a class="{{ Request::is('courses*') ? 'active' : '' }}" href="{{ URL::route('courses') }}"><span>Kurser</span></a></li>
		<!--<li><a class="{{ Request::is('achievement*') ? 'active' : '' }}" href="{{ URL::route('achievement') }}"><span>Medaljer</span></a></li>-->
		<li><a class="{{ Request::is('classroom*') ? 'active' : '' }}" href="{{ URL::route('classroom') }}"><span>Klassrummet</span></a></li>
		<?php if (Auth::user()->account_type == 3) { ?><li><a class="{{ Request::is('manager*') ? 'active' : '' }}" href="{{ URL::route('manager') }}"><span>Verktyg</span></a></li> <?php } ?>
	</ul>
</aside>
</div>
<div class="remodal" data-remodal-id="frame_holder_modal"><h1 id="formula_title"></h1><div id="frame_holder"></div></div>
