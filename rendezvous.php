<?php
$title = 'E2N | Infos Rendez-vous';
require_once 'header.php';
require_once 'parameters.php';
if (empty($_GET['idpatient'])) {
    header('location: liste-rendezvous.php');
    exit();
}
$errors = [];
$dateRegex = '/^([1-2]{1})([0-9]{3})(-)([0-1]{1})([0-9]{1})(-)([0-3]{1})([0-9]{1})([T])([0-9]{2})(:)([0-9]{2})$/';
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
$db = new PDO($dsn, USER, PASSWORD);
//récupération du rendez vous correspondant
try {
    $req = $db->prepare('SELECT `id`, `idPatients`, DATE_FORMAT(`dateHour`, \'%Y-%m-%d\T%H:%i\') `dateHour`,`dateHour` AS `date` FROM `appointments` WHERE `dateHour` = ?');
    $req->execute(array($_GET['dateetheure']));
    $appointments = $req->fetch();
} catch (Exception $ex) {
    die('Connexion échoué');
}
//récupération du patient coresspondant
try {
    $req = $db->prepare('SELECT `id`, `lastname`, `firstname` FROM `patients` WHERE `id` = ?');
    $req->execute(array($_GET['idpatient']));
    $patients = $req->fetch();
} catch (Exception $ex) {
    die('Connexion échoué');
}
//récupération des patients
try {
    $query = 'SELECT `id`, `lastname`, `firstname` FROM `patients` ORDER BY `lastname` ASC';
    $patientsQueryStat = $db->query($query);
    $patientsList = $patientsQueryStat->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $ex) {
    die('Connexion échoué');
}
//vérifications avant envoi
if (isset($_POST['submit'])) {
    //contrôle Date du rendez-vous
    $date = $_POST['date'];
    if (!preg_match($dateRegex, $date)) {
        $errors['date'] = 'Veuillez renseigner une date et une heure valide.';
    }
    $patientid = trim(filter_input(INPUT_POST, 'patientslist', FILTER_SANITIZE_NUMBER_INT));
}
?>
<div class="text-center text-light" id="appointmentInformations">
    <h1>E2N | Informations rendez-vous :</h1>
    <p>Date et heure : <?= $appointments['date'] ?></p>
    <p>Nom du patient : <?= $patients['lastname'] ?></p>
    <p>Prénom du patient : <?= $patients['firstname'] ?></p>
    <button class="btn btn-warning" id="modify">Modifier les informations du rendez-vous</button>
</div>
<div class="container col-12" id="modifyInformations">
    <h1 class="text-center text-light">Modifier rendez-vous :</h1>
    <form id="form" action="#" method="post" novalidate>
        <div class="form group">
            <label class="text-light" for="patientlist">Patient : </label>
            <select name="patientslist" id="patientlist">
                <?php if (isset($_POST['submit'])) { ?>
                    <option selected><?= $_POST['patientslist'] ?></option>
                <?php } else { ?>
                    <option selected><?= $patients['id'] . ' ' . $patients['lastname'] . ' ' . $patients['firstname'] ?></option>
                <?php }
                foreach ($patientsList AS $patient): ?>
                    <option><?= $patient['id'] . ' ' . $patient['lastname'] . ' ' . $patient['firstname'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group">
            <label class="text-light" for="date">Date et heure :</label>
            <span class="text-danger float-right"><?= ($errors['date']) ?? '' ?></span>
            <input name="date" id="date" type="datetime-local" value="<?php if (isset($_POST['date'])) {
                echo $_POST['date'];
            } else {
                echo $appointments['dateHour'];
            } ?>">
        </div>
        <button class="btn btn-info form-control mt-4 mb-3" name="submit" id="submit" type="submit"
                value="<?= $_POST['submit'] ?? '' ?>">Modifier
        </button>
    </form>
</div>
<?php
if (isset($_POST['submit']) && count($errors) == 0) {
    echo $patientid = trim(filter_input(INPUT_POST, 'patientslist', FILTER_SANITIZE_NUMBER_INT));
    try {
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $patientid = filter_input(INPUT_GET, 'idpatient', FILTER_SANITIZE_NUMBER_INT);
        $date = $_POST['date'];
        $appointmentid = $appointments['id'];
        $sth = $db->prepare('UPDATE `appointments` SET idPatients = :patientid, dateHour = :appointmentDateHour WHERE `id` = :appointmentid');
        $sth->bindValue(':patientid', $patientid, PDO::PARAM_INT);
        $sth->bindValue(':appointmentDateHour', $date, PDO::PARAM_STR);
        $sth->bindValue(':appointmentid', $appointmentid, PDO::PARAM_INT);
        $sth->execute(); ?>
        <script>
            alert("Le rendez vous a bien été modifié");
            function redir(){
                self.location.href="liste-rendezvous.php"
            };
            redir();
        </script><?php
    } catch (PDOException $e) {
        die('Connexion échoué');
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