<?= $this->extend('backend/layouts/main') ?>

<?= $this->section('content') ?>
<div class="row justify-content-center">
    <div class="col-md-6 col-lg-3">
        <div class="card report-card bg-purple-gradient shadow-purple box-hover" data-id="1">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-purple">HSTL</span>
                <h3 class="my-3"><?= $num_doc ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
        <div class="card report-card bg-purple-gradient shadow-purple mt-2 box-hover" data-id="2">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-purple">HSTL LƯU TRỮ</span>
                <h3 class="my-3"><?= $num_doc_in_inventory ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
        <div class="card report-card bg-danger-gradient shadow-danger mt-2 box-hover" data-id="3">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-purple">HSTL CHO MƯỢN</span>
                <h3 class="my-3"><?= $num_doc_in_loan ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->

        <div class="card report-card bg-danger-gradient shadow-danger mt-2 box-hover" data-id="4">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-purple">HSTL HẾT HẠN LƯU TRỮ</span>
                <h3 class="my-3"><?= $num_doc_expire ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
        <div class="card report-card bg-secondary-gradient shadow-secondary mt-2 box-hover" data-id="5">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-purple">HSTL ĐẾN HẠN</span>
                <h3 class="my-3"><?= $num_doc_review ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->


        <div class="card report-card bg-secondary-gradient shadow-secondary mt-2 box-hover" data-id="6">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-purple">HSTL QUÁ HẠN</span>
                <h3 class="my-3"><?= $num_doc_out_review ?></h3>
            </div>
            <!--end card-body-->
        </div>
        <!--end card-->
    </div>
    <!--end col-->
    <div class="col-md-6 col-lg-9 pt-2 pt-md-0" id="document">
        <div class="card card-fluid">
            <div class="card-header">
                HSTL
            </div>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanly" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Loại tài liệu</th>
                                <th>Đính kèm</th>
                                <th>Hành động</th>
                            </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    <!--end col-->
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
        let id = 1;
        let list_status = <?= json_encode($status) ?>;
        let table = $('#quanly').DataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": path + "admin/document/table",
                "dataType": "json",
                "type": "POST",
                'data': function(data) {
                    // Read values
                    // let search_type = localStorage.getItem('SEARCH_TYPE') || "code";
                    // let search_status = localStorage.getItem('SEARCH_STATUS') || "0";
                    // let filter = localStorage.getItem('SEARCH_FILTER') || "0";
                    // data['search_type'] = search_type;
                    // data['search_status'] = search_status;
                    // data['filter'] = filter;

                    data['filter_home'] = id;
                    let orders = data['order'];
                    for (let i in orders) {
                        let order = orders[i];
                        let column = order['column'];
                        orders[i]['data'] = data['columns'][column]['data'];
                    }
                    let search_type = localStorage.getItem('SEARCH_TYPE') || "code";
                    let search_status = localStorage.getItem('SEARCH_STATUS') || "0";
                    let filter = localStorage.getItem('SEARCH_FILTER') || "0";
                    data['search_type'] = search_type;
                    data['search_status'] = search_status;
                    data['filter'] = filter;
                    data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                }
            },
            "columns": [{
                    "data": "id",
                }, {
                    "data": "code",
                }, {
                    "data": "name_vi",
                    "width": "500px",
                    "orderable": false
                },
                {
                    "data": "status"
                },
                {
                    "data": "type"
                },
                {
                    "data": "file",
                    "orderable": false
                },
                {
                    "data": "action",
                    "orderable": false
                }
            ],
            initComplete: function() {
                $(".dataTables_filter label").prepend("<select style='margin-right: 0.5em;display: inline-block;width: auto;' class='form-control form-control-sm search_type'><option value='id'>ID</option><option value='code'>Mã tài liệu</option><option value='name_vi'>Tên tài liệu</option><option value='status'>Trạng thái</option><option value='description_vi'>Ghi chú</option></select>");
                $(".dataTables_filter label").append("<select style='margin-left: 0.5em;display: inline-block;width: auto;' class='form-control form-control-sm search_status d-none'></select>");
                $(".dataTables_length label").prepend("<select style='margin-right: 0.5em;display: inline-block;width: auto;' class='form-control form-control-sm filter'><option value='0'>Tất cả</option><option value='1'>Tài liệu hiện hành</option></select>");
                let html = "";
                for (let status of list_status) {
                    html += "<option value='" + status.id + "'>" + status.name + "</option>";
                }
                $(".search_status").append(html);

                let search_type = localStorage.getItem('SEARCH_TYPE') || "code";
                $(".search_type").val(search_type);

                if (search_type == "status") {
                    $(".search_status").removeClass("d-none");
                    $(".dataTables_filter label input").addClass("d-none");
                } else {
                    $(".search_status").addClass("d-none");
                    $(".dataTables_filter label input").removeClass("d-none");
                }
                let search_status = localStorage.getItem('SEARCH_STATUS') || "0";
                $(".search_status").val(search_status);

                let filter = localStorage.getItem('SEARCH_FILTER') || "0";
                $(".filter").val(filter);
            }
        });
        $(".box-hover").click(function() {
            let name = $(this).find(".badge").text();
            id = $(this).data("id");
            $("#document .card-header").text(name);
            table.ajax.reload();
        })
        $(document).on("change", ".filter", function() {
            let filter = $(this).val();
            localStorage.setItem('SEARCH_FILTER', filter);
            table.ajax.reload();
        });
        $(document).on("change", ".search_type", function() {
            let search_type = $(this).val();
            localStorage.setItem('SEARCH_TYPE', search_type);
            if (search_type == "status") {
                $(".search_status").removeClass("d-none");
                $(".dataTables_filter label input").addClass("d-none");
            } else {
                $(".search_status").addClass("d-none");
                $(".dataTables_filter label input").removeClass("d-none");
            }
            table.ajax.reload();
        });

        $(document).on("change", ".search_status", function() {
            let search_status = $(this).val();
            localStorage.setItem('SEARCH_STATUS', search_status);
            table.ajax.reload();
        });
    });
</script>
<?= $this->endSection() ?>