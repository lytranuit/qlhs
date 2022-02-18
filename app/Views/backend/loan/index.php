<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <h5 class="card-header drag-handle">
                <a class="btn btn-success btn-sm" href="<?= base_url("admin/$controller/add") ?>">Đăng ký</a>

            </h5>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlytin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Người yêu cầu</th>
                                <th>Tài liệu</th>
                                <th>Ngày yêu cầu</th>
                                <th>Ghi chú</th>
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
        let table = $('#quanlytin').DataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            // "ordering": false,
            "ajax": {
                "url": path + "admin/<?= $controller ?>/table",
                "dataType": "json",
                "type": "POST",
                'data': function(data) {
                    // Read values
                    let orders = data['order'];
                    for (let i in orders) {
                        let order = orders[i];
                        let column = order['column'];
                        orders[i]['data'] = data['columns'][column]['data'];
                    }
                    data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                }
            },
            "columns": [{
                    "data": "id",
                }, {
                    "data": "name",
                },
                {
                    "data": "documents"
                },
                {
                    "data": "date"
                },
                {
                    "data": "note"
                },
                {
                    "data": "action",
                    "orderable": false
                }
            ],
        });

    });
</script>
<?= $this->endSection() ?>