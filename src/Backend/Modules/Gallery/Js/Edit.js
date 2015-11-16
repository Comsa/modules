/**
 * Interaction for the galleria-module
 *
 *
 *
 */
jsBackend.gallery =
{

    init: function ()
    {
        //--Initialise sortable
        jsBackend.gallery.bindSortable();

        //--Initialise uploadify
        jsBackend.gallery.plupload();

    },

    bindSortable: function ()
    {
        //--Add sortable to the galleria-lists
        $('ul.gallery').sortable(
            {
                handle: 'img',
                tolerance: 'pointer',
                stop: function (e, ui)				// on stop sorting
                {
                    var arrIds = Array();

                    //--Loop the children
                    $(this).children('li').each(function (index, element)
                    {
                        //--Get the id from the element and push it into an array
                        arrIds.push($(element).attr('id').substr(6));
                    });

                    //--Create a string of the array with a , delimeter.
                    var strIds = arrIds.join(',');

                    //--Create ajax-call
                    $.ajax(
                        {
                            data: {
                                fork: { action: 'ImagesSequence' },
                                ids: strIds
                            },
                            success: function (data, textStatus)
                            {
                                //--Check if the response is correct
                                if (data.code == 200)
                                {
                                    jsBackend.messages.add('success', jsBackend.locale.lbl('SequenceSaved'));
                                }

                                //--If there is an error, alert the message
                                if (data.code != 200 && jsBackend.debug)
                                {
                                    alert(data.message);
                                }

                            },
                            error: function (XMLHttpRequest, textStatus, errorThrown)
                            {
                                // revert
                                $(this).sortable('cancel');

                                // show message
                                jsBackend.messages.add('error', 'alter sequence failed.');

                                // alert the user
                                if (jsBackend.debug)
                                {
                                    alert(textStatus);
                                }
                            }
                        })
                }
            });
    },
    plupload:function ()
    {
        var uploader = $("#uploader").plupload({
            // General settings
            runtimes: 'html5,flash,html4',
            url: "/backend/ajax",
            file_data_name: 'images',
            // Maximum file size
            max_file_size: '20mb',
            autostart: true,
            multipart_params : {
                "fork[module]" : "Gallery",
                "fork[action]" : "Plupload",
                "fork[language]" : jsBackend.current.language,
                "id" : jsData.Gallery.id
            },

            //chunk_size: '1mb',

            // Resize images on clientside if we can
            resize: {
                width: 1920,
                height: 1920,
                quality: 80,
                crop: false // crop to exact dimensions
            },

            // Specify what files to browse for
            filters: [
                {title: "Image files", extensions: "jpg,jpeg,gif,png"},
            ],

            // Rename files by clicking on their titles
            rename: true,

            // Sort files
            sortable: true,

            // Enable ability to drag'n'drop files onto the widget (currently only HTML5 supports that)
            dragdrop: true,

            // Views to activate
            views: {
                list: true,
                thumbs: true, // Show thumbs
                active: 'thumbs'
            },

            // Flash settings
            flash_swf_url: '/src/Backend/Modules/Gallery/Swf/Moxie.swf',

            // Silverlight settings
            //silverlight_xap_url: 'https://cdnjs.cloudflare.com/ajax/libs/plupload/2.1.8/Moxie.xap',

            init : {
                UploadComplete: function (up, files)
                {
                    // Called when all files are either uploaded or failed
                    var randomNumber = Math.floor(Math.random() * 11)

                    window.location.replace("/private/" + jsBackend.current.language + "/gallery/edit_album?token=true&id=" + jsData.Gallery.id + "&report=added-images&random=" + randomNumber + "#tabImages");
                }
            },
        });


    }
}

$(jsBackend.gallery.init);