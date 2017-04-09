<link href="{{ URL::asset('css/datepicker.css') }}" rel="stylesheet" type="text/css" />
<div class="rightWrapper">
<section role="main">
	<nav id="subBar">
		<h2>Klassrummet</h2>
        <ul id="classes"></ul>
	</nav>
	<article role="classroom">
        <div id="no-classroom" style="display: none;">
            <h4>Du är inte med i något klassrum!</h4>
            <input type="text" placeholder="Klassrummets kod" /><button class="button blue">Gå med i klassrummet</button>
        </div>
        <div id="my-classroom">
            <div id="classroom-info" class="fancy fadeIn">
                <h5>Beskrivning</h5>
                <p id="class"></p>
                <p id="school"></p>
                <p id="teacher"></p>
                <h5>Elever</h5>
                <ul id="users-in-classroom"></ul>
            </div>
            <div id="classroom-feed"></div>
        </div>
	</article>
</section>

<!-- STATISTIK START -->
<!--<section role="main">
    <nav id="subBar">
        <h2>Läxa 1</h2>
    </nav>
    <article role="homeworkstat">
        <div id="class-stat-table">
            <table>
                <thead>
                    <tr>
                        <th>Uppgift</th>
                        <th>Andelen klara</th>
                        <th>Antalet felaktiga svar</th>
                        <th>Fel svar (1)</th>
                        <th>Fel svar (2)</th>
                        <th>Fel svar (3)</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>1</td>
                        <td>22 (84 %)</td>
                        <td>9000</td>
                        <td>x^2 (45)</td>
                        <td>x^2 + 1 (30)</td>
                        <td>x^3 (25)</td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div id="class-stat-pie">
            <canvas id="piechart" width="200" height="200"></canvas>
        </div>
    </article>
</section>-->

</div>
<div class="remodal" id="addtasksmenu" id="lrf-modal" data-remodal-id="addtasks_modal">
    <h1>Lägg till uppgifter</h1>
    <select id="course">
        <option>Kurs</option>
        @foreach($data->books as $item)
            {{ '<option value="'.$item->id.'">'.$item->name.'</option>' }}
        @endforeach
    </select>
    <select id="chapter">
        <option>Kapitel</option>
    </select>
    <select id="subchapter">
        <option>Delkapitel</option>
    </select>
    <select id="task">
        <option>Uppgifter</option>
    </select>
    <button class="button blue" id="add-task-to-group" style="display:block; margin: 20px auto;">Lägg till</button>
    <p>Dina valda uppgifter</p>
    <ul id="four"></ul>
</div>
<div class="remodal" id="addstudent" id="lrf-modal" data-remodal-id="addstudent_modal">
    <h1>Skicka förfrågan</h1>
    <input type="text" id="search-student" placeholder="Elevens användarnamn/email" /><button class="button blue" id="add-student">Lägg till</button>
</div>
<div class="remodal" id="addstudent" id="lrf-modal" data-remodal-id="createclassroom_modal">
    <h1>Skapa klassrum</h1>
    <input type="text" id="create-classroom-school-name" placeholder="Skola" />
    <input type="text" id="create-classroom-class-name" placeholder="Klassens namn" />
    <button class="button blue" id="add-classroom">Skapa klassrum</button>
</div>

<!-- STAT MODAL START -->
<div class="remodal" id="stat" data-remodal-id="stat_modal">
    <h1>Statistik: <span id="homework_number"></span></h1>
    <article role="homeworkstat">
        <div id="class-stat-pie">
            <canvas id="piechart" width="200" height="200"></canvas>
        </div>
        <div id="class-stat-table">
            <table>
                <thead>
                    <tr>
                        <th>Uppgift</th>
                        <th>Andelen klara</th>
                        <th>Antalet felaktiga svar</th>
                        <th>Fel svar (1)</th>
                        <th>Fel svar (2)</th>
                        <th>Fel svar (3)</th>
                    </tr>
                </thead>
                <tbody id="stat_tbody">
                </tbody>
            </table>
        </div>
    </article>
