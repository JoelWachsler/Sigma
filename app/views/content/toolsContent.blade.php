<div class="rightWrapper">
	<section role="main">
		<nav id="subBar">
			<h2>Verktyg - Arbetsblad</h2>
			<ul>
				<li><a class="active" href="#">Arbetsblad</a></li>
				<li><a href="{{ URL::route('manager') }}">Manager</a></li>
			</ul>
		</nav>
		<article role="tools">
			<div class="rightSmallerWrapper">
				<div class="part">
					<form>
						<div>
						<h4>Välj kurs</h4>
						<label>1c </label><input type="checkbox" checked="checked">
						<label>2c </label><input type="checkbox">
						<label>3c </label><input type="checkbox" checked="checked">
						<label>4 </label><input type="checkbox">
						<label>5 </label><input type="checkbox">
						</div>
						<div>
						<h4>Välj kapitel</h4>
						<h5>Matematik 1c</h5>
						<label>Tal </label><input type="checkbox">
						<label>Algebra och ekvationer </label><input type="checkbox">
						<label>Procent </label><input type="checkbox">
						<label>Funktioner </label><input type="checkbox">
						<label>Statistik </label><input type="checkbox">
						<label>Sannolikhetslära </label><input type="checkbox">
						<label>Geometri </label><input type="checkbox">
						<h5>Matematik 3c</h5>
						<label>Tal </label><input type="checkbox">
						<label>Algebra och ekvationer </label><input type="checkbox">
						<label>Procent </label><input type="checkbox">
						<label>Funktioner </label><input type="checkbox">
						<label>Statistik </label><input type="checkbox">
						<label>Sannolikhetslära </label><input type="checkbox">
						<label>Geometri </label><input type="checkbox">
						</div>
						<div style="margin-top: 40px;">
						<label>Antal sidor: </label><input type="number" value="1" />
						<button class="button">Skapa arbetsblad</button>
						</div>
					</form>
				</div>
			</div>
		</article>
	</section>
</div>
