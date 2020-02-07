<?php
$title = 'E2N | Ajout Rendez-vous';
require_once 'header.php';
require_once 'parameters.php';
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
try {
    $db = new PDO($dsn, USER, PASSWORD);
} catch (Exception $ex) {
    die('Connexion échoué');
}
$query = 'SELECT `id`, `lastname`, `firstname`, DATE_FORMAT(`birthdate`, \'%d-%m-%Y\') `birthdate`, `phone`, `mail` FROM `patients` ORDER BY `lastname` ASC';
$patientsQueryStat = $db->query($query);
$patientsList = $patientsQueryStat->fetchAll(PDO::FETCH_ASSOC);
$patientslist = $date = '';
$errors = [];
$dateRegex = '/^([1-2]{1})([0-9]{3})(-)([0-1]{1})([0-9]{1})(-)([0-3]{1})([0-9]{1})([T])([0-9]{2})(:)([0-9]{2})$/';
if (isset($_POST['submit'])) {
    //contrôle Date du rendez-vous
    $date = $_POST['date'];
    if (!preg_match($dateRegex, $date)) {
        $errors['date'] = 'Veuillez renseigner une date et une heure valide.';
    }
}
?>
<h1 class="text-center text-light">E2N | Ajouter un Rendez-vous :</h1>
<div class="container col-12">
    <form action="#" method="post" novalidate>
        <div class="form-group">
            <label class="text-light form-check-label" for="patientslist">Sélectionner un patient : </label>
            <span class="text-danger"><?= ($errors['patientslist']) ?? '' ?></span>
            <select id="patientslist" name="patientslist">
                <?php foreach ($patientsList AS $patient): ?>
                    <option><?= $patient['id']. ' ' .$patient['lastname']. ' ' .$patient['firstname'] ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form group">
            <label class="text-light form-check-label" for="date">Date et heure :</label>
            <span class="text-danger float-right"><?= ($errors['date']) ?? '' ?></span>
            <input class="form-control" id="date" name="date" type="datetime-local"
                   value="<?= $_POST['date'] ?? '' ?>"  min="">
        </div>
        <button class="btn btn-info form-control mt-4 mb-3" name="submit" id="submit" type="submit"
                value="<?= $_POST['submit'] ?? '' ?>">Envoyer
        </button>
    </form>
</div>
<?php
if (isset($_POST['submit']) && count($errors) == 0) {
    $patientslist = $_POST['patientslist'];
    try {
        $dbh = new PDO($dsn, USER, PASSWORD);
        $dbh->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $dbh->prepare('INSERT INTO `appointments` (dateHour, idPatients)
VALUES (:dateHour, :idPatients)');
        $sth->bindValue(':dateHour', $date, PDO::PARAM_STR);
        $sth->bindValue(':idPatients', $patientslist, PDO::PARAM_INT);
        $sth->execute();
        echo '
<script>
    alert("Entrée ajoutée dans la table.");
</script>';
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
} ?>
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