</div>
<!-- STAT MODAL END -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.0/jquery.min.js"></script>
<script src="{{ URL::asset('js/jquery.remodal.min.js') }}"></script>
<script src="{{ asset('js/Chart.js') }}" charset="utf-8"></script>
<!-- STATISTIK START -->
<script>
var clicked_homework = null
var current_homework_number = null
$(function() {
    $(document).on('click', '.stat_btn', function()
    {
        clicked_homework = $(this).attr('name')
        current_homework_number = $(this).siblings('.type').text()
    })

    // Called when the remodal is opened
    $(document).on('open', '#stat', function()
    {
        //if (clicked_homework == null)
            //$(this).remodal('close')
        var classroom_data = data.classrooms[current_classroom]
        var ajax = new Ajax(data.url.stat_data, {classroom_id: classroom_data.classroom_id, homework_id: clicked_homework})
        var stat_server_data = ajax.send()
        $('#homework_number').text(current_homework_number)
        // Generate task_data
        var holder = ""
        console.log(stat_server_data)
        if (typeof stat_server_data == 'string')
            alert(stat_server_data)
        for (x in stat_server_data.stat_data)
        {
            local_data = stat_server_data.stat_data[x]
            holder += "<tr>"
                // id
                holder += "<td>"+x+"</td>"
                // Percentage done
                holder += "<td>"+local_data.completed + " (" + local_data.percentage + " %)</td>"
                // Wrong answers
                holder += "<td>"+local_data.wrong_answers[0]+"</td>"
                var i = 0
                for (item in local_data.wrong_answers[1])
                {
                    if (i > 3)
                        break
                    holder += "<td>"+ item +"("+local_data.wrong_answers[1][item]+")</td>"
                    delete local_data.wrong_answers[1][item]
                    i++
                }
                while (i < 3)
                {
                    holder += "<td></td>"
                    i++
                }
            holder += "</tr>"
        }

        $('#stat_tbody').empty()
        $('#stat_tbody').append(holder)

        var stat_data = [
            {
                value: stat_server_data.completed_all,
                color:"rgba(255,186,115,1)",
                highlight: "rgba(255,186,115,1)",
                label: "Färdiga"
            },
            {
                value: stat_server_data.started,
                color: "#2DB6CF",
                highlight: "#2DB6CF",
                label: "Påbörjat"
            },
            {
                value: stat_server_data.not_started,
                color: "rgba(107,127,207,1)",
                highlight: "rgba(107,127,207,1)",
                label: "Inte påbörjat"
            }
        ]

        var options = {
            //Boolean - Whether the scale should start at zero, or an order of magnitude down from the lowest value
            scaleBeginAtZero : true,
            //Boolean - Whether grid lines are shown across the chart
            scaleShowGridLines : true,
            //String - Colour of the grid lines
            scaleGridLineColor : "rgba(0,0,0,1)",
            //Number - Width of the grid lines
            scaleGridLineWidth : 1,
            //Boolean - Whether to show horizontal lines (except X axis)
            scaleShowHorizontalLines: true,
            //Boolean - Whether to show vertical lines (except Y axis)
            scaleShowVerticalLines: true,
            //Boolean - If there is a stroke on each bar
            barShowStroke : true,
            //Number - Pixel width of the bar stroke
            barStrokeWidth : 2,
            //Number - Spacing between each of the X value sets
            barValueSpacing : 5,
            //Number - Spacing between data sets within X values
            barDatasetSpacing : 1,
            //String - A legend template
            legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].fillColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
        };

        var ctx = $("#piechart").get(0).getContext("2d")
        // This will get the first returned node in the jQuery collection.
        var newChart = new Chart(ctx).Pie(stat_data,options)
    })


})
</script>
<!-- STATISTIK END -->

<script>
var data = {{ json_encode($data) }}
var current_classroom = 0
var is_teacher = false
var task_group = []
@if (Auth::user()->account_type > 0)
is_teacher = true
@endif

function add_users_to_classroom(current_classroom)
{
    // Load the current classroom
    var users_append = $('#users-in-classroom')
    users_append.empty()
    for (var i = 0; i < data.classrooms[current_classroom].user_data.length; i++)
    {
        var append_user = '<li><a href="#">'
        append_user += data.classrooms[current_classroom].user_data[i].first_name
        append_user += " "
        append_user += data.classrooms[current_classroom].user_data[i].last_name
        users_append.append(append_user)
    }

    if (is_teacher)
        users_append.append('<a class="button blue" href="#addstudent_modal">Lägg till elev</a>')
}

function add_info_classroom(data)
{
    var _class  = $('#class')
    var school  = $('#school')
    var teacher = $('#teacher')
    // Fill with new data
    _class.html(data.classname)
    school.html(data.schoolname)
    teacher.html(data.first_name + " " + data.last_name)
}

function is_owner()
{
    return data.classrooms[current_classroom].is_owner
}

