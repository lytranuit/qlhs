<?= $this->extend('backend/layouts/main') ?>


<?= $this->section('content') ?>
<!-- ============================================================== -->
<!-- pageheader -->
<!-- ============================================================== -->
<div class="row clearfix">
    <div class="col-12">
        <section class="card card-fluid">
            <h5 class="card-header drag-handle">
                <?php if (in_groups(array("admin", "editor"))) : ?>
                    <a class="btn btn-success btn-sm" href="<?= base_url("admin/$controller/add") ?>">Thêm</a>
                <?php endif ?>
                <a class="btn btn-primary btn-sm ml-2 text-white export">
                    <i class="fas fa-file-excel"></i>
                    Excel
                </a>
                <a class="btn btn-primary btn-sm ml-2 text-white qrdownload">
                    <i class="fas fa-download"></i>
                    QR download
                </a>
                <div style="margin-left:auto;">
                    <a class="btn btn-sm btn-success" id='scan' href="#"><i class="fas fa-qrcode"></i> Quét QR</a>
                </div>
            </h5>
            <div class="card-body">
                <div class="table-responsive-md">
                    <table id="quanlytin" class="table table-striped table-bordered table-hover" cellspacing="0" width="100%">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Mã</th>
                                <th>Tiêu đề</th>
                                <th>Trạng thái</th>
                                <th>Loại tài liệu</th>
                                <th>Ghi chú</th>
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
<link rel="stylesheet" href="<?= base_url("assets/lib/qrcode/qrcode.css") ?> " ?>

<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url('assets/lib/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/datatables/jquery.highlight.js') ?>"></script>
<div id="div_video" class="d-none">
    <video id="preview" class="d-none"></video>
    <div class="custom-scanner"></div>
    <button class="btn btn-sm btn-secondary change_cam"><i class="fas fa-sync-alt"></i></button>
</div>

<script type="module">
    import QrScanner from "<?= base_url('assets/lib/qr-scanner/qr-scanner.min.js') ?>";
    QrScanner.WORKER_PATH = "<?= base_url('assets/lib/qr-scanner/qr-scanner-worker.min.js') ?>";

    const video = document.getElementById('preview');
    const camList = document.getElementById('cam-list');
    var prev = "";
    var scanner = new QrScanner(video, content => {
        if (content == "" || content == prev)
            return;
        prev = content;
        location.href = content;
    });
    var select_cam = 0;
    var cameras = [];

    QrScanner.hasCamera().then(hasCamera => {
        if (hasCamera) {
            QrScanner.listCameras(true).then(c => {
                cameras = c;
                if (cameras.length > 1) {
                    select_cam = cameras.length - 1;
                }
            });
        }
    });
    $(".change_cam").click(function() {
        select_cam++;
        if (select_cam > cameras.length)
            select_cam = 0;
        $("#scan").trigger("click");
    })
    $("#scan").click(function() {
        if (cameras.length > 0) {
            let cam = cameras[select_cam];
            scanner.setCamera(cam.id)
            scanner.setInversionMode('both');
            scanner.start();
            $("#preview").before(scanner.$canvas);

            $("#div_video").removeClass("d-none");
            if (cameras.length == 1) {
                $(".change_cam").addClass("d-none");
            }
        } else {
            alert('Không tìm thấy camera.');
            console.log('No cameras found.');
        }
    });
</script>
<script type="text/javascript">
    $(document).ready(function() {
        let list_status = <?= json_encode($status) ?>;
        let type_id = <?= $type_id ?>;
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
                    let search_type = localStorage.getItem('SEARCH_TYPE') || "code";
                    let search_status = localStorage.getItem('SEARCH_STATUS') || "0";
                    let filter = localStorage.getItem('SEARCH_FILTER') || "0";
                    data['search_type'] = search_type;
                    data['search_status'] = search_status;
                    data['type_id'] = type_id;
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
                    "data": "description_vi"
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
        $(".export").click(async function() {
            $(".page-loader-wrapper").show();
            let url = await $.ajax({
                "url": path + "admin/<?= $controller ?>/exportexcel",
                "data": table.ajax.params(),
                "type": "POST",
                "dataType": "JSON"
            })
            $(".page-loader-wrapper").hide();
            location.href = url;
        });
        $(".qrdownload").click(async function() {
            $(".page-loader-wrapper").show();
            let url = await $.ajax({
                "url": path + "admin/<?= $controller ?>/exportqr",
                "data": table.ajax.params(),
                "type": "POST",
                "dataType": "JSON"
            })
            $(".page-loader-wrapper").hide();
            location.href = url;
        });
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