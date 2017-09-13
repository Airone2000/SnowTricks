$(function(){


    let $imageViewer = $('#image-viewer');
    let $imageInViewer = $imageViewer.find('img');
    let $btnCloseViewer = $imageViewer.find('.btn');

    $('.image-trick').click(function(){
        let $image = $(this);
        let src = $image.data('original');

        $imageInViewer.attr('src', src);
        $imageViewer.removeClass('hidden');

        /* Prévenir le scroll */
        $('body').css('overflow', 'hidden');

    });

    $btnCloseViewer.click(function(){
        $('body').css('overflow', 'auto');
        $imageViewer.addClass('hidden');
    });

})