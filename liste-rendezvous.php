<?php
$title = 'E2N | Liste rendez-vous';
require_once 'header.php';
require_once 'parameters.php';
$dsn = 'mysql:dbname=' . DB . '; host=' . HOST;
try {
    $db = new PDO($dsn, USER, PASSWORD);
} catch (Exception $ex) {
    die('Connexion échoué');
}
$query = 'SELECT `id`, `dateHour`, `idPatients` FROM `appointments` ORDER BY `dateHour` ASC';
$appointmentsQueryStat = $db->query($query);
$appointmentsList = $appointmentsQueryStat->fetchAll(PDO::FETCH_ASSOC); ?>
<h1 class="text-center text-light">E2N | Liste des rendez-vous :</h1>
<table class="table table-dark">
    <thead>
    <th>id :</th>
    <th>Date et heure :</th>
    <th>id du patient :</th>
    <th>infos rendez-vous :</th>
    <th>Supprimer le rendez-vous :</th>
    </thead>
    <tbody>
    <?php foreach ($appointmentsList AS $appointment): ?>
        <tr>
            <td><?= $appointment['id'] ?></td>
            <td><?= $appointment['dateHour'] ?></td>
            <td><?= $appointment['idPatients'] ?></td>
            <td><a title="Infos rendez-vous" href="rendezvous.php?idpatient=<?= $appointment['idPatients'] ?>&amp;dateetheure=<?= $appointment['dateHour'] ?>"class="btn btn-sm btn-info ml-2" ><i class="fas fa-calendar-day"></i></a></td>
            <td><button class="btn btn-sm btn-danger" type="button"><i class="fas fa-calendar-times"></i></button></td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
</body>
</html>