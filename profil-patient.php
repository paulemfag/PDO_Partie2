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
$req = $db->prepare('SELECT * FROM `patients` WHERE `lastname` = ?');
$req->execute(array($_GET['nom']));
$patients = $req->fetch();
}
catch (Exception $ex) {
    die('Connexion échoué');
}
?>
<form action="#" method="post" novalidate>
    <div class="form group">
        <label for="lastName">Nom :</label>
        <span class="text-danger"><?= ($errors['lastName']) ?? '' ?></span>
        <input name="lastName" id="lastName" type="text" value="<?= $patients['lastname']?>" required>
    </div>
    <div class="form group">
        <label for="firstName">Prénom :</label>
        <span class="text-danger"><?= ($errors['firstName']) ?? '' ?></span>
        <input name="firstName" id="firstName" type="text" value="<?= $patients['firstname'] ?? '' ?>" required>
    </div>
    <div class="form group">
        <label for="birthDate">Date de naissance :</label>
        <span class="text-danger"><?= ($errors['birthDate']) ?? '' ?></span>
        <input name="birthDate" id="birthdate" placeholder="format aaaa-mm-jj" type="text" value="<?= $patients['birthdate'] ?? '' ?>" required>
    </div>
    <div class="form group">
        <label for="phone">Téléphone : ( Facultatif )</label>
        <span class="text-danger"><?= ($errors['phone']) ?? '' ?></span>
        <input name="phone" id="phone" type="text" placeholder="0000000000" value="<?= $patients['phone'] ?? '' ?>">
    </div>
    <div class="form group">
        <label for="mailbox">Adresse mail :</label>
        <span class="text-danger"><?= ($errors['mailbox']) ?? '' ?></span>
        <input name="mailbox" id="mailbox" type="text" value="<?= $patients['mail'] ?? '' ?>" required>
    </div>
    <input name="submit" type="submit" value="Envoyer">
</form>
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
        $sth = $db->prepare('INSERT INTO `patients` WHERE `lastname` (lastname, firstname, birthdate, phone, mail)
VALUES (:lastName, :firstName, :birthDate, :phone, :mailbox)');
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
</body>
</html>
