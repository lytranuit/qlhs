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
                <h3 class="my-3">575</h3>
             
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-danger-gradient shadow-danger">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-print report-main-icon bg-icon-danger"></i>
                </div>
                <span class="badge badge-light text-danger">TÀI LIỆU CHO MƯỢN</span>
                <h3 class="my-3">254</h3>
               
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-secondary-gradient shadow-secondary">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-code-branch report-main-icon bg-icon-secondary"></i>
                </div>
                <span class="badge badge-light text-secondary">TÀI LIỆU HẾT HẠN</span>
                <h3 class="my-3">84</h3>
               
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
    <div class="col-md-6 col-lg-3 pt-2 pt-md-0">
        <div class="card report-card bg-warning-gradient shadow-warning">
            <div class="card-body">
                <div class="float-right">
                    <i class="fa fa-user report-main-icon bg-icon-warning"></i>
                </div>
                <span class="badge badge-light text-warning">TỔNG NGƯỜI DÙNG</span>
                <h3 class="my-3">9</h3>
               
            </div><!--end card-body-->
        </div><!--end card-->
    </div> <!--end col-->
</div>


<?= $this->endSection() ?>
