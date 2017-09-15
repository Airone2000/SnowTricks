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

/* Je n'ai pas rédigé les conditions d'utilisation. */
/* Il est plus propre d'annoncer qqchose que de laisser un lien qui ne mène nulle part */
function alertCond()
{
    alert("Nos conditions générales sont en cours de rédaction.\n\nDécouvrez-les dès leur sortie dans 6 jours.\n\nPour toute question utile, contactez sans hésiter un administrateur : maels1991@gmail.com");
    return false;
}