<script src="assets/vendor/bootstrap/js/bootstrap.bundle.min.js"></script>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.datatables.net/2.3.2/js/dataTables.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
<script src="assets/js/app.js"></script>

<script src="assets/js/datatable.js"></script>

<script src="assets/js/alert.js"></script>

<script src="assets/js/utils.js"></script>

<?php

$page = $_GET['page'] ?? 'dashboard';

if (file_exists("assets/js/$page.js")) {

?>

<script src="assets/js/<?= $page ?>.js"></script>

<?php } ?>

</body>
</html>