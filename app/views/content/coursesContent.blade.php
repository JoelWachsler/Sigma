<div class="rightWrapper">
<section role="main">
	<nav id="subBar">
		<h2>Kurser</h2>
	</nav>
	<article id="courses">
        @foreach($data->books as $item)
            <?php
            $name = $item->name; // Cannot access data later if we don't declare it here
            ?>
            @if($item->name == '1c')
                <a href="{{ URL::route('home') }}">
            @endif
        	<div id="wrapper-for-course-{{ $item->name }}" class="course-item-wrapper fancy fadeIn">
        	@if($item->active)
        		<div class="course-item" id="course-{{ $item->name }}">
        	@else 
        		<div class="course-item coming-soon" id="course-{{ $item->name }}">
        	@endif
        	<div class="course-wrapper">
        		<div class="course-info">
            		<h3 id="course-title">Matematik {{ $item->name }}</h3>
            		<p id="course-description">Det här är en kort beskrivande text om kursen. Texten beskriver vad kursen innehåller lite översiktligt. Vad bra den här texten är.</p>
            	</div>
            	<div class="course-progress">
                    <div class="stat-circle">
                        <p>Lätta</p>
                        <input class="circleprog knob-e" name="{{ $data->course_data->$name->total_tasks->easy }}" data-max="{{ $data->course_data->$name->total_tasks->easy }}" data-displayPrevious=true value="{{ $data->course_data->$name->completed->easy }}">
                        
                    </div>
                    <div class="stat-circle">
                        <p>Medel</p>
                        <input class="circleprog knob-c" name="{{ $data->course_data->$name->total_tasks->medium }}" data-max="{{ $data->course_data->$name->total_tasks->medium }}" data-displayPrevious=true value="{{ $data->course_data->$name->completed->medium }}">
                    </div>
                    <div class="stat-circle">
                        <p>Svåra</p>
                        <input class="circleprog knob-a" name="{{ $data->course_data->$name->total_tasks->hard }}" data-max="{{ $data->course_data->$name->total_tasks->hard }}" data-displayPrevious=true value="{{ $data->course_data->$name->completed->hard }}">
                    </div>
                    <div class="stat-circle">
                        <p>Klart av kursen</p>
                        <input class="circleprog knob-percent" data-displayPrevious=true value="{{ ($data->course_data->$name->completed->total / ($data->course_data->$name->total_tasks->total == 0 ? 1 : $data->course_data->$name->total_tasks->total)) * 100 }}">
                    </div>
            	</div>
            	<div class="clear"></div>
        		</div>
        	</div>
        	</div>
            @if($item->name == '1c')
                </a>
            @endif
        @endforeach
	</article>
</section>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="{{ asset('js/custom_scripts/utility.js') }}"></script>
<script type="text/javascript">
$(function() {
    var size = 100
    var fontsize = 0.9
    
    var data = {{ json_encode($data) }}
    console.log(data)

    $('.circleprog').each(function () { 
        animateKnob($(this), this.value);
    })

    function animateKnob(circle, current) {
        $({value: 0}).animate({value: current}, {
            duration: 1500,
            easing:'swing',
            step: function() 
            {
                circle.val(Math.ceil(this.value)).trigger('change');
            }
        })
    }

    for (x in data.books)
    {
        $("#wrapper-for-course-"+data.books[x].name+" .knob-percent").knob({
            'min': 0,
            'max': 100,
            'readOnly': true,
            'width': size,
            'height': size,
            'fgColor': '#5db0c6',
            'bgColor': '#f7f7f7',
            'dynamicDraw': true,
            'thickness': 0.15,
            'tickColorizeValues': true,
            'draw': function () { 
                $(this.i).val(this.cv + '%')
                $(this.i).css('font-size', fontsize + 'em');
            }
        })
        
        $("#wrapper-for-course-"+data.books[x].name+" .knob-e").knob(
        {
            'min': 0,
            'readOnly': true,
            'width': size,
            'height': size,
            'fgColor': '#5db0c6',
            'bgColor': '#f7f7f7',
            'dynamicDraw': true,
            'thickness': 0.15,
            'tickColorizeValues': true,
            'draw': function ()
            {
                $(this.i).val(this.cv + '/' + $(this.i).attr('name'))
                $(this.i).css('font-size', fontsize + 'em')
            }
        })

        $("#wrapper-for-course-"+data.books[x].name+" .knob-c").knob({
            'min':0,
            'readOnly': true,
            'width': size,
            'height': size,
            'fgColor': '#5db0c6',
            'bgColor': '#f7f7f7',
            'dynamicDraw': true,
            'thickness': 0.15,
            'tickColorizeValues': true,
            'draw': function () { 
                $(this.i).val(this.cv + '/' + $(this.i).attr('name'))
                $(this.i).css('font-size', fontsize + 'em')
            }
        })

        $("#wrapper-for-course-"+data.books[x].name+" .knob-a").knob({
            'min':0,
            'readOnly': true,
            'width': size,
            'height': size,
            'fgColor': '#5db0c6',
            'bgColor': '#f7f7f7',
            'dynamicDraw': true,
            'thickness': 0.15,
            'tickColorizeValues': true,
            'draw': function () { 
                $(this.i).val(this.cv + '/' + $(this.i).attr('name'))
                $(this.i).css('font-size', fontsize + 'em')
            }
        })
    }
})
</script>
