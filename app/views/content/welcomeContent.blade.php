<div class="rightWrapper">
<section role="main">
	<header role="banner">
		<select>
			<option>Matematik 1c</option>
			<option>Matematik 2c</option>
			<option>Matematik 3c</option>
			<option>Matematik 4</option>
			<option>Matematik 5</option>
		</select>
	</header>
	<nav id="subBar">
		<h2>Matematik 1</h2>
		<ul id="chapters">
            @foreach($data['chapters'] as $chapter)
                <li><a href="#" class="chapter" name="{{ $chapter->id }}">{{ $chapter->name }}</a></li>
            @endforeach
		</ul>
	</nav>
	<article id="taskBrowser"></article>
</section>
</div>
<script type="text/javascript" src="//code.jquery.com/jquery-1.11.0.min.js"></script>
<script type="text/javascript" src="//code.jquery.com/jquery-migrate-1.2.1.min.js"></script>
<script src="{{ asset('js/custom_scripts/utility.js') }}"></script>
<script type="text/javascript">
    var current_course      = {{ $data['course'] }}
	var chapter_data_url    = '{{ action('TaskController@get_chapter_data') }}'
    var starred_add_url     = '{{ action('UtilController@toggle_starred') }}'

	function change_chapter(data)
	{
		var container = $('#taskBrowser')
		// First empty the container
		container.empty()
        // Go through all data before adding it
		for(item in data)
		{
			var exc = '<div class="task-item"><a class="walkthrough" href="#"><span>'+data[item].subchapter_data.desc+'</span></a></div>'
            for (item_deep = 0; item_deep < data[item].tasks.length; item_deep++)
			{
				// Go through all data for each task
				var deep_data = data[item].tasks[item_deep]
				var i = 0
				var difficulty = ""
                // Add difficulty to each task
				while(i < deep_data.difficulty)
					difficulty += '<span class="circle filled"></span>',
					i++
				while(i < 3 )
					difficulty +='<span class="circle"></span>',
					i++

				var div_difficulty = '<div class="difficulty">'+difficulty+'</div>'
				div_difficulty += deep_data.starred == 1 ? '<span class="star filled" name="'+deep_data.id+'"></span>' : '<span name="'+deep_data.id+'" class="star"></span>'
				div_difficulty += '<a href="task/'+deep_data.id+'">Uppgift '+(parseInt(item_deep) + 1)+'</a>'
				exc += '<div class="task-item">'+div_difficulty+'</div>'
			}
			container.append('<div class="subchap"><header role="title">'+data[item].subchapter_data.name+'</header><div class="task-scroll">' + exc + '</div></div>')
		}
	}

	function slickTasks() {
		$('.task-scroll').slick({
		  dots: true,
		  infinite: false,
		  speed: 300,
		  responsive: [
		    {
		      breakpoint: 2200,
		      settings: {
	            slidesToShow: 6,
	            slidesToScroll: 6,
	            infinite: true,
	            dots: true
		      }
		    },
		    {
		      breakpoint: 1800,
		      settings: {
	            slidesToShow: 5,
	            slidesToScroll: 5,
	            infinite: true,
	            dots: true
		      }
		    },
		    {
		      breakpoint: 1400,
		      settings: {
	            slidesToShow: 4,
	            slidesToScroll: 4,
	            infinite: true,
	            dots: true
		      }
		    },
		    {
		      breakpoint: 1024,
		      settings: {
	            slidesToShow: 3,
	            slidesToScroll: 3,
	            infinite: true,
	            dots: true
		      }
		    },
		    {
		      breakpoint: 600,
		      settings: {
		        slidesToShow: 2,
		        slidesToScroll: 2
		      }
		    },
		    {
		      breakpoint: 480,
		      settings: {
		        slidesToShow: 1,
		        slidesToScroll: 1
		      }
		    }
		  ]
		})

	    $( ".task-item" ).mouseenter(function()
	    {
	      $(this).find('a').addClass('hover')
	    })

	    $( ".task-item" ).mouseleave(function()
	    {
	      $(this).find('a').removeClass('hover')
	    })

	    $( ".star" ).click(function()
	    {
	        $(this).hasClass('filled') ? $(this).removeClass('filled') : $(this).addClass('filled')
	    })
	}

	$(function(){
        // Starred
        $(document).on('click', '.star', function()
        {
            // Toggle starred
            var ajax = new Ajax(starred_add_url, {task_id: $(this).attr('name'), active: $(this).hasClass('filled')})
            var data = ajax.send()
        })

		$(document).on('click', '.chapter', function()
		{
			// Go through each chapter and remove selected
			$('.chapter').each(function()
			{
				$(this).removeClass('active')
			})

			// Get data for the task the user pressed
			var ajax = new Ajax(chapter_data_url, { chapter_id: $(this).attr('name') }, 'POST', false)
			change_chapter(ajax.send())

			// Add active to the one the user pressed
			$(this).addClass('active')

			slickTasks()

		})
		// Add active to the first chapter when loading
		$('.chapter').first().trigger('click')
	})
</script>
