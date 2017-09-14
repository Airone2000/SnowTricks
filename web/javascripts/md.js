$(function(){

    let firstTextArea = $('.mde')[0];
    $(firstTextArea).prop('required', false);

    var simplemde = new SimpleMDE({
        element : firstTextArea,
        spellChecker : false,
        hideIcons: ["guide", "heading", "fullscreen", "side-by-side"],
        showIcons: ["strikethrough", "horizontal-rule"]
    });

})