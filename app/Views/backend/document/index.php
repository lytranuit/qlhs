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
<style rel="stylesheet" type="text/css">
    #div_video {
        display: none;
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        text-align: center;
        background: #8080808c;
        z-index: 11;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    #preview {
        width: 100%;
    }

    .change_cam {
        position: absolute;
        right: 10px;
        top: 10px;
    }

    @-webkit-keyframes scanner {
        0% {
            bottom: 90%;
        }

        50% {
            bottom: 10%;
        }

        100% {
            bottom: 90%;
        }
    }

    @-moz-keyframes scanner {
        0% {
            bottom: 90%;
        }

        50% {
            bottom: 10%;
        }

        100% {
            bottom: 90%;
        }
    }

    @-o-keyframes scanner {
        0% {
            bottom: 90%;
        }

        50% {
            bottom: 10%;
        }

        100% {
            bottom: 90%;
        }
    }

    @keyframes scanner {
        0% {
            bottom: 90%;
        }

        50% {
            bottom: 10%;
        }

        100% {
            bottom: 90%;
        }
    }

    .custom-scanner {
        height: 2px;
        background: #4CAF50;
        position: absolute;
        -webkit-transition: all 200ms linear;
        -moz-transition: all 200ms linear;
        transition: all 200ms linear;
        -webkit-animation: scanner 3s infinite linear;
        -moz-animation: scanner 3s infinite linear;
        -o-animation: scanner 3s infinite linear;
        animation: scanner 3s infinite linear;
        box-shadow: 0 1px 0 0 rgba(0, 0, 0, 0.4);
        left: -10px;
        right: 0;
        margin: auto;
    }
</style>
<?= $this->endSection() ?>

<!-- Script --->
<?= $this->section('script') ?>

<script src="<?= base_url('assets/lib/datatables/datatables.min.js') ?>"></script>
<script src="<?= base_url('assets/lib/datatables/jquery.highlight.js') ?>"></script>
<script type="text/javascript" src="https://rawgit.com/schmich/instascan-builds/master/instascan.min.js"></script>
<div id="div_video" class="d-none">
    <video id="preview"></video>
    <div class="custom-scanner"></div>
    <button class="btn btn-sm btn-secondary change_cam"><i class="fas fa-sync-alt"></i></button>
</div>
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
            location.href = content;
        });
        var select_cam = 0;
        $(".change_cam").click(function() {
            select_cam++;
            if (select_cam > cameras.length)
                select_cam = 0;
            $("#scan").trigger("click");
        })
        $("#scan").click(function() {
            if (cameras.length > 0) {
                let cam = cameras[select_cam];
                if (cam.name.indexOf("back") != -1) {
                    scanner.mirror = false
                } else {
                    scanner.mirror = true
                }
                scanner.start(cam);
                $("#div_video").removeClass("d-none");
                if (cameras.length == 1) {
                    $(".change_cam").addClass("d-none");
                }
            } else {
                alert('Không tìm thấy camera.');
                console.log('No cameras found.');
            }
        });
        var cameras = [];
        Instascan.Camera.getCameras().then(function(c) {
            cameras = c;
            if (cameras.length > 1) {
                select_cam = cameras.length - 1;
            }
            // console.log(cameras);
            // if (cameras.length > 1) {
            //     let html = "<select class='form-control form-control-sm d-none' id='select_cam'>";
            //     for (let k in cameras) {
            //         let cam = cameras[k];
            //         html += "<option value='" + k + "'>" + cam.name + "</option>";
            //     }
            //     html += "</select>";
            //     $("#scan").before(html);
            //     $("#select_cam").val(cameras.length - 1);
            // }
        }).catch(function(e) {
            console.log(e);
        });
    });
</script>

<?= $this->endSection() ?>