function homeworks()
{
    try
    {
        var classroom_id = data.classrooms[current_classroom].classroom_id
    }
    catch(error)
    {
        // This user does not have any classrooms
        return false
    }

    var ajax = new Ajax(data.url.homework_data, {classroom_id: classroom_id}, 'GET', false)
    var res = ajax.send()
    feed = $('#classroom-feed')
    feed.empty()

    // Insert creater if the user is the owner of this classroom
    if (is_owner())
    {
        var data_to_append = ""
        data_to_append += '</div>'
        data_to_append += '<div class="item fancy fadeIn">'
        data_to_append += '<div class="item-content create-homework">'
        data_to_append += '<h4>Skapa en läxa</h4>'
        data_to_append += '<p><label>Deadline:</label><input type="text" id="homework-new-deadline"/></p>'
        data_to_append += '<p><label>Meddelande:</label><textarea rows="4" id="homework-new-text"></textarea></p>'
        data_to_append += '<button class="button blue" id="new-homework">Skicka</button>'
        data_to_append += '</div>'
        data_to_append += '<div class="tasks" id="homework-new-tasks">'
        data_to_append += '<a id="add-tasks" href="#addtasks_modal">+</a>'
        data_to_append += '</div>'
        data_to_append += '</div>'
        feed.append(data_to_append)
    }

    for(var i = 0; i < res.length; i++)
    {
        var day_or_days = res[i].days == 1 ? 'dag' : 'dagar'
        var data_to_append = ""
        data_to_append += '<div class="item fancy fadeIn">'
            data_to_append += '<div class="item-content">'
                data_to_append += '<p class="deadline"><span class="deadline-value">'+res[i].days+'</span><span class="deadline-text">'+day_or_days+' kvar</span></p>'
                data_to_append += '<p class="type">Läxa '+(i + 1)+'</p>'
                data_to_append += '<p class="date">'+res[i].deadline+'</p>'
                data_to_append += '<p class="message">'+res[i].message+'</p>'
                if (is_teacher)
                    data_to_append += '<a class="button blue stat_btn" href="#stat_modal" name="'+res[i].id+'">Statistik</a>'
            data_to_append += '</div>'
            data_to_append += '<div class="tasks">'
            for(var j = 0; j < res[i].tasks.length; j++)
            {
                var complete = res[i].tasks[j].solved == 0 ? false : true
                data_to_append += '<a'
                if (complete)
                    data_to_append += ' class="complete"'
                data_to_append += ' href="'+data.url.task_url+'/'+res[i].tasks[j].task_id+'/'+res[i].id+'" target="_new">Uppgift '+(j + 1)+'</a>'
            }
            data_to_append += '</div>'
        data_to_append += '</div>'
        feed.append(data_to_append)
    }

    // Rebind datepicker
    $('#homework-new-deadline').datepicker({
        dateFormat: 'yy-mm-dd',
        minDate:    0
    })
}

