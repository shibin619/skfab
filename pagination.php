<script>
$(document).ready(function () {
    $('#datatable').DataTable({
        "processing": true,
        "serverSide": true,
        "ajax": {
            "url": "<?= base_url('users/fetchUsers') ?>",
            "type": "POST"
        }
    });
});
</script>
