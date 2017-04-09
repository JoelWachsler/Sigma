<div class="rightWrapper">
<section role="main">
	<nav id="subBar">
		<h2 id="subchapter">
			Text
		</h2>
		<ul>
			<li><a class="active" href="#">Uppgift</a></li>
			<li><a id="goto_solve" href="">Lösning</a></li>
		</ul>
	</nav>
	<article role="task">
		<div class="exercise-circle-wrapper"></div>
		<header>
			<div id="prev" class="task-move"><a class="no-task" id="exercise-previous" href="#"><span>Uppgift ???</span></a></div>
			<div id="next" class="task-move"><a class="no-task" id="exercise-next" href="#"><span>Uppgift ???</span></a></div>
			<span id="exercise-this">
				Uppgift ???
			<div class="exercise-head-wrapper">
        	</div>
			</span>
			<div class="clear"></div>
		</header>
		<div id="exercise-content" style="height: 300px">
		</div>
		<div id="exercise-input">
		</div>
		<div id="task-feedback">
			<h1>Bra jobbat!</h1>
		</div>
	</article>
</section>
</div>
<audio id="sound-right-answer" preload="auto">
    <source src="{{ URL::asset('sounds/right_answer.mp3') }}"></source>
</audio>
<audio id="sound-wrong-answer" preload="auto">
    <source src="{{ URL::asset('sounds/wrong_answer.mp3') }}"></source>
