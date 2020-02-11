<?php
$title = 'E2N | Ajout Patient et Rendez-vous';
require_once 'header.php';
require_once 'parameters.php';
$errors = [];
$lastName = $firstName = $birthDate = $phone = $mailbox = '';
$regexName = "/^[A-Za-zéÉ][A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{1,12}+((-| ?)[A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{0,11})$/";
$regexPhone = "/^0[3679]([0-9]{2}){4}$/";
$regexDate = "/^([1-2]{1})([0-9]{3})(-)([0-1]{1})([0-9]{1})(-)([0-3]{1})([0-9]{1})$/";
$regexDatetime = '/^([1-2]{1})([0-9]{3})(-)([0-1]{1})([0-9]{1})(-)([0-3]{1})([0-9]{1})([T])([0-9]{2})(:)([0-9]{2})$/';
if (isset($_POST['submit'])) {
    //contrôle Nom
    $lastName = trim(filter_input(INPUT_POST, 'lastName', FILTER_SANITIZE_STRING));
    if (empty($lastName)) {
        $errors['lastName'] = 'Veuillez renseigner votre Nom.';
    } elseif (!preg_match($regexName, $lastName)) {
        $errors['lastName'] = 'Votre Nom contient des caractères non autorisés !';
    }
    //contrôle Prénom
    $firstName = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
    if (empty($firstName)) {
        $errors['firstName'] = 'Veuillez renseigner votre Prénom.';
    } elseif (!preg_match($regexName, $firstName)) {
        $errors['firstName'] = 'Votre Prénom contient des caractères non autorisés !';
    }
    //contrôle Date de naissance
    $birthDate = trim(filter_input(INPUT_POST, 'birthDate', FILTER_SANITIZE_STRING));
    if (!preg_match($regexDate, $birthDate)) {
        $errors['birthDate'] = 'Veuillez renseigner une date valide.';
    }
    //contrôle téléphone
    $phone = $_POST['phone'];
    if (!empty($phone) && !preg_match($regexPhone, $phone)) {
        $errors['phone'] = 'Veuillez saisir un numéro de téléphone valide.';
    }
    //contrôle adresse mail
    $mailbox = trim(htmlspecialchars($_POST['mailbox']));
    if (empty($mailbox)) {
        $errors['mailbox'] = 'Veuillez renseigner votre adresse mail.';
    } elseif (!filter_var($mailbox, FILTER_VALIDATE_EMAIL)) {
        $errors['mailbox'] = 'Veuillez saisir une adresse mail valide.';
    }
    //contrôle Date du rendez-vous
    $date = $_POST['date'];
    if (!preg_match($regexDatetime, $date)) {
        $errors['date'] = 'Veuillez renseigner une date et une heure valide.';
    }
}
?>
<h1 class="text-light text-center">E2N | Ajout patient et rendez-vous :</h1>
<div class="container col-12">
    <form class="form bg-dark text-light" action="#" method="post" novalidate>
        <div class="form group">
            <label class="form-check-label" for="lastName">Nom :</label>
            <span class="text-danger float-right"><?= ($errors['lastName']) ?? '' ?></span>
            <input name="lastName" class="form-control" id="lastName" type="text"
                   value="<?= $_POST['lastName'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="form-check-label" for="firstName">Prénom :</label>
            <span class="text-danger float-right"><?= ($errors['firstName']) ?? '' ?></span>
            <input name="firstName" class="form-control" id="firstName" type="text"
                   value="<?= $_POST['firstName'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="form-check-label" for="birthDate">Date de naissance :</label>
            <span class="text-danger float-right"><?= ($errors['birthDate']) ?? '' ?></span>
            <input name="birthDate" class="form-control" id="birthdate" type="date"
                   value="<?= $_POST['birthDate'] ?? '' ?>">
        </div>
        <div class="form group">
            <label for="phone">Téléphone : ( Facultatif )</label>
            <span class="text-danger float-right"><?= ($errors['phone']) ?? '' ?></span>
            <input name="phone" class="form-control" id="phone" type="text" placeholder="format : 0000000000"
                   value="<?= $_POST['phone'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="form-check-label" for="mailbox">Adresse mail :</label>
            <span class="text-danger float-right"><?= ($errors['mailbox']) ?? '' ?></span>
            <input name="mailbox" class="form-control" id="mailbox" type="text" value="<?= $_POST['mailbox'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="date">Date et heure du rendez-vous:</label>
            <span class="text-danger float-right"><?= ($errors['date']) ?? '' ?></span>
            <input class="form-control" id="date" name="date" type="datetime-local"
                   value="<?= $_POST['date'] ?? '' ?>"  min="">
        </div>
        <button name="submit" class="btn btn-success form-control mt-4" type="submit">Enregistrer</button>
    </form>
</div>
<?php
if (isset($_POST['submit']) && count($errors) == 0) {
    $dsn = 'mysql:dbname=' .DB. '; host=' .HOST;
    //création patient
    try {
        $dbh = new PDO($dsn, USER, PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare('INSERT INTO `patients` (lastname, firstname, birthdate, phone, mail)
VALUES (:lastName, :firstName, :birthDate, :phone, :mailbox)');
        $sth->bindValue(':lastName', $lastName, PDO::PARAM_STR);
        $sth->bindValue(':firstName', $firstName, PDO::PARAM_STR);
        $sth->bindValue(':birthDate', $birthDate, PDO::PARAM_STR);
        $sth->bindValue(':phone', $phone, PDO::PARAM_STR);
        $sth->bindValue(':mailbox', $mailbox, PDO::PARAM_STR);
        $sth->execute();
        echo '
<script>
alert("Patient créer.")
</script>';
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
    try {
        $sth = $dbh->prepare('INSERT INTO `appointments` (dateHour, idPatients)
VALUES (:dateHour, :idPatients)');
        $sth->bindValue(':dateHour', $date, PDO::PARAM_STR);
        $sth->bindValue(':idPatients', $mailbox, PDO::PARAM_STR);
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
</body>
</html>