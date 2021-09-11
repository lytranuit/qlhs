<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<!-- ============================================================== -->
<!-- userheader -->
<!-- ============================================================== -->
<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <h5 class="card-header drag-handle">
                <a class="btn btn-success btn-sm" href="<?= base_url("admin/user/add") ?>">Thêm</a>
            </h5>
            <div class="card-body">

                <div class="table-responsive-md">
                    <table id="quanlytin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Username</th>
                                <th>Tên</th>
                                <th>Mô tả</th>
                                <th>Nhóm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>

                        </tbody>
                    </table>
                </div>
            </div>
        </section>
    </div>
</div>

<?= $this->endSection() ?>
<!-- Style --->
<?= $this->section("style") ?>

<link rel="stylesheet" href="<?= base_url("assets/lib/datatables/datatables.min.css") ?> " ?>
<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url('assets/lib/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/datatables/jquery.highlight.js') ?>"></script>

<script type="text/javascript">
    $(document).ready(function() {
        let data = {};
        data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
        $('#quanlytin').DataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": path + "admin/user/table",
                "dataType": "json",
                "type": "POST",
                "data": data
            },
            "columns": [{
                    "data": "id"
                }, {
                    "data": "username"
                }, {
                    "data": "name"
                },
                {
                    "data": "description"
                },
                {
                    "data": "groups"
                },
                {
                    "data": "action"
                }
            ]

        });
    });
</script>

<?= $this->endSection() ?>