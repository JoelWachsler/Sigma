<div class="rightWrapper">
    <section role="main">
        <nav id="subBar">
            <h2>Verktyg</h2>
            <ul>
                <!--<li><a href="{{ URL::route('tools') }}">Arbetsblad</a></li>-->
                <li><a class="active" href="#">Manager</a></li>
            </ul>
        </nav>

        <article role="manager">
            <div id="manager-banner">
                <div id="task-picker">
                    <label>Välj:</label>
                    <select name="tp" id="course">
                        <option value="default">Välj mattekurs</option>
                        <option value="0">Formel</option>
                        @foreach($data->books as $item)
                            <option value="{{ $item->id }}">Matematik {{ $item->name }}</option>
                        @endforeach
                    </select>
                    <select name="tp" id="chapter">
                        <option value="default">Välj kapitel</option>
                    </select>
                    <select name="tp" id="subchapter">
                        <option value="default">Välj delkapitel</option>
                    </select>
                    <select name="tp" id="task">
                        <option value="default">Välj uppgift</option>
                    </select>
                </div>
                <div id="manager-buttons">
                    <button type="button" class="button blue" id="new">Skapa ny</button>
                    <button type="button" class="button blue" id="save">Spara</button>
                    <button type="button" class="button red" id="save">Ta bort</button>
                </div>
                <div class="clear"></div>
            </div>

            <div id="manager-view">
                <h2>Editor</h2>
                <div id="add-content">
                    <h4>Lägg till</h4>
                    <div id="add-content-wrapper">
                        <a id="add-text" class="add-content-item">Text</a>
                        <a id="add-img" class="add-content-item">Bild</a>
                        <a id="add-table" class="add-content-item">Tabell</a>
                    </div>
                    <h4>Ändra</h4>
                    <div id="add-content-wrapper">
                        <a id="left-align-text" class="align-content-item"></a>
                        <a id="center-align-text" class="align-content-item active"></a>
                        <a id="right-align-text" class="align-content-item"></a>
                    </div>
                    <h4>Inställningar</h4>
                    <div id="add-content-wrapper">
                        <p>
                            <label for="active">Höjd: </label>
                            <input id="change-height" type="number" value="300" />
                        </p>
                        <p>
                            <label for="difficulty">Nivå: </label>
                            <select id="difficulty">
                                <option value="1" selected="selected">Lätt</option>
                                <option value="2">Medel</option>
                                <option value="3">Svår</option>
                            </select>
                        </p>
                        <p>
                            <label for="active">Aktiv: </label>
                            <select id="active">
                                <option value="0">False</option>
                                <option value="1">True</option>
                            </select>
                        </p>
                        <p>
                            <label for="type-of-answer">Svarsmetod: </label>
                            <select id="type-of-answer">
                                <option value="0">Inmatning</option>
                                <option value="1">Alternativ</option>
                            </select>
                        </p>
                    </div>
            	</div>
                <div id="progressbox" style="display:none;margin:auto"><div id="progressbar"></div ><div id="statustxt">0%</div></div>
                <p id="upload_progress"></p>

                <div id="content"></div>
                <div class="clear"></div>
            </div>

            <div id="manager-solution-answer">
                <div id="content_solver">
                    <h2>Lösning</h2>
                    <p>Antalet steg: <input type="number" value="1" min="1" id="answer_number"></p>
                    <table>
                        <thead>
                            <tr>
                                <th>LaTeX</th>
                                <th>Kommentar</th>
                            </tr>
                        </thead>
                        <tbody id="solution-steps">
                            <tr>
                                <td><input type="text" placeholder="Input här" /></td>
                                <td><input type="text" placeholder="Input här" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div id="answer_content">
                    <h2>Svar</h2>
                    <p>Antalet svar: <input type="number" value="1" min="1" id="real_answer_number"></p>
                    <table>
                        <thead>
                            <tr>
                                <th>Delsvar</th>
                                <th>Svar</th>
                            </tr>
                        </thead>
                        <tbody id="answer-data">
                            <tr>
                                <td>a)</td>
                                <td><input type="text" placeholder="Input här" /></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="clear"></div>
            </div>

            <div id="task-settings">
            {{ Form::text('title', '', array('placeholder' => 'Kapitel', 'id' => 'title')) }}
            {{ Form::text('task_id', '', array('placeholder' => 'ID', 'id' => 'task_id')) }}
            </div>

           <!-- START Content needed uploading images --> 
            <div style="display:none">
            {{ Form::open(array('url' => 'upload_img', 'files' => true, 'id' => 'MyUploadForm')) }}
            {{ Form::file('img', array('id' => 'imageFile')) }}
            {{ Form::close() }}
            </div>
            <!-- END Content needed for base64 -->
        </article>
    </section>
</div>
<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script> 
<script src="//ajax.googleapis.com/ajax/libs/jqueryui/1.11.1/jquery-ui.min.js"></script>

<link href="https://hayageek.github.io/jQuery-Upload-File/uploadfile.min.css" rel="stylesheet">
<script src="https://hayageek.github.io/jQuery-Upload-File/jquery.uploadfile.min.js"></script>

<script src="{{ asset('js/custom_scripts/task_util.js') }}"></script>
<script src="{{ asset('js/custom_scripts/upload_image.js') }}"></script>
<script src="{{ asset('js/custom_scripts/solver.js') }}"></script>
<script src="{{ asset('js/custom_scripts/task.js') }}"></script>

