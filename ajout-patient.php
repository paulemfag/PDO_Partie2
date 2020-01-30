<?php
require_once 'parameters.php';
$title = 'E2N | Ajout patient';
require_once 'header.php';
$lastName = $firstName = $birthDate = $phone = $mailbox = '';
$regexName = "/^[A-Za-zéÉ][A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{1,12}+((-| ?)[A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{0,11})$/";
$regexPhone = "/^0[3679]([0-9]{2}){4}$/";
$regexDate = "/^((?:19|20)[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
$errors = [];
if(isset($_POST['submit'])){
    //contrôle Nom
    $lastName = trim(filter_input(INPUT_POST,'lastName',FILTER_SANITIZE_STRING));
    if (empty($lastName)) {
        $errors['lastName'] = 'Veuillez renseigner votre Nom.';
    } elseif (!preg_match($regexName, $lastName)) {
        $errors['lastName'] = 'Votre Nom contient des caractères non autorisés !';
    }
    //contrôle Prénom
    $firstName = trim(filter_input(INPUT_POST,'firstName',FILTER_SANITIZE_STRING));
    if (empty($firstName)) {
        $errors['firstName'] = 'Veuillez renseigner votre Prénom.';
    } elseif (!preg_match($regexName, $firstName)) {
        $errors['firstName'] = 'Votre Prénom contient des caractères non autorisés !';
    }
    //contrôle Date de naissance
    $birthDate = trim(filter_input(INPUT_POST,'birthDate',FILTER_SANITIZE_STRING));
    if (empty($birthDate)) {
        $errors['birthDate'] = 'Veuillez renseigner votre date de naissance.';
    } elseif (!preg_match($regexDate, $birthDate)) {
        $errors['birthDate'] = 'Votre Date contient des caractères non autorisés !';
    }
    //contrôle téléphone
    if (!empty($phone)){
        if (!preg_match($regexPhone, $phone)) {
            $errors['phone'] = 'Veuillez saisir un numéro de téléphone valide.';
        }
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
<form action="#" method="post" novalidate>
    <div class="form group">
        <label for="lastName">Nom :</label>
        <span class="text-danger"><?= ($errors['lastName']) ?? '' ?></span>
        <input name="lastName" id="lastName" type="text" value="<?= $_POST['lastName'] ?? '' ?>" required>
    </div>
    <div class="form group">
        <label for="firstName">Prénom :</label>
        <span class="text-danger"><?= ($errors['firstName']) ?? '' ?></span>
        <input name="firstName" id="firstName" type="text" value="<?= $_POST['firstName'] ?? '' ?>" required>
    </div>
    <div class="form group">
        <label for="birthDate">Date de naissance :</label>
        <span class="text-danger"><?= ($errors['birthDate']) ?? '' ?></span>
        <input name="birthDate" id="birthdate" placeholder="format aaaa-mm-jj" type="text" value="<?= $_POST['birthDate'] ?? '' ?>" required>
    </div>
    <div class="form group">
        <label for="phone">Téléphone : ( Facultatif )</label>
        <span class="text-danger"><?= ($errors['phone']) ?? '' ?></span>
        <input name="phone" id="phone" type="text" placeholder="0000000000" value="<?= $_POST['phone'] ?? '' ?>">
    </div>
    <div class="form group">
        <label for="mailbox">Adresse mail :</label>
        <span class="text-danger"><?= ($errors['mailbox']) ?? '' ?></span>
        <input name="mailbox" id="mailbox" type="text" value="<?= $_POST['mailbox'] ?? '' ?>" required>
    </div>
    <input name="submit" type="submit" value="Envoyer">
</form>
<?php
if(isset($_POST['submit']) && empty($errors['lastName']) && empty($errors['firstName']) && empty($errors['birthDate']) && empty($errors['phone']) && empty($errors['mailbox'])){
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
try {
    $dbh =  new PDO($dsn, USER, PASSWORD);
    $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // <== add this line
    $sql = "INSERT INTO `patients` (lastname, firstname, birthdate, phone, mail)
VALUES ('".$_POST["lastName"]."','".$_POST["firstName"]."','".$_POST["birthDate"]."','".$_POST['phone']."','".$_POST['mailbox']."')";
    if ($dbh->query($sql)) {
        echo "<script type= 'text/javascript'>alert('Les informations ont été enregistrées');</script>";
    }
    else{
        echo "<script type= 'text/javascript'>alert('Data not successfully Inserted.');</script>";
    }
    $dbh = null;
}
catch(PDOException $e)
{
    echo $e->getMessage();
}
}
?>
</body>
</html>
