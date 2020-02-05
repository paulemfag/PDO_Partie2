$(function(){
    // Si le formulaire a déjà été envoyé le réaffiche avec les messages d'erreur / Span
    if ($('#submit').attr('value') === 'alreadySubmitted'){
        $('#appointmentInformations').hide();
        $('#modifyInformations').show();
        $('#return').show();
    } else {
        // Sinon n'affiche que les informations du patient
        $('#appointmentInformations').show();
        $('#modifyInformations').hide();
        $('#return').hide();
    }
});

// Quand on clique sur modifier les informations du patient
$('#modify').click(function () {
    $('#appointmentInformations').hide();
    $('#modifyInformations').show();
    $('#return').show();
});

// Quand on clique sur "Envoyer" donne la valeur alreadySubmitted au bouton
$('#submit').click(function () {
    $('#submit').attr('value','alreadySubmitted');
});

// Quand on clique sur la flèche retour
$('#return').click(function () {
    $('#appointmentInformations').show();
    $('#modifyInformations').hide();
    $('#return').hide();
});