<script>
// Global variables that cannot be declared in external files
var url_get     = '{{ action('TaskController@task_data') }}'
var url_create  = '{{ action('ManageController@task_create') }}'
var url_chapter = '{{ action('TaskController@chapters') }}'
var url_task    = '{{ action('TaskController@tasks') }}'
var url_formula = '{{ action('FormulasController@insert') }}'
var url_subchap = '{{ action('TaskController@subchapter_by_id') }}'
var formulas    = '{{ action('FormulasController@get_formulas') }}'
var formula_id  = '{{ action('FormulasController@get_formula_by_id') }}'
var chapter_url = '{{ action('ManageController@insert_chapter') }}'
var subchapter_url = '{{ action('ManageController@insert_subchapter') }}'
var book_id     = '{{ $data->this_book }}'
var chapter_id  = '{{ $data->this_chapter }}'
var task_id     = '{{ $data->this_id }}'
var task        = ""
var instance    = []
var task_utility= ""
var base_url    = '{{ URL::to('/') }}/manager/'

function is_formula()
{
    return $('#course').val() == 0 ? true : false
}

$(document).ready(function()
{  
    /*********
    START TEMP // DAVID
    **********/
    $('#course').show()
    $('#course').change(function() {
        if($(this).val() != 'default') {
            $('#chapter').show()
            $('#task-picker select').slice(2).hide()
        }
        else
            $('#task-picker select').slice(1).hide()
    })
    $('#chapter').change(function() {
        if($('#course').val() == 0)
            return false
        else if($(this).val() != 'default') {
            $('#subchapter').show()
            $('#task-picker select').slice(3).hide()
        }
        else
            $('#task-picker select').slice(2).hide()
    })
    $('#subchapter').change(function() {
        if($(this).val() != 'default')
            $('#task').show()
        else
            $('#task-picker select').slice(3).hide()
    })
    /*********
    END TEMP // DAVID
    **********/

    // Start a new utility
    task_utility = new Task_util()
    // Check if a task is selected
    task = new Task()
    //task.change_task(task_id)

    // Check if save button was clicked
    $(document).on('click', '#save', function()
    {
        var course      = $('#course').val()
        var chapter     = $('#chapter').val()
        var subchapter  = $('#subchapter').val()
        var title       = $('#title').val()

        try
        {
            if ($('#course option:selected').text() == "Formel" && chapter != 'default' || course != 'default' && chapter != 'default' && subchapter != 'default')
            {
                // Everything is fine and we can save :)
                task.save()
            }
            else if (course != 'default' && $('#task').val() == 'default')
            {
                if (title == '' || typeof title == 'undefined')
                    throw 'Skriv in en titel innan du sparar'

                if (chapter == 'default')
                {
                    var response = task.save_chapter_or_subchapter(title, chapter_url, course)
                    $('#course').trigger('change')
                    $('#chapter').val(response.id)
                }
                else if (subchapter == 'default')
                {
                    var response = task.save_chapter_or_subchapter(title, subchapter_url, course, chapter)
                    $('#chapter').trigger('change')
                    $('#subchapter').val(response.id)
                }
                else
                    throw 'Sätt in "välj" innan du klickar spara'
            }
            else if ($('course').val() == 'default' || $('#task').val() == 'default')
                throw 'Välj en uppgift att spara'
            else
                // Something went wrong
                throw 'Någonting gick snett :('
        }
        catch(error)
        {
            alert("Error: " + error)
        }
        
        // Return false so the page won't load anything
        return false
    })

    $(document).on('click', '#new', function()
    {
        task.change_task()

        return false
    })

    $('#course').change(function()
    {
        task_utility.default_chapter()
        // Check if not formula
        if (!is_formula())
        {
            task_utility.default_task()
            var data = task_utility.get(url_chapter, { book_id: $(this).val() })
        }
        else
        {
            var data = task_utility.get(formulas, {})
            $('#chapter').empty()
            $('#chapter').append('<option selected value="default">VÄLJ FORMEL</option>')
            $('#subchapter').empty()
            $('#subchapter').append('<option selected value="default">NOT AVAILABLE</option>')
            $('#task').empty()
            $('#task').append('<option selected value="default">NOT AVAILABLE</option>')
        }

        for (x in data)
            $('#chapter').append('<option value="' + data[x].id + '">' + data[x].name + '</option>')
    })

    // Check if task is being changed
    $('#chapter').change(function()
    {
        if (!is_formula())
        {
            task_utility.default_subchapter()
            var data = task_utility.get(url_subchap, { chapter_id: $(this).val() })
            if (typeof data == 'object')
                for (x in data)
                   $('#subchapter').append('<option value="' + data[x].id + '">' + data[x].name + '</option>')
        }
        else
        {
            task.change_formula($(this).val())
        }
    })

    $('#subchapter').change(function()
    {
        task_utility.default_task()
        var data = task_utility.get(url_task, { subchapter_id: $(this).val() })
        if (typeof data == 'object')
            for(var x = 0; x < data.length; x++)
                $('#task').append('<option value="' + data[x].id + '">' + (x + 1) + '</option>')
            //for (x in data)
    })

    $('#task').change(function()
    {
        task.change_task($(this).val())

        // For later
        //window.history.pushState('Object', 'Sigma - Manager', task_utility.create_url($('#course').val(), $('#chapter').val(), $('#task').val()))
    })
    
    $.fn.inlineStyle = function (prop)
    {
         var styles = this.attr("style"),
             value;
         styles && styles.split(";").forEach(function (e)
         {
             var style = e.split(":")
             if ($.trim(style[0]) === prop)
                 value = style[1]
         }) 
         return value
    }
})    
</script>
