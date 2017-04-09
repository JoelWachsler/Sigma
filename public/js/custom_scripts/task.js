// This file is used for creating and uploading tasks

function Task()
{
    var self = this
    // For creating solving material
    self.solvers = []

    // Asks the server for content with the current id
    self.change_task = function(id)
    {
        var data
        if (typeof id == 'undefined')
        {
            data =
            {
                active:         0,
                answer:         "",
                chapter_id:     0,
                chapter_order:  0,
                content:        "",
                created_at:     "2015-01-01 00:00:00",
                difficulty:     1,
                height:         300,
                id:             0,
                latex_answer:   "",
                latex_solve:    "",
                updated_at:     "2015-01-01 00:00:00",
                answer_type:    0
            }
        }
        else
        {
            // Get data
            var ajax = new Ajax(url_get, { task_id: id }, 'GET', true)
            data = ajax.send()
        }

        // Change data
        self.replace_data(data)
    }

    self.change_formula = function(id_formula)
    {
        var ajax = new Ajax(formula_id, { q: id_formula }, 'GET', true)
        self.replace_data(ajax.send())
    }

    self.save_chapter_or_subchapter = function(name, url, book, chapter)
    {
        // Create a new chapter
        var data = 
        {
            name:       name,
            book:       book,
            chapter:    chapter
        }
        var ajax = new Ajax(url, data, 'POST', true)
        // Give back response from server
        return ajax.send()
    }

    self.save = function()
    {
        // Save current task
        self.remove_tags() // Remove tags to prevent errors when recreating

        // Declare variables here so we can use
        // them outside of the statement below
        var data = ""
        var response

        if (is_formula())
        {
            // Default data
            data =
            {
                active:         $('#active').val(),
                name:           $('#chapter option:selected').text(), // Name of selected input
                height:         $('#change-height').val(),
                content:        $('#content').html(),
                id:             $('#chapter').val()
            }
            
            var ajax = new Ajax(url_formula, data, 'POST', true)
            response = ajax.send()
            $('#course').trigger('change')
            $('#chapter').val(response)
        }
        else
        {
            // Data to upload
            data =
            {
                active:         $('#active').val(),
                answer:         $('#answer').val(),
                chapter_id:     $('#chapter').val(),
                subchapter_id:  $('#subchapter').val(),
                // This may be buggy and should probably be replaced later
                // Should be calculated by server
                chapter_order:  $('#task').val() == 'new' ? $('#task option').size() - 1 : $('#task').val(),
                content:        $('#content').html(),
                difficulty:     $('#difficulty').val(),
                height:         $('#change-height').val(),
                id:             $('#task').val(),
                latex_answer:   self.solvers[1].prepare(),
                latex_solve:    self.solvers[0].prepare(),
                answer_type:    $('#type-of-answer').val()
            }

            var ajax = new Ajax(url_create, data, 'POST', true)
            response = ajax.send()
            // Update menu
            $('#subchapter').trigger('change')
            $('#task').val(response)
        }
        fix_images($('#content'))
        task_utility.make_draggable()

        alert(typeof response == 'number' ? 'Uppgift sparad!' : 'Error: ' + response)
    }
    
    self.remove_tags = function()
    {
        $('#content *').removeAttr('contenteditable').blur()
        // Remove draggable and resizeable
        // so it does not get saved in the database
        $('#content').children().each(function()
        {
            if ($(this).data('ui-draggable'))
                $(this).draggable('destroy')

            if ($(this).data('ui-resizable'))
                $(this).resizable('destroy')

            $(this).children().each(function()
            {
                // Making links safe for saving in tha database
                var attr = $(this).attr('src')
                if (typeof attr !== typeof undefined && attr !== false)
                {
                    var match = $(this).attr('src').match(/([^/]*)$/gi)
                    if (match)
                        $(this).replaceWith("<div name=\""+match[0]+"\" style=\"\" class=\"replaced_img\"></div>")
                }
            })
        })
    }

    // Goes through the data from the server
    // and puts it in the correct places
    self.replace_data = function(data)
    {
        // Check if this task is a formula
        if (is_formula())
        {
            // Check if new
            if (data.chapter_order == 0)
            {
                var name = prompt("Vad ska formeln heta?")
                if (typeof name == 'string' && name != "")
                    $('#chapter').append('<option selected value="new">'+name+'</option>')
            }
        }
        else
        {
            // Check if new
            if (data.chapter_order == 0)
                $('#task').append('<option selected value="new">'+ $('#task option').size() +'</option>')
        }
        // Fill data
        $('#active').val(data.active == 1 ? 1 : 0)
        $('#title').val($('#chapter :selected').text())
        $('#answer').val(data.answer)
        // Change height of #content
        $('#change-height').val(data.height)
        // Trigger height change
        $('#change-height').trigger('input')

        $('#difficulty').val(data.difficulty)
        $('#task_id').val(data.chapter_id)
        $('#content').html(data.content)
        $('#type-of-answer').val(data.answer_type)

        // Now make the data in #content editable
        fix_images($('#content'))
        task_utility.make_draggable()
        $('#content *').attr('contenteditable', 'true')

        self.solvers.push(new Solver(data.latex_solve,  2, '#solution-steps',   $('#answer_number')))
        self.solvers.push(new Solver(data.latex_answer, 2, '#answer-data',      $('#real_answer_number')))
        for (x in self.solvers)
            self.solvers[x].init()
    }
}

