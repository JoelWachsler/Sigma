/* 
 * @author  Joel Wachsler
 * @date    2014-12-22
 * @version 1.0
 * @desc    This file is used for searching formulas
 */

$(function()
{
    $('#tags').autocomplete
    ({
        minChars: 1,
        autoFill: true,
        source: function( request, response )
        {
            $.ajax
            ({
                url: url,
                dataType: "json",
                data: {
                q: request.term
                },
                success: function( data )
                {
                    response(data)
                }
            })
        },
        select: function (event, b)
        {
            var id = b.item.data
            var name = b.item.value

            var holder = $('#frame_holder')
            var new_url = url + '/' + id
            $.get(new_url, function(data)
            {
                holder.empty()
                holder.css('height', data.height + 'px');
                $('#formula_title').text(name);
                holder.append(data.content)
                fix_images(holder)
                $('[data-remodal-id=frame_holder_modal]').remodal({}).open();
                MathJax.Hub.Queue(["Typeset",MathJax.Hub]);
            })
        }
    })

    $('.ui-autocomplete').addClass('arrow_box_left');
    $('.ui-autocomplete').attr("style", "");

    $(document).on('click', '.hide_formula', function()
    {
        $(this).parent().hide()
    })

})
