<?php
$title = 'E2N | Profil Patient';
require_once 'header.php';
require_once 'parameters.php';
$lastName = $firstName = $birthDate = $phone = $mailbox = '';
$regexName = "/^[A-Za-zéÉ][A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{1,12}+((-| ?)[A-Za-záàâäãåçéèêëíìîïñóòôöõúùûüýÿæœ]{0,11})$/";
$regexPhone = "/^0[3679]([0-9]{2}){4}$/";
$regexDate = "/^((?:19|20)[0-9]{2})-(0[1-9]|1[0-2])-(0[1-9]|[12][0-9]|3[01])$/";
$errors = [];
if (empty($_GET['nom'])) {
    header('location: liste-patients.php');
    exit();
}
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
$db = new PDO($dsn, USER, PASSWORD);
//récupération des infos du patient
try {
    $req = $db->prepare('SELECT `id`, `lastname`, `firstname`, DATE_FORMAT(`birthdate`, \'%d/%m/%Y\') `birthdate`, `birthdate` AS `date`, `phone`, `mail` FROM `patients` WHERE `lastname` = ?');
    $req->execute(array($_GET['nom']));
    $patients = $req->fetch();
} catch (Exception $ex) {
    die('Connexion échoué');
}
//récupération des infos rendez-vous du patient
try {
    $req = $db->prepare('SELECT DATE_FORMAT(`dateHour`, \'%d-%m-%Y\ à %HH%i\') `dateHour` FROM `appointments` WHERE `idPatients` = ? ORDER BY `dateHour` ASC');
    $req->execute(array($_GET['id']));
    $patientAppointments = $req->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    die('Connexion échoué');
}
if (isset($_POST['submit'])) {
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
<div class="text-center text-light" id="patientInformations">
    <h1>E2N | Profil Patient :</h1>
    <p>Nom : <?= $patients['lastname'] ?></p>
    <p>Prénom : <?= $patients['firstname'] ?></p>
    <p>Date de naissance : <?= $patients['birthdate'] ?></p>
    <?php if (!empty($patients['phone'])) {
        echo '<p class="text-light">Téléphone : ' . $patients['phone'] . '</p>';
    } ?>
    <p class="text-light">Adresse mail : <?= $patients['mail'] ?></p>
    <button class="btn btn-warning" id="modify">Modifier les informations du patient</button>
</div>
<?php if (!empty ($patientAppointments)) : ?>
<div class="text-light mt-2" id="appointments">
    <h2 class="text-center">Liste des rendez-vous :</h2>
    <ul>
        <?php
        foreach ($patientAppointments as $patientAppointment) : ; ?>
            <li><?= 'Rendez vous le ' . $patientAppointment['dateHour'] ?></li>
        <?php endforeach; ?>
    </ul>
</div>
<?php endif; ?>
<div class="container col-12" id="modifyInformations">
    <i id="return" class="fas fa-arrow-left ml-3 mt-3 text-primary" style="font-size: 50px;"></i>
    <h1 class="text-center text-light">E2N | Modifier patient :</h1>
    <form action="#" method="post" novalidate>
        <div class="form group">
            <label class="text-light form-check-label" for="lastName">Nom :</label>
            <span class="text-danger float-right"><?= ($errors['lastName']) ?? '' ?></span>
            <input name="lastName" class="form-control" id="lastName" type="text"
                   value="<?php if (isset($_POST['lastName'])) {
                       echo $_POST['lastName'];
                   } else {
                       echo $patients['lastname'];
                   } ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="firstName">Prénom :</label>
            <span class="text-danger float-right"><?= ($errors['firstName']) ?? '' ?></span>
            <input name="firstName" class="form-control" id="firstName" type="text"
                   placeholder="<?= $patients['firstname'] ?>" value="<?php if (isset($_POST['firstName'])) {
                echo $_POST['firstName'];
            } else {
                echo $patients['firstname'];
            } ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="birthDate">Date de naissance :</label>
            <span class="text-danger float-right"><?= ($errors['birthDate']) ?? '' ?></span>
            <input name="birthDate" class="form-control" id="birthdate"
                   type="date" value="<?php if (isset($_POST['birthDate'])) {
                echo $_POST['birthDate'];
            } else {
                echo $patients['date'];
            } ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="phone">Téléphone : ( Facultatif )</label>
            <span class="text-danger float-right"><?= ($errors['phone']) ?? '' ?></span>
            <input name="phone" class="form-control" id="phone" type="text" placeholder="<?= $patients['phone'] ?>"
                   value="<?php if (isset($_POST['phone'])) {
                       echo $_POST['phone'];
                   } else {
                       echo $patients['phone'] ?? '';
                   } ?>">
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="mailbox">Adresse mail :</label>
            <span class="text-danger float-right"><?= ($errors['mailbox']) ?? '' ?></span>
            <input name="mailbox" class="form-control" id="mailbox" type="text"
                   value="<?php if (isset($_POST['mailbox'])) {
                       echo $_POST['mailbox'];
                   } else {
                       echo $patients['mail'];
                   } ?>">
        </div>
        <button class="btn btn-info form-control mt-4 mb-3" name="submit" id="submit" type="submit"
                value="<?= $_POST['submit'] ?? '' ?>">Modifier
        </button>
    </form>
</div>
<?php
if (isset($_POST['submit']) && count($errors) == 0) {
    $phone = $_POST['phone'];
    $idGet = filter_input(INPUT_GET, 'id', FILTER_SANITIZE_NUMBER_INT);
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $db->prepare('UPDATE `patients` SET lastname = :lastName, firstname = :firstName, birthdate = :birthDate, phone = :phone, mail = :mailbox WHERE `id` = :idGet');
            $sth->bindValue(':idGet', $idGet, PDO::PARAM_INT);
            $sth->bindValue(':lastName', $lastName, PDO::PARAM_STR);
            $sth->bindValue(':firstName', $firstName, PDO::PARAM_STR);
            $sth->bindValue(':birthDate', $birthDate, PDO::PARAM_STR);
            $sth->bindValue(':phone', $phone, PDO::PARAM_STR);
            $sth->bindValue(':mailbox', $mailbox, PDO::PARAM_STR);
            $sth->execute();
        ?>
        <script>
            alert("Le patient a bien été modifié");
            function redir(){
                self.location.href="liste-patients.php"
            }
            redir();
        </script><?php
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