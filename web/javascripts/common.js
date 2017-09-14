var uniqid = function() {
    return (new Date().getTime() + Math.floor((Math.random()*10000)+1)).toString(16);
};

$(function(){

    /* Demander confirmation pour supprimer un contenu */
    $(document).on('click', '.ask-confirm', function(){
        if(!confirm("Êtes-vous sûr ?")){
            return false;
        }
    });

});