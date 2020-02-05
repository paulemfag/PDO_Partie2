<?php
$title = 'E2N | Liste patients';
require_once 'header.php';
require_once 'parameters.php';
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
try {
    $db = new PDO($dsn, USER, PASSWORD);
} catch (Exception $ex) {
    die('Connexion échoué');
}
$query = 'SELECT * FROM `patients` ORDER BY `lastname` ASC';
$patientsQueryStat = $db->query($query);
$patientsList = $patientsQueryStat->fetchAll(PDO::FETCH_ASSOC); ?>
<h1 class="text-center text-light">E2N | Liste des patients :</h1>
<table class="table table-dark">
    <thead>
    <th>Nom :</th>
    <th>Prénom :</th>
    <th>Profil :</th>
    </thead>
    <tbody>
    <?php foreach ($patientsList AS $patient): ?>
    <tr>
        <td><?= $patient['lastname'] ?></td>
        <td><?= $patient['firstname'] ?></td>
        <td><a title="Profil de <?= $patient['lastname']. ' ' .$patient['firstname'] ?>" href="profil-patient.php?nom=<?= $patient['lastname'] ?>&amp;prénom=<?= $patient['firstname'] ?>&amp;id=<?= $patient['id'] ?>" class="btn btn-sm btn-info ml-2" ><i class="fas fa-user-circle"></i></a></td>
    </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<script src="/assets/js/jquery-3.3.1.min.js"></script>
<script src="/assets/js/script.js"></script>
<script src="https://code.jquery.com/jquery-3.3.1.slim.min.js" integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js" integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js" integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous"></script>
</body>
</html>