$(function()
{
    // ---Create Task-groups---
    
    // Book
    $(document).on('change', '#course', function()
    {
        var ajax = new Ajax(data.url.chapter_data, {book_id: $(this).val()})
        var res = ajax.send()
        // Add chapters
        var chapter = $('#chapter')
        chapter.empty()
        for (x in res)
            chapter.append('<option value="'+res[x].id+'">'+res[x].name+'</option>')
        // Trigger next
        $('#chapter').trigger('change')
    })

    // Chapter
    $(document).on('change', '#chapter', function()
    {
        var ajax = new Ajax(data.url.subchapter_data, {chapter_id: $(this).val()})
        var res = ajax.send()
        // Add chapters
        var subchapter = $('#subchapter')
        subchapter.empty()
        for (x in res)
            subchapter.append('<option value="'+res[x].id+'">'+res[x].name+'</option>')
        $('#subchapter').trigger('change')
    })

    // Subchapter
    $(document).on('change', '#subchapter', function()
    {
        var ajax = new Ajax(data.url.task_data, {subchapter_id: $(this).val()})
        var res = ajax.send()
        // Add chapters
        var task = $('#task')
        task.empty()
        for(var i = 0; i < res.length; i++)
            task.append('<option value="'+res[i].id+'">'+(i + 1)+'</option>')
        $('#task').trigger('change')
    })

    // Apply the selected tasks when closing
    $(document).on('close', '#addtasksmenu', function()
    {
        var homework_new_tasks = $('#homework-new-tasks')
        homework_new_tasks.empty()
        for (x in task_group)
            homework_new_tasks.append('<a href="'+data.url.task_url+'/'+task_group[x]+'" target="_new">Uppgift '+task_group[x]+'</a>')
        homework_new_tasks.append('<a id="add-tasks" href="#addtasks_modal">+</a>')
    })

    $(document).on('click', '#add-task-to-group', function()
    {
        // Add task to group
        var task_value = $('#task').val()
        if (task_group.indexOf(task_value) == -1 && task_value != null) // Add item if it does not exist in `task_group` and does exist
            task_group.push(task_value)

        // Add items to "Dina valda uppgifter"
        var holder = $('#four')
        holder.empty()
        for (x in task_group)
            holder.append('<li><a href="'+data.url.task_url+'/'+task_group[x]+'" target="_new">Uppgift '+task_group[x]+'</a></li>')
    })

    // ---END Create Task-groups---

    $(document).on('click', '#new-homework', function()
    {
        var message = $('#homework-new-text').val()
        var deadline = $('#homework-new-deadline').val()
        if (deadline && task_group.length != 0)
        {
            var data_to_send = {
                message:     message,
                deadline:    deadline,
                tasks_group: task_group,
                classroom_id:data.classrooms[current_classroom].classroom_id
            }
            var ajax    = new Ajax(data.url.new_homework, data_to_send, 'GET', false)
            var res     = ajax.send()
            // Update homeworks
            homeworks()
        }
        else
            alert('Du måste sätta in en deadline och välja uppgifter!')
    })

    var classroom_container = $('#classes')
    try // Prevent classroom from crashing if the user isn't in one
    {
        // Add the classes we're in
        classroom_container.empty()
        for (var i = 0; i < data.classrooms.length; i++)
        {
            var appendable_data = '<li>'
            appendable_data += '<a href="#" name="'+i+'" class="change-class'
            appendable_data += i == 0 ? ' active' : ''
            appendable_data += '">'+data.classrooms[i].name+'</a>'
            // Set the current class
            appendable_data += '</li>'
            classroom_container.append(appendable_data)
        }

        if (is_teacher)
            classroom_container.append('<li><a id="create-class" href="#createclassroom_modal">+</a></li>')

        add_users_to_classroom(current_classroom)
        add_info_classroom(data.classrooms[current_classroom].classroom_info)
        // 
    }
    catch(error)
    {
        console.log(error)
    }

    // Add homework-data
    homeworks()

    // Change the current class
    $(document).on('click', '.change-class', function()
    {
        // Remove the active class
        $('.change-class').each(function()
        {
            $(this).removeClass('active')
        })

        $(this).addClass('active')
        current_classroom = $(this).attr('name')
        add_users_to_classroom(current_classroom)
        add_info_classroom(data.classrooms[current_classroom].classroom_info)
        // Add homeworks to this classroom
        homeworks()
    })
    
    // Add student
    $(document).on('click', '#add-student', function()
    {
        var user_id = $('#search-student').attr('name')
        var data_to_send = {
            classroom_id: data.classrooms[current_classroom].classroom_id,
            student_id: user_id
        }
        // Send the request to the user
        var ajax = new Ajax(data.url.send_request, data_to_send, 'GET', false)
        var res = ajax.send()
        if (res == true)
            alert('En förfrågan har skickats!')
        else if (res == false)
            alert('Ett fel har inträffat!')
        else
            alert(res)

        return false
    })

    // Autofill for students
    $('#search-student').autocomplete
    ({
        minChars: 1,
        autoFill: true,
        source: function( request, response )
        {
            $.ajax
            ({
                url: data.url.search_user,
                dataType: "JSON",
                data: {
                    input: request.term
                },
                success: function( data )
                {
                    var tags = []
                    for (x in data)
                        tags.push({label: data[x].first_name + " " + data[x].last_name + ' (' + data[x].email + ')', value: data[x].first_name + " " + data[x].last_name, id: data[x].id})
                    response(tags)
                }
            })
        },
        select: function (event, b)
        {
            $(this).attr('name', b.item.id)
        }
    })

    // Autofill for schools
    $('#create-classroom-school-name').autocomplete
    ({
        minChars: 1,
        autoFill: true,
        source: function( request, response )
        {
            $.ajax
            ({
                url: data.url.search_school,
                dataType: "JSON",
                data: {
                    input: request.term
                },
                success: function( data )
                {
                    response(data)
                }
            })
        }
    })

    $('.ui-autocomplete').addClass('arrow_box_left');
    $('.ui-autocomplete').attr("style", "");

    // Create classroom
    $(document).on('click', '#add-classroom', function()
    {
        var class_name_input  = $('#create-classroom-class-name').val()
        var school_name_input = $('#create-classroom-school-name').val()
        if (class_name_input == "" || school_name_input == "")
            alert('Skolans och klassens namn krävs!')
        else
        {
            var data_to_send = {
                class_name:     class_name_input,
                school_name:    school_name_input
            }

            var ajax = new Ajax(data.url.create_classroom, data_to_send, 'GET', false)
            var response = ajax.send()
            // Close remodal window before reloading the page
            var inst = $.remodal.lookup[$('[data-remodal-id=createclassroom_modal]').data('remodal')];
            inst.close()
            location.reload()
        }

        return false
    })
})
</script>


