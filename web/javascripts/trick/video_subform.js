$(function(){

    /* 1. Le code ci-dessous permet d'insérer une nouvelle instance de VideoType au sein de TrickType */

    var $videosContainer = $('#appbundle_trick_trick_videos');
    var _prototype = $videosContainer.data('prototype');
    var $btnAddVideo = $('button.add-video');
    var $btnRemoveVideo = $('<button class="remove-video">Retirer ce sous-formulaire</button>');

    /* 1.1 Ajouter un sous-formulaire video */
    $btnAddVideo.click(function(){

        var videoTypeId = uniqid();
        var prototype = _prototype;
        prototype = $(_prototype.replace(/_FORM_TRICK_VIDEO_/g, videoTypeId));
        prototype.addClass('videoType').append( $btnRemoveVideo.clone(true) );


        $videosContainer.append( prototype );

        return false;

    });

    /* 1.2 Retirer un sous formulaire */
    $btnRemoveVideo.click(function(){

        $(this).parents('div.videoType').remove();

        return false;

    });

    /* 1.3 Retirer une vidéos existante */
    $('button.remove-existing-video').click(function(){

        if(confirm("Confirmez-vous la suppression de cette vidéo ?"))
        {
            $(this).parents('div.video').remove();
        }

        return false;

    });


    /** 1. Fin **/

})