</audio>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script>
    var data_from_server = {{ json_encode($data) }}
    var data

    function update_data(data, server_url_data, current_task)
    {
        // Update data on page
        var title = $('#exercise-this')
        var content = $('#exercise-content')
        answer_data = JSON.parse(data.latex_answer)
        // Clear input-div
        $('#exercise-input').empty()
        // Check if this is input or alternative
        if (data.answer_type == 1)
        {
            answer = JSON.parse(data.latex_answer)
            var data_to_append = '<p>'
            for (x in answer)
                data_to_append += answer[x][0]+'<input type="radio" name="answer" value="'+answer[x][0]+'">'
            data_to_append += '<button id="check-exercise" class="button blue check">Kontrollera svar</button></p>'
            $('#exercise-input').append(data_to_append)
        }
        else
        {
            for (x in data.before_answers)
            {
                
                // Do not append answers if the user has already completed this task
                if (data.completed)
                    data.before_answers[x][1] = ""
                var img_url = data.before_answers[x][2] ? data_from_server['ok_url'] : ""
                $('#exercise-input').append('<p><label>Svar ('+data.before_answers[x][0]+'):</label><input type="text" class="answer" value="'+data.before_answers[x][1]+'" name="'+data.before_answers[x][0]+'"><button id="check-exercise" class="button blue check">Kontrollera svar</button><img width="50px" src="'+img_url+'" /></p>')
            }
        }

        if (data.completed)
            $('#exercise-input').append('<p>UPPGIFT REDAN AVKLARAD!</p>')

        title.empty()
        title.html('Uppgift ' + data.id)
        $('#subchapter').html(data.subchapter)
        content.empty()
        content.height(data.height)
        content.html(data.content)
        $('#goto_solve').attr('href', server_url_data)
        difficulty(data.difficulty)

        // Check if there are any "next" and "prev" urls
        if (typeof data_from_server.next_prev_url.prev_url == 'string')
        {
            $('#exercise-previous span').html('Uppgift ' + data_from_server.next_prev_url.task_id_prev)
            $('#exercise-previous').attr('href', data_from_server.next_prev_url.prev_url)
            $('#exercise-previous').removeClass('no-task')
        }
        if (typeof data_from_server.next_prev_url.next_url == 'string')
        {
            $('#exercise-next span').html('Uppgift ' + data_from_server.next_prev_url.task_id_next)
            $('#exercise-next').attr('href', data_from_server.next_prev_url.next_url)
            $('#exercise-next').removeClass('no-task')
        }

        // Fix images for cross sites
        fix_images($('#exercise-content'))

    }

    function difficulty(diff)
    {
        // Set difficulty
        var container = $('.exercise-circle-wrapper')
        container.empty()
        var filled_circle = '<span class="circle filled"></span>'
        var circle = '<span class="circle"></span>'
        var i = 0
        while(i < diff)
            container.append(filled_circle),
            i++
        while(i < 3)
            container.append(circle),
            i++
    }

    function count(obj)
    {
        var count = 0
        for (x in obj)
            for (y in obj[x])
                count++
        return count
    }

    $(function() 
    {
        // Get data from the current url
        var url_data = QueryString[QueryString.indexOf('task') + 1]
        // Get data from server
        var ajax = new Ajax(data_from_server['data_url'], {task_id: url_data}, 'GET', false)
        data = ajax.send()
        if (typeof data == 'string')
            alert(data)
        update_data(data, data_from_server['solver_url'], url_data)
        var answers_needed = data.answer_type == 1 ? 1 : JSON.parse(data.latex_answer).length
        var correct = {}
        // Add values from before to "correct"
        for (x in data.before_answers)
            if (data.before_answers[x][2])
                if (correct.hasOwnProperty(data.before_answers[x][0]))
                    correct[data.before_answers[x][0]].push(data.before_answers[x][1])
                else
                    correct[data.before_answers[x][0]] = [data.before_answers[x][1]]

        $(document).keyup(function(event)
        {
            // This does not work right now
            //if(event.keyCode == 13)
                //$('#check-exercise').click();
        })

        $(document).on('click', '.check', function()
        {
            var closest_input = $(this).prev('input')
            var answer
            if (data.answer_type == 1)
            {
                var selected = $("input[type='radio'][name='answer']:checked").val()
                answer = [selected, selected]
            }
            else
                answer = [closest_input.attr('name'), closest_input.val()]

            var ajax = new Ajax(data_from_server['answer_url'], {task_id: url_data, answer: answer, before: JSON.stringify(correct)}, 'GET', false)
            var response = ajax.send()
            if (response == true)
            {
                // Add correct image
                $(this).next('img').attr('src', data_from_server['ok_url'])
                // Check if answer exists
                if (correct.hasOwnProperty(answer[0]))
                    correct[answer[0]].push(answer[1])
                else
                    correct[answer[0]] = [answer[1]]
                
                // Check if the user got all answers right
                if (count(correct) == answers_needed)
                {
                    var next_task_check = data_from_server.next_prev_url
                    $('#exercise-input').show(0).delay(500).hide(0)
                    var qoutes_continue = ['Bra jobbat,', 'Bra kämpat,', 'Kämpa på och']
                    var qoutes_finished = ['grattis', 'vilken kämpe du är', ':)', ':D', '^_^']
                    var qoutes          = ['Bra jobbat', 'Bra kämpat', 'Vilken kämpe du är']
                    // Check if the user is done with this homework
                    if (typeof next_task_check.next_url != 'undefined')
                        $('#task-feedback h1').html(qoutes_continue[Math.floor(Math.random() * qoutes_continue.length)]+' gör <a href="'+next_task_check.next_url+'">uppgift '+next_task_check.task_id_next+'</a> också!')
                    else if(data_from_server.group_id != false)
                        $('#task-feedback h1').html('Du är klar med den här läxan, '+qoutes_finished[Math.floor(Math.random() * qoutes_finished.length)]+'!')
                    else
                        $('#task-feedback h1').html(qoutes[Math.floor(Math.random() * qoutes.length)]+'! Klicka <a href="{{ action('HomeController@home') }}">här</a> för att gå tillbaka till uppgiftsmenyn.')

                    $('#task-feedback').css('background', '#5db0c6')
                    $('#task-feedback').css('background-image', 'url({{ URL::asset('img/done.png') }})')
                    $('#task-feedback').animate({bottom:'0px'}, 500)
                    $("#sound-right-answer")[0].play()
                    $('html,body').animate({ scrollTop: 0 }, 'slow')
                }
            }
            else if(response == false)
            {
                // The users answer was not correct when we checked with the server :(
                $('#task-feedback h1').text("Försök igen!")
                $('#task-feedback').css('background', '#FF8673')
                $('#task-feedback').css('background-image', 'url({{ URL::asset('img/cross.png') }})')
                $('#task-feedback').animate({bottom:'0px'}, 500).delay(2000).animate({bottom:'-125px'}, 500)
                $("#sound-wrong-answer")[0].play()
            }
            else
                alert('Någonting gick snett: ' + response)
                    
        })
    })
</script>
