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
            <td><a title="Infos rendez-vous"
                   href="rendezvous.php?idpatient=<?= $appointment['idPatients'] ?>&amp;dateetheure=<?= $appointment['dateHour'] ?>"
                   class="btn btn-sm btn-info ml-2"><i class="fas fa-calendar-day"></i></a></td>
            <td>
                <button class="delete" id="<?= $appointment['id'] ?>" class="btn btn-sm btn-danger" type="button"><i class="fas fa-calendar-times"></i></button>
            </td>
        </tr>
    <?php endforeach; ?>
    </tbody>
    <!-- Modal -->
    <div class="modal fade" id="deleteappointment" tabindex="-1" role="dialog"
         aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Modal title</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    ...
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary">Save changes</button>
                </div>
            </div>
        </div>
    </div>
</table>
<script>
    $(function () {
        $('.delete').on('click', function () {
            $('#deleteappointment').modal('show');
        })
    })
</script>
<script src="assets/js/jquery-3.3.1.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1"
        crossorigin="anonymous"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM"
        crossorigin="anonymous"></script>
</body>
</html>