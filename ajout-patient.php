<?php
require_once 'parameters.php';
$title = 'E2N | Ajout patient';
require_once 'header.php';
$lastName = $firstName = $birthDate = $phone = $mailbox = '';
$regexName = "/^[A-Za-zéÉ][A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{1,12}+((-| ?)[A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{0,11})$/";
$regexPhone = "/^0[3679]([0-9]{2}){4}$/";
$regexDate = "/^((?:19|20)[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
$errors = [];
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
    if (empty($birthDate)) {
        $errors['birthDate'] = 'Veuillez renseigner votre date de naissance.';
    } elseif (!preg_match($regexDate, $birthDate)) {
        $errors['birthDate'] = 'Votre Date contient des caractères non autorisés !';
    }
    //contrôle téléphone
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
}
?>
<h1 class="text-light text-center">E2N | Ajouter un patient :</h1>
<div class="container col-12">
<form class="form bg-dark text-light" action="#" method="post" novalidate>
    <div class="form group">
        <label class="form-check-label" for="lastName">Nom :</label>
        <span class="text-danger float-right"><?= ($errors['lastName']) ?? '' ?></span>
        <input name="lastName" class="form-control" id="lastName" type="text" value="<?= $_POST['lastName'] ?? '' ?>">
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
        <input name="birthDate" class="form-control" id="birthdate" placeholder="format aaaa-mm-jj" type="text"
               value="<?= $_POST['birthDate'] ?? '' ?>">
    </div>
    <div class="form group">
        <label for="phone">Téléphone : ( Facultatif )</label>
        <span class="text-danger float-right"><?= ($errors['phone']) ?? '' ?></span>
        <input name="phone" class="form-control" id="phone" type="text" placeholder="0000000000"
               value="<?= $_POST['phone'] ?? '' ?>">
    </div>
    <div class="form group">
        <label class="form-check-label" for="mailbox">Adresse mail :</label>
        <span class="text-danger float-right"><?= ($errors['mailbox']) ?? '' ?></span>
        <input name="mailbox" class="form-control" id="mailbox" type="text" value="<?= $_POST['mailbox'] ?? '' ?>">
    </div>
    <button name="submit" class="btn btn-success form-control mt-4" type="submit" value="">Modifier</button>
</form>
</div>
<?php
if (isset($_POST['submit']) && empty($errors)) {
    $dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
    try {
        $dbh = new PDO($dsn, USER, PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $birthDate = $_POST['birthDate'];
        $phone = $_POST['phone'];
        $mailbox = $_POST['mailbox'];
        $sth = $dbh->prepare('INSERT INTO `patients` (lastname, firstname, birthdate, phone, mail)
VALUES (:lastName, :firstName, :birthDate, :phone, :mailbox)');
        $sth->execute(array(
            ':lastName' => $lastName,
            ':firstName' => $firstName,
            ':birthDate' => $birthDate,
            ':phone' => $phone,
            ':mailbox' => $mailbox));
        echo '<script class="bg-dark">alert("Entrée ajoutée dans la table");</script>';
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
</body>
</html>
