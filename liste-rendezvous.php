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
$appointmentsList = $appointmentsQueryStat->fetchAll(PDO::FETCH_ASSOC);
?>
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
            <td><a title="Infos rendez-vous"
                   href="rendezvous.php?idpatient=<?= $appointment['idPatients'] ?>&amp;dateetheure=<?= $appointment['dateHour'] ?>"
                   class="btn btn-sm btn-info ml-2"><i class="fas fa-calendar-day"></i></a></td>
            <td>
                <a href="<?= '?id=' .$appointment['id'] ?>" id="<?= $appointment['id'] ?>" class="btn btn-sm btn-danger delete" type="button"><i
                            class="fas fa-calendar-times"></i></a>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
</table>
<?php
if (isset($_GET['id'])){
    $idGet = $_GET['id'];
    try {
        $db = new PDO($dsn, USER, PASSWORD);
        $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $sth = $db->prepare('DELETE FROM `appointments` WHERE `id` = ?');
        $sth->execute([$idGet]);
        ?>
        <script>
            alert("Le rendez-vous a bien été supprimé");
            function redir(){
                self.location.href="liste-rendezvous.php"
            }
            redir();
        </script><?php
    } catch (PDOException $e) {
        echo "Erreur : " . $e->getMessage();
    }
}
?>
<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="assets/js/liste-rendezvous.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>