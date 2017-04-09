// Uploader
var progressbox     = $('#progressbox')
var progressbar     = $('#progressbar')
var statustxt       = $('#statustxt')
var completed       = '0%'
var upload_progress = $('#upload_progress')

var options = { 
    beforeSubmit:   beforeSubmit,
    uploadProgress: OnProgress,    //upload progress callback 
    success:        afterSuccess,
    onError:        onError,
    resetForm:      true  
}

function onError (data)
{
    alert(data.error)
}

function beforeSubmit ()
{
    // Maybe not needed
    completed = '0%'
    $('#progressbox').show()
}

function afterSuccess (data)
{
    upload_progress.html('Image uploaded!')
    uploadDone(data.success)
}

function OnProgress (event, position, total, percentComplete)
{
    // Progress bar
    progressbar.width(percentComplete + '%')   //update progressbar percent complete
    statustxt.html(percentComplete + '%')      //update status text
    if(percentComplete>50)
        statustxt.css('color','#fff');              //change status text to white after 50%
}

function uploadDone (data)
{
    // Uploading images
    if (data)
        // This will be changed when it's going live
        task_utility.add('<div style="display:inline-block;max-width:inherit;max-height:inherit;"><img src="'+url_image+'/'+data+'" alt="Image" class="img" /></div>') 
    else
        alert("Something went wrong: " + data)
}

$(document).ready(function()
{
    // Check if image was submitted
    $('#MyUploadForm').submit(function(e)
    { 
        $(this).ajaxSubmit(options)       
        return false
    })

    // Check if anything is being uploaded
    $('#imageFile').on('change', function()
    {
        $('#MyUploadForm').submit()
    })

})


