<?php
$title = 'E2N | Profil Patient';
require_once 'header.php';
require_once 'parameters.php';
$lastName = $firstName = $birthDate = $phone = $mailbox = '';
$regexName = "/^[A-Za-zéÉ][A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{1,12}+((-| ?)[A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{0,11})$/";
$regexPhone = "/^0[3679]([0-9]{2}){4}$/";
$regexDate = "/^((?:19|20)[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
$errors = [];
try {
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
$db = new PDO($dsn, USER, PASSWORD);
$req = $db->prepare('SELECT `lastname`, `firstname`, DATE_FORMAT(`birthdate`, \'%d-%m-%Y\') `birthdate`, `phone`, `mail` FROM `patients` WHERE `lastname` = ?');
$req->execute(array($_GET['nom']));
$patients = $req->fetch();
}
catch (Exception $ex) {
    die('Connexion échoué');
}
?>
<h1 class="text-center">Informations patient :</h1>
<div id="patientInformations">
<p>Nom : <?= $patients['lastname'] ?></p>
<p>Prénom : <?= $patients['firstname'] ?></p>
<p>Date de naissance : <?= $patients['birthdate'] ?></p>
<?php if (!empty($patients['phone'])){
  echo '<p>Téléphone : ' .$patients['phone']. '</p>';
}?>
<p>Adresse mail : <?= $patients['mail'] ?></p>
<button id="modify">Modifier les informations du patient</button>
</div>
<div id="modifyInformations">
<form action="#" method="post" novalidate>
    <div class="form group">
        <label for="lastName">Nom :</label>
        <span class="text-danger"><?= ($errors['lastName']) ?? '' ?></span>
        <input name="lastName" id="lastName" type="text" placeholder="<?= $patients['lastname']?>" value="<?= $_POST['lastName'] ?? ''?>">
    </div>
    <div class="form group">
        <label for="firstName">Prénom :</label>
        <span class="text-danger"><?= ($errors['firstName']) ?? '' ?></span>
        <input name="firstName" id="firstName" type="text" placeholder="<?= $patients['firstname']?>" value="<?= $_POST['firstName'] ?? '' ?>">
    </div>
    <div class="form group">
        <label for="birthDate">Date de naissance :</label>
        <span class="text-danger"><?= ($errors['birthDate']) ?? '' ?></span>
        <input name="birthDate" id="birthdate" placeholder="<?= $patients['birthdate']?>" type="text" value="<?= $_POST['birthdate'] ?? '' ?>">
    </div>
    <div class="form group">
        <label for="phone">Téléphone : ( Facultatif )</label>
        <span class="text-danger"><?= ($errors['phone']) ?? '' ?></span>
        <input name="phone" id="phone" type="text" placeholder="<?= $patients['phone']?>" value="<?= $_POST['phone'] ?? $patients['phone']?>">
    </div>
    <div class="form group">
        <label for="mailbox">Adresse mail :</label>
        <span class="text-danger"><?= ($errors['mailbox']) ?? '' ?></span>
        <input name="mailbox" id="mailbox" type="text" placeholder="<?= $patients['mail'] ?>" value="<?= $_POST['mail'] ?? '' ?>">
    </div>
    <input name="submit" type="submit" value="Envoyer">
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
        $sth = $db->prepare('UPDATE `patients` SET `lastname` = :lastname WHERE `id` = $patient[\'id\']');
/*        $sth = $db->prepare('INSERT INTO `patients` WHERE `lastname` (lastname, firstname, birthdate, phone, mail)
VALUES (:lastName, :firstName, :birthDate, :phone, :mailbox)');*/
        $sth->execute(array(
            ':lastName' => $lastName,
            ':firstName' => $firstName,
            ':birthDate' => $birthDate,
            ':phone' => $phone,
            ':mailbox' => $mailbox));
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
