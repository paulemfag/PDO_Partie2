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
$query = 'SELECT `id`, `dateHour`, `idPatients` FROM `appointments`';
$appointmentsQueryStat = $db->query($query);
$appointmentsList = $appointmentsQueryStat->fetchAll(PDO::FETCH_ASSOC); ?>
<table class="table table-dark">
    <thead>
    <th>id :</th>
    <th>Date et heure :</th>
    <th>id du patient :</th>
    </thead>
    <tbody>
    <?php foreach ($appointmentsList AS $appointment): ?>
        <tr>
            <td><?= $appointment['id'] ?></td>
            <td><?= $appointment['dateHour'] ?></td>
            <td><?= $appointment['idPatients'] ?></td>
        </tr>
    <?php endforeach;
    $querybis = 'SELECT * FROM `patients` WHERE `id` = ' .$appointement['idPatients'];?>
    </tbody>
</table>
</body>
</html>