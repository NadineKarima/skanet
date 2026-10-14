<?php
if (isset($_SESSION['flash'])):

    $flash = $_SESSION['flash'];
    unset($_SESSION['flash']);

    $fn = ($flash['type'] === 'success') ? 'sukses' : 'gagal';

?>

<script>
document.addEventListener("DOMContentLoaded", function () {
    <?= $fn ?>(<?= json_encode($flash['pesan'], JSON_UNESCAPED_UNICODE) ?>);
});
</script>

<?php endif; ?>
