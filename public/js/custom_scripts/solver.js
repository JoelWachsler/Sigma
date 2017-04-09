// Answer code under here
function Solver(numb, answers, input, counter)
{
    var self            = this
    self.numb           = numb
    self.input          = input
    self.counter        = counter
    self.number_answer  = answers

    self.init = function()
    {
        // Convert to JSON format for easier handling
        if (typeof self.numb == 'undefined' || self.numb == '')
            self.numb = []
        else
            self.numb = JSON.parse(numb)
        
        // Remove everything in input before
        // doing anything with it
        $(self.input).empty()
        
        // There has to always be at least one answer
        if (self.numb.length < 1 || typeof self.numb != 'object')
        {
            self.counter.val(1)
            self.counter.trigger('change')
        }
        else
        {
            // Go through each answer and add it
            for (var i = 0; i < self.numb.length; i++)
                self.add(self.numb[i])
            // Set how many rows we now have
            self.counter.val(self.numb.length)
        }
    }
    
    // Check number of answers
    self.counter.change(function()
    {
        // Go until the proper amount of inputs are up
        while($(this).val() != self.count())
        {
            if ($(this).val() > self.count())
            {
                var fields = []
                for (var i = 0; i < self.number_answer; i++)
                    fields.push("")
                self.add(fields)
            }
            else
                $(input + ' tr').last().remove()
        }
    })

    self.count = function()
    {
        var steps = 0
        $(input + ' tr').each(function(){steps++})

        return steps
    }

    self.add = function(data)
    {
        var new_data = '<tr>'
        switch(data.length == 0 ? self.number_answer : data.length)
        {
            case 1:
                new_data += '<td>'
                new_data += '<input type="text" placeholder="Input här" value="'+data[0]+'">'
                new_data += '</td>'
                new_data += '</tr>'
                $(input).append(new_data)
                break
            case 2:
                new_data += '<td>'
                new_data += '<input type="text" placeholder="Input här" value="'+data[0]+'"></td>'
                new_data += '</td>'
                new_data += '<td>'
                new_data += '<input type="text" placeholder="Input här" value="'+data[1]+'">'
                new_data += '</td>'
                new_data += '</tr>'
                $(input).append(new_data)
                break
            default:
                alert('An error has occurred: 1')
        }
    }

    self.prepare = function()
    {
        // Go through each answer and add it
        // to a two dimensional array
        var data = []
        $(input).children().each(function()
        {
            var temp_data = []
            $(this).children().each(function()
            {
                temp_data.push($(this).children().val())
            })

            data.push(temp_data)
        })

        return JSON.stringify(data)
    }

    self.empty = function()
    {
        $(input).empty()
    }

}

