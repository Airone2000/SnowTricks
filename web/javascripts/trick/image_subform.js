$(function(){

    /* 1. Le code ci-dessous permet d'insérer une nouvelle instance de ImageType au sein de TrickType */

    var $imagesContainer = $('#appbundle_trick_trick_images');
    var _prototype = $imagesContainer.data('prototype');
    var $btnAddImage = $('button.add-image');
    var $btnRemoveImage = $('<button class="remove-image">Retirer ce sous-formulaire</button>');

        /* 1.1 Ajouter un sous-formulaire image */
        $btnAddImage.click(function(){

            var imageTypeId = uniqid();
            var prototype = _prototype;
                prototype = $(_prototype.replace(/_FORM_TRICK_IMAGE_/g, imageTypeId));
                prototype.addClass('imageType').append( $btnRemoveImage.clone(true) );


            $imagesContainer.append( prototype );

            return false;

        });

        /* 1.2 Retirer un sous formulaire */
        $btnRemoveImage.click(function(){

            $(this).parents('div.imageType').remove();

            return false;

        });

    /** 1. Fin **/

})