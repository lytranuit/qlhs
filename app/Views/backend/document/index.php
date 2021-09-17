<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <h5 class="card-header drag-handle">
                <a class="btn btn-success btn-sm" href="<?= base_url("admin/$controller/add") ?>">Thêm</a>
                <div style="margin-left:auto;">
                    <a class="btn btn-sm btn-success" id='scan' href="#"><i class="fas fa-qrcode"></i> Quét QR</a>
                </div>
            </h5>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlytin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>Mã</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Đính kèm</th>
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
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<video id="preview"></video>

<script type="text/javascript">
    $(document).ready(function() {
        let list_status = <?= json_encode($status) ?>;
        let table = $('#quanlytin').DataTable({
            "stateSave": true,
            "processing": true,
            "serverSide": true,
            "ajax": {
                "url": path + "admin/<?= $controller ?>/table",
                "dataType": "json",
                "type": "POST",
                'data': function(data) {
                    // Read values
                    let search_type = localStorage.getItem('SEARCH_TYPE') || "code";
                    let search_status = localStorage.getItem('SEARCH_STATUS') || "0";
                    data['search_type'] = search_type;
                    data['search_status'] = search_status;

                    data['<?= csrf_token() ?>'] = "<?= csrf_hash() ?>";
                }
            },
            "columns": [{
                    "data": "code"
                }, {
                    "data": "name_vi",
                    "width": "500px"
                },
                {
                    "data": "status"
                },
                {
                    "data": "file"
                },
                {
                    "data": "action"
                }
            ],
            initComplete: function() {
                $(".dataTables_filter label").prepend("<select style='margin-right: 0.5em;display: inline-block;width: auto;' class='form-control form-control-sm search_type'><option value='code'>Mã tài liệu</option><option value='name_vi'>Tên tài liệu</option><option value='status'>Trạng thái</option></select>");
                $(".dataTables_filter label").append("<select style='margin-left: 0.5em;display: inline-block;width: auto;' class='form-control form-control-sm search_status d-none'></select>");
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
            }
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
        let scanner = new Instascan.Scanner({
            video: document.getElementById('preview')
        });
        scanner.addListener('scan', function(content) {
            console.log(content);
            alert("content");
        });
        $("#scan").click(function() {
            let select_cam = 0;
            if ($("#select_cam").length > 0) {
                select_cam = $("#select_cam").val();
            }
            if (cameras.length > 0) {
                scanner.start(cameras[select_cam]);
            } else {
                alert('Không tìm thấy camera.');
                console.log('No cameras found.');
            }
        });
        var cameras = [];
        Instascan.Camera.getCameras().then(function(c) {
            cameras = c;
            console.log(cameras);
            if (cameras.length > 1) {
                let html = "<select class='form-control form-control-sm' id='select_cam'>";
                for (let k in cameras) {
                    let cam = cameras[k];
                    html += "<option value='" + k + "'>" + cam.name + "</option>";
                }
                html += "</select>";
                $("#scan").before(html);
                $("#select_cam").val(cameras.length - 1);
            }
        }).catch(function(e) {
            console.log(e);
        });
    });
</script>

<?= $this->endSection() ?>