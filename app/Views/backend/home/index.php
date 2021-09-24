<?= $this->extend('backend/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-purple-gradient shadow-purple">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-laptop report-main-icon bg-icon-purple"></i>
                </div>
                <span class="badge badge-light text-purple">TÀI LIỆU</span>
                <h3 class="my-3"><?= $num_doc ?></h3>

            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-warning-gradient shadow-warning">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-user report-main-icon bg-icon-warning"></i>
                </div>
                <span class="badge badge-light text-warning">Tài liệu trong kho</span>
                <h3 class="my-3"><?= $num_doc_in_inventory ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-danger-gradient shadow-danger">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-danger">TÀI LIỆU Đang CHO MƯỢN</span>
                <h3 class="my-3"><?= $num_doc_in_loan ?></h3>

            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-secondary-gradient shadow-secondary">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-code-branch report-main-icon bg-icon-secondary"></i>
                </div>
                <span class="badge badge-light text-secondary">TÀI LIỆU HẾT HẠN</span>
                <h3 class="my-3"><?= $num_doc_expire ?></h3>

            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
</div>
<div class="row">
    <div class="col-md-6 mt-3">
        <div class="card card-fluid">
            <div class="card-header">
                Tài liệu cần rà soát
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlyreview" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Tài liệu</th>
                                <th>Ngày rà soát</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6 mt-3">
        <div class="card card-fluid">
            <div class="card-header">
                Tài liệu hết hiệu lực
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlyexpire" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Tài liệu</th>
                                <th>Ngày hết hạn</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
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
<script type='text/javascript'>
    $(document).ready(function() {

        $('#quanlyreview').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": path + "admin/<?= $controller ?>/tablereview",
                "dataType": "json",
                "type": "POST",
                'data': function(data) {
                    data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                }
            },
            "columns": [{
                    "data": "name"
                },
                {
                    "data": "date"
                }
            ],
        });
        $('#quanlyexpire').DataTable({
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": path + "admin/<?= $controller ?>/tableexpire",
                "dataType": "json",
                "type": "POST",
                'data': function(data) {
                    data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                }
            },
            "columns": [{
                    "data": "name"
                },
                {
                    "data": "date"
                }
            ],
        });
    });
</script>
<?= $this->endSection() ?>