$(document).ready(function () {
    // Si le formulaire a déjà été envoyé le réaffiche avec les messages d'erreur / Span
    if ($('#submit').attr(value === 'alreadySubmitted')){
        $('#patientInformations').hide();
        $('#modifyInformations').show();
        $('#return').show();
    } else {
    // Sinon n'affiche que les informations du patient
        $('#patientInformations').show();
        $('#modifyInformations').hide();
        $('#return').hide();
    }
});

// Quand on clique sur modifier les informations du patient
$('#modify').click(function () {
    $('#patientInformations').hide();
    $('#modifyInformations').show();
    $('#return').show();
});

// Quand on clique sur "Envoyer" donne la valeur alreadySubmitted au bouton
$('#submit').click(function () {
    $('#submit').attr('value','alreadySubmitted');
});

// Quand on clique sur la flèche retour
$('#return').click(function () {
    $('#patientInformations').show();
    $('#modifyInformations').hide();
    $('#return').hide();
});