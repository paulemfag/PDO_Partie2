$(function(){
    // Si le formulaire a déjà été envoyé le réaffiche avec les messages d'erreur / Span
    if ($('#submit').attr('value') === 'alreadySubmitted'){
        $('#appointmentInformations').show();
        $('#modifyInformations').show();
    } else {
        // Sinon n'affiche que les informations du patient
        $('#appointmentInformations').show();
        $('#modifyInformations').hide();
    }
});

// Quand on clique sur modifier les informations du patient
$('#modify').click(function () {
    $('#appointmentInformations').show();
    $('#modifyInformations').show();
});

// Quand on clique sur "Envoyer" donne la valeur alreadySubmitted au bouton
$('#submit').click(function () {
    $('#submit').attr('value','alreadySubmitted');
});