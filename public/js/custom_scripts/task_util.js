// This class is used for utility on many pages
function Task_util()
{
    var self = this
    self.latest
    self.obj_del_holder = null
    // Goes through each element and makes it draggable
    self.make_draggable = function()
    {
        $('#content').children().each(function()
        {
            // We resize images yet
            if ($(this).css('display') != 'inline-block')
                $(this).draggable({ containment: '#content' }).resizable({ containment: '#content' })
            else
                $(this).draggable({ containment: '#content' })
            // Custom fix for items to not affect each other
            $(this).css('position', 'absolute')
            // Custom fix for deleting pictures
            if (!$(this).hasClass('obj'))
                $(this).addClass('obj')
            if (!$(this).hasClass('un_selected'))
                $(this).addClass('un_selected')
        })
    }

    // Makes it easier to get JSON data when searching
    self.get = function(this_url, data)
    {
        var ajax = new Ajax(this_url, data, 'GET', true)
        return ajax.send()
    }

    // Makes urls for the manager
    self.create_url = function(book, chapter, id)
    {
        var book = book
        var chapter = chapter
        var id = id
        return base_url + book + '/' + chapter + '/' + id
    }
    
    self.default = function(input, text)
    {
        var input = input
        var text = text
        input.empty()
        input.append(text)
    }

    self.default_chapter = function()
    {
        self.default($('#chapter'), '<option value="default">VÄLJ KAPITEL</option>')
    }

    self.default_subchapter = function()
    {
        self.default($('#subchapter'), '<option value="default">VÄLJ DELKAPITEL</option>')
    }

    self.default_task = function()
    {
        self.default($('#task'), '<option value="default">VÄLJ UPPGIFT</option>')
    }

    // Adds content to the #content div
    self.add = function(content)
    {
        $('#content').append(content)
        self.make_draggable()
    }

    self.del_element = function(element)
    {
        element.remove()
    }

    self.unbind = function()
    {
       $("div.custom-menu").remove();
        // Revert
        $('#content').children().each(function()
        {
            if ($(this).hasClass('selected'))
                $(this).removeClass('selected').addClass('un_selected')
        })
    }

    // Table adder
    $(document).on('click', '#add-table', function()
    {
        // Table generator
        var rows = parseInt(prompt('Rows'))
        if (!isNaN(parseInt(rows)))
        {
            var colums = parseInt(prompt('Colums'))
            // Prepare table if there is any input
            // and if it's valid
            if (!isNaN(parseInt(colums)))
            {
                var table = "<table class='auto_gen_table'>"
                for(i = 0; i < rows;i++)
                {
                    table += "<tr>"
                    for(y = 0; y < colums; y++)
                        table += "<td>text</td>"
                    table += "</tr>"
                }
                self.add(table)
            }
        }
    })

    $('#text-alignment').change(function() {
        var val = $(this).val()
        switch(val)
        {
            case '1':
                self.latest.css('text-align', 'left')
                break
            case '2':
                self.latest.css('text-align', 'center')
                break
            case '3':
                self.latest.css('text-align', 'right')
                break
        }
    })

    $('#text-type').change(function() {
        var val = $(this).val()
        switch(val)
        {
            case '1':
                self.latest.removeClass('container-task-title')
                self.latest.css('font-weight', '400')
                break
            case '2':
                self.latest.removeClass('container-task-title')
                self.latest.css('font-weight', '700')
                break
            case '3':
                self.latest.addClass('container-task-title')
                break
        }
    })

    $('#div-alignment').change(function() {
        var val = $(this).val()
        switch(val)
        {
            case '1':
                self.latest.css('left', '0')
                break
            case '2':
                self.latest.css('left', $('#content').width()/2 - self.latest.width()/2)
                break
            case '3':
                self.latest.css('left', $('#content').width() - self.latest.width())
                break
        }
    })

    $(document).on('click', '#content div, #content, table', function () {
        self.latest = $(this)
        var textAlign = $(this).css('textAlign')
        var fontWeight = $(this).css('font-weight')

        switch(textAlign)
        {
            case 'left':
                textAlign = 1
                break
            case 'center':
                textAlign = 2
                break
            case 'right':
                textAlign = 3
                break
        }

        if(fontWeight == 700 || fontWeight == 'bold')
            fontWeight = 2
        else if(fontWeight == 400 || fontWeight == 'normal')
            fontWeight = 1

        $('#text-alignment').val(textAlign)
        $('#text-type').val(fontWeight) 
    })

    $(document).on('click', '#add-text', function()
    {
        var text = prompt('Text att sätta in:')
        // Add text if there is any
        if (typeof text == 'string' && text != '')
            self.add('<div style="display:inline-block">'+text+'</div>')
    })

    $(document).on('click', '#add-img', function()
    {
        $('#imageFile').trigger('click')
    })

    // Extension for deleting
    $(document).on('contextmenu', '.obj', function()
    {
        $(this).removeClass('un_selected').addClass('selected')
        self.obj_del_holder = $(this)
    })

    $('#content').bind('contextmenu', function(e)
    {
        var target = $(e.target)
        self.unbind()
        if (!target.is('#content'))
        {
            e.preventDefault()
            $("<div class='custom-menu delete' name='"+target+"'>RADERA</div>")
                .appendTo("body")
                .css({top: event.pageY + "px", left: event.pageX + "px"})
        }
    })

    $(document).bind("click", function(event) {
        self.unbind()
    })

    $('#change-height').on('input', function(e){
        $('#content').css('height', $(this).val() + 'px')
    })
    
    $(document).on('click', '.delete', function()
    {
        self.obj_del_holder.remove()
    })
}
