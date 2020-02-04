<?php
$title = 'E2N | Profil Patient';
require_once 'header.php';
require_once 'parameters.php';
$lastName = $firstName = $birthDate = $phone = $mailbox = '';
$regexName = "/^[A-Za-zéÉ][A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{1,12}+((-| ?)[A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{0,11})$/";
$regexPhone = "/^0[3679]([0-9]{2}){4}$/";
$regexDate = "/^((?:19|20)[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
$errors = [];
if (empty($_GET['nom'])){
    header('location: liste-patients.php');
    exit();
}
try {
    $dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
    $db = new PDO($dsn, USER, PASSWORD);
    $req = $db->prepare('SELECT `lastname`, `firstname`, DATE_FORMAT(`birthdate`, \'%d-%m-%Y\') `birthdate`, `phone`, `mail` FROM `patients` WHERE `lastname` = ?');
    $req->execute(array($_GET['nom']));
    $patients = $req->fetch();
} catch (Exception $ex) {
    die('Connexion échoué');
}
if (isset($_POST['submit'])){
    $_POST['submit'] = 'alreadySubmitted';
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
<div class="text-center" id="patientInformations">
    <h1 class="text-center text-light">E2N | Informations patient :</h1>
    <p class="text-light">Nom : <?= $patients['lastname'] ?></p>
    <p class="text-light">Prénom : <?= $patients['firstname'] ?></p>
    <p class="text-light">Date de naissance : <?= $patients['birthdate'] ?></p>
    <?php if (!empty($patients['phone'])) {
        echo '<p class="text-light">Téléphone : ' . $patients['phone'] . '</p>';
    } ?>
    <p class="text-light">Adresse mail : <?= $patients['mail'] ?></p>
    <button class="btn btn-warning" id="modify">Modifier les informations du patient</button>
</div>
<div class="container col-12" id="modifyInformations">
    <i id="return" class="fas fa-arrow-left ml-3 mt-3 text-primary" style="font-size: 50px;"></i>
    <h1 class="text-center text-light">E2N | Modifier patient :</h1>
    <form action="#" method="post" novalidate>
        <div class="form group">
            <label class="text-light form-check-label" for="lastName">Nom :</label>
            <span class="text-danger float-right"><?= ($errors['lastName']) ?? '' ?></span>
            <input name="lastName" class="form-control" id="lastName" type="text"
                   placeholder="<?= $patients['lastname'] ?>" value="<?= $_POST['lastName'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="firstName">Prénom :</label>
            <span class="text-danger float-right"><?= ($errors['firstName']) ?? '' ?></span>
            <input name="firstName" class="form-control" id="firstName" type="text"
                   placeholder="<?= $patients['firstname'] ?>" value="<?= $_POST['firstName'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="birthDate">Date de naissance :</label>
            <span class="text-danger float-right"><?= ($errors['birthDate']) ?? '' ?></span>
            <input name="birthDate" class="form-control" id="birthdate"
                   type="date" value="<?= $_POST['birthDate'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="phone">Téléphone : ( Facultatif )</label>
            <span class="text-danger float-right"><?= ($errors['phone']) ?? '' ?></span>
            <input name="phone" class="form-control" id="phone" type="text" placeholder="<?= $patients['phone'] ?>"
                   value="<?= $_POST['phone'] ?? '' ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="mailbox">Adresse mail :</label>
            <span class="text-danger float-right"><?= ($errors['mailbox']) ?? '' ?></span>
            <input name="mailbox" class="form-control" id="mailbox" type="text" placeholder="<?= $patients['mail'] ?>"
                   value="<?= $_POST['mail'] ?? '' ?>">
        </div>
        <button class="btn btn-info form-control mt-4 mb-3" name="submit" id="submit" type="submit" value="<?= $_POST['submit'] ?? '' ?>">Modifier</button>
    </form>
</div>
<?php
if (isset($_POST['submit']) && empty($errors['lastName']) && empty($errors['firstName']) && empty($errors['birthDate']) && empty($errors['phone']) && empty($errors['mailbox'])) {
    $dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $birthDate = $_POST['birthDate'];
        $phone = $_POST['phone'];
        $mailbox = $_POST['mailbox'];
        $sth = $db->prepare('UPDATE `patients` SET `lastname` = :lastName WHERE `id` = ?');
        /*        $sth = $db->prepare('INSERT INTO `patients` WHERE `lastname` (lastname, firstname, birthdate, phone, mail)
        VALUES (:lastName, :firstName, :birthDate, :phone, :mailbox)');*/
        $sth->execute(array(
            ':lastName' => $lastName,
          ));
        echo "Entrée ajoutée dans la table";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo"
        crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>