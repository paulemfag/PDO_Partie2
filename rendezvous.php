<?php
$title = 'E2N | Infos Rendez-vous';
require_once 'header.php';
require_once 'parameters.php';
if (empty($_GET['idpatient'])){
    header('location: liste-rendezvous.php');
    exit();
}
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
$db = new PDO($dsn, USER, PASSWORD);
//rendez vous correspondant
try {
    $req = $db->prepare('SELECT `idPatients`, `dateHour` FROM `appointments` WHERE `idPatients` = ?');
    $req->execute(array($_GET['idpatient']));
    $appointments = $req->fetch();
} catch (Exception $ex) {
    die('Connexion échoué');
}
//patient coresspondant
try {
    $req = $db->prepare('SELECT `lastname`, `firstname` FROM `patients` WHERE `id` = ?');
    $req->execute(array($_GET['idpatient']));
    $patients = $req->fetch();
} catch (Exception $ex) {
    die('Connexion échoué');
}
?>
<div class="text-center text-light" id="appointmentInformations">
    <h1>E2N | Informations rendez-vous :</h1>
    <p>Date et heure : <?= $appointments['dateHour'] ?></p>
    <p>Nom du patient : <?= $patients['lastname'] ?></p>
    <p>Prénom du patient : <?= $patients['firstname'] ?></p>
    <button class="btn btn-warning" id="modify">Modifier les informations du rendez-vous</button>
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
if (isset($_POST['submit']) && empty($errors)) {
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $lastName = $_POST['lastName'];
        $firstName = $_POST['firstName'];
        $birthDate = $_POST['birthDate'];
        $phone = $_POST['phone'];
        $mailbox = $_POST['mailbox'];
        $sth = $db->prepare('UPDATE `patients` SET lastname=:lastName, WHERE `lastname` = ?');
        $sth->execute(array(
            $sth->bindValue(':lastName', $lastName, PDO::PARAM_STR),
            $sth->bindValue(':firstName', $firstName, PDO::PARAM_STR),
            $sth->bindValue(':birthDate', $birthDate, PDO::PARAM_STR),
            $sth->bindValue(':phone', $phone, PDO::PARAM_STR),
            $sth->bindValue(':mailbox', $mailbox, PDO::PARAM_STR),
        ));
        echo "Entrée ajoutée dans la table";
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/rdv.js"></script>
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