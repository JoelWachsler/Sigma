/* 
 * @author  Joel Wachsler
 * @date    2014-12-21
 * @version 1.0
 * @desc    This file is used on multiple pages
 *          for example getting data with ajax
 */

// JS class that makes it easier to do ajax requests
function Ajax(url, data_to_send, method, spinner)
{
    var self = this
    self.url = url
    self.data_to_send = data_to_send
    self.method = method
    self.spinner = spinner

    // Holder of the result for returning
    self.result

    $.ajaxSetup({
        beforeSend: function()
        {
            if (self.spinner)
                $('#loader').show()
        },
        complete: function()
        {
            if (self.spinner == true)
                $('#loader').hide()
        },
        headers: { 'X-CSRF-Token' : $('meta[name=_token]').attr('content') }
    })

    self.send = function()
    {

        $.ajax({
            method: self.method,
            url:    self.url,
            async:  false,
            cache:  false,
            data:   self.data_to_send,
            success: function(data)
            {
                result = data
            },
            error: function (request, status, error) {
                alert("FEL :( Om du ser en admin skicka detta: " + request.responseText)
            }
        })

        return result
    }
}

// Make friendly urls
function convertToSlug(Text)
{
    return Text
        .toLowerCase()
        .replace(/ /g,'_')
        .replace(/[^\w-]+/g,'')
}

// This function goes through each image
// and replaces the url so it can be saved
// in the database and then later be used
// on another servers than the one the images
// was uploaded to
function fix_images(container)
{
    container.children().each(function()
    {
        $(this).children().each(function()
        {
            if ($(this).hasClass('replaced_img'))
                $(this).replaceWith('<img src="'+url_image+'/'+$(this).attr('name')+'" alt="Image" class="img" />')
        })
    })
}

// Get parameters in the url
var QueryString = function () {
    // This function is anonymous, is executed immediately and 
    // the return value is assigned to QueryString!

    return window.location.pathname.split('/')
}();
