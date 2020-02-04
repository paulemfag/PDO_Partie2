<?php
$title = 'E2N | Ajout Rendez-vous';
require_once 'header.php';
require_once 'parameters.php';
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
try {
    $db = new PDO($dsn, USER, PASSWORD);
} catch (Exception $ex) {
    die('Connexion échoué');
} ?> <h1 class="text-center text-light">E2N | Ajouter un Rendez-vous :</h1>
<?php
/*$query = 'SELECT `lastName`, `firstName`, DATE_FORMAT(`birthDate`, \'%d-%m-%Y\') `birthDate`, `card`, `cardNumber` FROM `patients`';*/
/*$req = $db->prepare('SELECT `lastname`, `firstname`, DATE_FORMAT(`birthdate`, \'%d-%m-%Y\') `birthdate`, `phone`, `mail` FROM `patients`');*/
$query = 'SELECT `id`, `lastname`, `firstname`, DATE_FORMAT(`birthdate`, \'%d-%m-%Y\') `birthdate`, `phone`, `mail` FROM `patients`';
$patientsQueryStat = $db->query($query);
$patientsList = $patientsQueryStat->fetchAll(PDO::FETCH_ASSOC);
$patientslist = $date = '';
$dateRegex = '/^([1-2]{1})([0-9]{3})(-)([0-1]{1})([0-9]{1})(-)([0-3]{1})([0-9]{1})([T])([0-9]{2})(:)([0-9]{2})(:?)([0-9]{0,2}?)$/';
echo $_POST['date'];
if (isset($_POST['submit'])) {
//contrôle Nom
    if ($patientslist === '-- Sélectionner --') {
        $errors['patientslist'] = 'Veuillez selectionner un patient.';
    }
//contrôle Prénom
    $firstName = trim(filter_input(INPUT_POST, 'firstName', FILTER_SANITIZE_STRING));
    if (empty($firstName)) {
        $errors['firstName'] = 'Veuillez renseigner votre Prénom.';
    } elseif (!preg_match($regexName, $firstName)) {
        $errors['firstName'] = 'Votre Prénom contient des caractères non autorisés !';
    }
//contrôle Date de Rendez vous
    $date = trim(filter_input(INPUT_POST, 'date', FILTER_SANITIZE_STRING));
    if (empty($date)) {
        $errors['date'] = 'Veuillez renseigner votre date de naissance.';
    } elseif (!preg_match($dateRegex, $date)) {
        $errors['date'] = 'Votre Date contient des caractères non autorisés !';
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
<div class="container col-12">
    <form action="#" method="post" novalidate>
        <div class="form-group">
        <label class="text-light form-check-label" for="patientslist">Sélectionner un patient : </label>
        <span class="text-danger"><?= ($errors['patientslist']) ?? '' ?></span>
        <select id="patientslist" name="patientslist">
            <option selected disabled><?= $_POST['patientslist'] ?? '-- Sélectionner --' ?></option>
            <?php foreach ($patientsList AS $patient):
                ?>
                <option><?= $patient['id']. ' ' .$patient['lastname'] . ' ' . $patient['firstname'] ?></option>
            <?php
            endforeach; ?>
        </select>
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="date">Date et heure :</label>
            <span class="text-danger float-right"><?= ($errors['date']) ?? '' ?></span>
            <input  class="form-control" id="date" name="date" type="datetime-local"
                    value="<?= $_POST['date'] ?? '' ?>" step="1" max="">
        </div>
        <button class="btn btn-info form-control mt-4 mb-3" name="submit" id="submit" type="submit"
                value="<?= $_POST['submit'] ?? '' ?>">Envoyer
        </button>
    </form>
</div>
<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/ajoutrdv.js"></script>
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
