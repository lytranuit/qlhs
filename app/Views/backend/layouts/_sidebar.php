<div class="app-sidebar sidebar-shadow">
    <div class="app-header__logo">
        <div><img src="<?= base_url("assets/images/logo.png") ?>" width="150" /></div>
        <div class="header__pane ml-auto">
            <div>
                <button type="button" class="hamburger close-sidebar-btn hamburger--elastic" data-class="closed-sidebar">
                    <span class="hamburger-box">
                        <span class="hamburger-inner"></span>
                    </span>
                </button>
            </div>
        </div>
    </div>
    <div class="app-header__mobile-menu">
        <div>
            <button type="button" class="hamburger hamburger--elastic mobile-toggle-nav">
                <span class="hamburger-box">
                    <span class="hamburger-inner"></span>
                </span>
            </button>
        </div>
    </div>
    <div class="app-header__menu">
        <span>
            <button type="button" class="btn-icon btn-icon-only btn btn-primary btn-sm mobile-toggle-header-nav">
                <span class="btn-icon-wrapper">
                    <i class="fa fa-ellipsis-v fa-w-6"></i>
                </span>
            </button>
        </span>
    </div>
    <div class="scrollbar-sidebar">
        <div class="app-sidebar__inner">
            <ul class="vertical-nav-menu">
                <li class="app-sidebar__heading">Tổng quan</li>
                <li>
                    <a href="<?= base_url() ?>/admin/" class="">
                        <i class="metismenu-icon far fa-chart-bar"></i>
                        Tổng quan
                    </a>
                </li>
                </li>

                <li class="app-sidebar__heading">Vị trí</li>
                <li>
                    <a href="<?= base_url() ?>/admin/category" class="">
                        <i class="metismenu-icon fas fa-bars"></i>
                        Vị trí
                    </a>
                </li>
                <li class="app-sidebar__heading">Tài liệu</li>
                <li class="mm-active">
                    <a href="<?= base_url() ?>/admin/document" class="">
                        <i class="metismenu-icon fas fa-file"></i>
                        Tài liệu
                        <i class="metismenu-state-icon fas fa-chevron-down caret-left" style="font-size: 12px;"></i>
                    </a>
                    <ul class="mm-collapse mm-show">
                        <?php foreach ($types_list as $row) : ?>
                            <li>
                                <a href="<?= base_url("/admin/document/index/$row->id") ?>" class="">
                                    <?= $row->name ?>
                                </a>
                            </li>
                        <?php endforeach ?>
                    </ul>

                </li>
                <li>
                    <a href="<?= base_url() ?>/admin/status" class="">
                        <i class="metismenu-icon fas fa-info-circle"></i>
                        Trạng thái
                    </a>
                </li>
                <li>
                    <a href="<?= base_url() ?>/admin/type" class="">
                        <i class="metismenu-icon fas fa-info-circle"></i>
                        Loại tài liệu
                    </a>
                </li>
                <!-- <li class="app-sidebar__heading">Auditrail</li>
                <li>
                    <a href="<?= base_url() ?>/admin/history" class="">
                        <i class="metismenu-icon fas fa-history"></i>
                        Auditrail
                    </a>
                </li> -->
                <?php if (in_groups("admin")) : ?>
                    <li class="app-sidebar__heading">Cài đặt</li>
                    <li>
                        <a href="<?= base_url() ?>/admin/settings" class="">
                            <i class="metismenu-icon fas fa-wrench"></i>
                            Cài đặt chung
                        </a>
                    </li>
                    <li>
                        <a href="<?= base_url() ?>/admin/user/">
                            <i class="metismenu-icon fa fa-lock"></i>
                            Tài khoản
                        </a>
                    </li>
                <?php endif ?>
            </ul>
        </div>
    </div>
</div>