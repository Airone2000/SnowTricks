$(function(){


    var $commentContainer = $('#comments');
    var $btnLoadMore = $('<button>Charger + de commentaires</button>');

    var controllerRetriever = $commentContainer.data('controller');
    var page = 0;
    var totalComments = 0;
    var totalCommentsLoaded = 0;

    // Closure de récupération des commentaires
    var loadTenMoreComments = function(page){

        // Je m'assure d'appeler le bon contrôleur avec les bons paramètres
        controllerRetriever = controllerRetriever.replace(/[0-9]+$/, page);
        $.get(controllerRetriever, function(response){

            // Je transforme les divs en objet jQuery pour les comptabiliser
            let $comments = $(response.view);
            totalCommentsLoaded += $comments.filter('.comment').length;

            /* Supprimer le loader */
            if(page === 0)
                $commentContainer.empty();
            else
                $btnLoadMore.removeClass('loading');

            // J'ajouter les commentaires reçus à la liste de ceux déjà chargés
            $commentContainer.append($comments);
            totalComments = response.totalComments;


            /* Je remets le bouton à la fin de la liste de commentaire */
            $commentContainer.append($btnLoadMore);

            /* S'il n'y a plus de commentaires à charger, on cache le bouton */
            /* Solution discutable dans la mesure où d'autres commentaires peuvent */
            /* avoir pu être publiés entre temps. */
            if(totalCommentsLoaded >= totalComments)
            {
                $btnLoadMore.off('click', function(){ loadTenMoreComments(page++) });
                $btnLoadMore.remove();
            }


        });

    };

    // charger initialement 10 commentaires
    loadTenMoreComments(page++);

    // Charger des commentaires à l'évènement
    $btnLoadMore.on('click', function(){

        // Ajouter le loader
        $btnLoadMore.addClass('loading');

        // Appeler 10 commentaires supplémentaires
        loadTenMoreComments(page++);
    });

    // Supprimer un commentaire
    $(document).on('click', 'a.remove-comment', function(){

        if(confirm('Confirmez vous la suppression de ce commentaire ?'))
        {
            let $parent = $(this).closest('div.comment')
            let href = $(this).attr('href');

            // Normalement,
            $.post(href, { '_method' : 'DELETE' }, function(response){

                $parent.text(response.msg);

            });
        }

        return false;

    });

    // Modifier un commentaire
    $(document).on('click', 'a.edit-comment', function(){

        let $parent = $(this).closest('div.comment');
        let href = $(this).attr('href');

        $parent.html('<p class="loading"></p>');

        $.get(href, function(response){

            if(response.status === 'OK_1')
            {
                $parent.html(response.view);
            }
        });

        return false;

    });

    // Modifier effectivement un commentaire
    $(document).on('submit', 'form.form-edit-comment', function(){

        let $form = $(this);
        let output = {};
        let action = $form.attr('action');
        let $btnSubmit = $form.find('button[type=submit]');
        let data = $form.serializeArray();

        /* Récupérer les données du formulaire */
        for(var id in data)
        {
            let field = data[id];
            output[field.name] = field.value
        }

        /* Afficher le loader */
        $btnSubmit.addClass('loading');

        /* Procéder à la requête */
        $.post(action, data, function(response){
            if(response.status === 'OK_2')
            {
                $form.parent('div.comment').replaceWith(response.view);
            }
            else
            {
                $form.replaceWith(response.view);
            }
        });

        return false;
    });
})