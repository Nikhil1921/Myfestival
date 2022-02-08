<?php defined('BASEPATH') OR exit('No direct script access allowed') ?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <title><?= APP_NAME.' | '.ucwords($title) ?></title>
    <?= link_tag('assets/images/favicon.png','icon','image/x-icon') ?>
    <?= link_tag('assets/plugins/fontawesome-free/css/all.min.css','stylesheet','text/css') ?>
    
    <?php if (isset($dataTables)): ?>
    <?= link_tag('assets/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css','stylesheet','text/css') ?>
    <?= link_tag('assets/plugins/datatables-responsive/css/responsive.bootstrap4.min.css','stylesheet','text/css') ?>
    
    <?php endif ?>
    
    <?php if (isset($select)): ?>
    <?= link_tag('assets/plugins/select2/css/select2.min.css','stylesheet','text/css') ?>
    <?= link_tag('assets/plugins/select2-bootstrap4-theme/select2-bootstrap4.min.css','stylesheet','text/css') ?>
    <?php endif ?>
    <?php if (isset($checkbox)): ?>
    <?= link_tag('assets/plugins/icheck-bootstrap/icheck-bootstrap.min.css','stylesheet','text/css') ?>
    <?php endif ?>
    <?php if (isset($colorpicker)): ?>
    <?= link_tag('assets/plugins/bootstrap-colorpicker/css/bootstrap-colorpicker.min.css','stylesheet','text/css') ?>
    <?php endif ?>
    <?= link_tag('assets/dist/css/adminlte.min.css','stylesheet','text/css') ?>
    <link href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700" rel="stylesheet">
  </head>
  <body class="hold-transition sidebar-mini">
    <div class="wrapper">
      <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <ul class="navbar-nav">
          <li class="nav-item">
            <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
          </li>
        </ul>
        <ul class="navbar-nav ml-auto">
          <li class="nav-item dropdown">
            <?= anchor('#', '<i class="far fa-user"></i>', 'class="nav-link" data-toggle="dropdown" aria-expanded="false"') ?>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
              <span class="dropdown-header"><?= anchor(admin('profile'), 'Account Details', 'class="dropdown-item"') ?></span>
              <div class="dropdown-divider"></div>
              <?= anchor(admin('profile'), '<i class="fa fa-user mr-2"></i>'.ucwords($this->session->name), 'class="dropdown-item"') ?>
              <div class="dropdown-divider"></div>
              <?= anchor(admin('profile'), '<i class="fa fa-envelope mr-2"></i>'.$this->session->email, 'class="dropdown-item"') ?>
              <div class="dropdown-divider"></div>
              <?= anchor(admin('profile'), '<i class="fa fa-phone mr-2"></i>'.$this->session->mobile, 'class="dropdown-item"') ?>
              <div class="dropdown-divider"></div>
              <?= anchor(admin('logout'), 'Log Out', 'class="dropdown-item dropdown-footer"') ?>
            </div>
          </li>
        </ul>
      </nav>
      <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <?= anchor(admin(), img(['src' => 'assets/images/favicon.png', 'alt' => '', 'class' => 'brand-image img-circle elevation-3']).strtoupper(APP_NAME), 'class="brand-link"') ?>
        <div class="sidebar">
          <div class="user-panel mt-3 pb-3 mb-3 d-flex">
            <div class="image">
              <?= img(['src' => 'assets/images/user.jpg', 'alt' => '', 'class' => 'img-circle elevation-2']) ?>
            </div>
            <div class="info">
              <?= anchor(admin(), ucwords($this->session->name), 'class="d-block"') ?>
            </div>
          </div>
          <nav class="mt-2">
            <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
              <li class="nav-item">
                <?= anchor(admin(), '<i class="nav-icon fas fa-home"></i><p>Dashboard</p>', 'class="nav-link '.(($name == 'dashboard') ? 'active' : '').'"') ?>
              </li>
              <li class="nav-item">
                <?= anchor(admin('upcoming'), '<i class="nav-icon fas fa-calendar-check"></i><p>Upcoming Events</p>', 'class="nav-link '.(($name == 'upcoming') ? 'active' : '').'"') ?>
              </li>
              <li class="nav-item">
                <?= anchor(admin('category'), '<i class="nav-icon fas fa-image"></i><p>Category</p>', 'class="nav-link '.(($name == 'category') ? 'active' : '').'"') ?>
              </li>
              <li class="nav-item">
                <?= anchor(admin('users'), '<i class="nav-icon fas fa-users"></i><p>Users</p>', 'class="nav-link '.(($name == 'users') ? 'active' : '').'"') ?>
              </li>
              <?php if (check_access('employee', 'add')): ?>
              <li class="nav-item">
                <?= anchor(admin('employee'), '<i class="nav-icon fas fa-users"></i><p>Employees</p>', 'class="nav-link '.(($name == 'employee') ? 'active' : '').'"') ?>
              </li>
              <?php endif ?>
              <?php if (check_access('sub-admin', 'add')): ?>
              <li class="nav-item">
                <?= anchor(admin('sub-admin'), '<i class="nav-icon fas fa-users"></i><p>Sub Admin</p>', 'class="nav-link '.(($name == 'sub-admin') ? 'active' : '').'"') ?>
              </li>
              <?php endif ?>
              <li class="nav-item">
                <?= anchor(admin('banner'), '<i class="nav-icon fas fa-image"></i><p>Banners</p>', 'class="nav-link '.(($name == 'banner') ? 'active' : '').'"') ?>
              </li>
              <li class="nav-item">
                <?= anchor(admin('profile'), '<i class="nav-icon fas fa-user"></i><p>Profile</p>', 'class="nav-link '.(($name == 'profile') ? 'active' : '').'"') ?>
              </li>
              <?php if (check_access('backup', 'add')): ?>
              <li class="nav-item">
                <?= anchor(admin('home/backup'), '<i class="nav-icon fas fa-database"></i><p>Backup</p>', 'class="nav-link '.(($name == 'home/backup') ? 'active' : '').'"') ?>
              </li>
              <?php endif ?>
            </ul>
          </nav>
        </div>
      </aside>
      <div class="content-wrapper">
        <section class="content-header">
          <div class="container-fluid">
            <div class="row mb-2">
              <div class="col-sm-6">
                <h1><?= ucwords($title) ?></h1>
              </div>
              <div class="col-sm-6">
                <ol class="breadcrumb float-sm-right">
                  <li class="breadcrumb-item">
                    <?= anchor(admin(), 'Home', '') ?>
                  </li>
                  <li class="breadcrumb-item <?= (!empty($operation)) ? '' : 'active' ?> ">
                    <?= (!empty($operation)) ? anchor($url, ucwords($title), '') : ucwords($title) ?>
                  </li>
                  <?php if (!empty($operation)): ?>
                  <li class="breadcrumb-item active">
                    <?= ucwords($operation) ?>
                  </li>
                  <?php endif ?>
                </ol>
              </div>
            </div>
          </div>
        </section>
        <section class="content">
          <?php if ($this->session->success): ?>
          <div class="alert alert-success alert-messages">
            <?= $this->session->success ?>
          </div>
          <?php endif ?>
          <?php if ($this->session->error): ?>
          <div class="alert alert-danger alert-messages">
            <?= $this->session->error ?>
          </div>
          <?php endif ?>
          <?= $contents ?>
        </section>
      </div>
      <footer class="main-footer no-print">
        <div class="float-right d-none d-sm-block">
          <a href="https://densetek.com" target="_blank" title="Densetek Infotech"><b>Densetek Infotech</b></a>
        </div>
        <strong>Copyright &copy; 2020.</strong> All rights
        reserved.
      </footer>
    <aside class="control-sidebar control-sidebar-dark"></aside>
  </div>
  <input type="hidden" id="<?= $this->security->get_csrf_token_name() ?>" value="<?= $this->security->get_csrf_hash() ?>">
  <script src="<?= assets('plugins/jquery/jquery.min.js') ?>"></script>
  <script src="<?= assets('plugins/bootstrap/js/bootstrap.bundle.min.js') ?>"></script>
  <?php if (isset($dataTables)): ?>
  <script src="<?= assets('plugins/datatables/jquery.dataTables.min.js') ?>"></script>
  <script src="<?= assets('plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') ?>"></script>
  <script src="<?= assets('plugins/datatables-responsive/js/dataTables.responsive.min.js') ?>"></script>
  <script src="<?= assets('plugins/datatables-responsive/js/responsive.bootstrap4.min.js') ?>"></script>
  <script src="<?= assets('plugins/sweetalert2/sweetalert2.all.min.js') ?>"></script>
  <script type="text/javascript" src="<?= assets('plugins/datatables/dataTables.buttons.min.js') ?>"></script>
  <script type="text/javascript" src="<?= assets('plugins/datatables/pdfmake.min.js') ?>"></script>
  <script type="text/javascript" src="<?= assets('plugins/datatables/vfs_fonts.js') ?>"></script>
  <script type="text/javascript" src="<?= assets('plugins/datatables/buttons.html5.min.js') ?>"></script>
  <script type="text/javascript" src="<?= assets('plugins/datatables/buttons.print.min.js') ?>"></script>
  <script type="text/javascript" src="<?= assets('plugins/datatables/buttons.colVis.min.js') ?>"></script>
  <?php endif ?>
  <?php if (isset($select)): ?>
  <script src="<?= assets('plugins/select2/js/select2.full.min.js') ?>"></script>
  <script type="text/javascript"> $('.select2').select2() </script>
  <?php endif ?>
  <?php if (isset($inputmask)): ?>
  <script src="<?= assets('plugins/moment/moment.min.js') ?>"></script>
  <script src="<?= assets('plugins/inputmask/min/jquery.inputmask.bundle.min.js') ?>"></script>
  <script type="text/javascript"> $('[data-mask]').inputmask() </script>
  <?php endif ?>
  <script src="<?= assets('dist/js/adminlte.min.js') ?>"></script>
  <script src="<?= assets('plugins/ckeditor/ckeditor.js') ?>"></script>
  <?php if (isset($colorpicker)): ?>
  <script src="<?= assets('plugins/bootstrap-colorpicker/js/bootstrap-colorpicker.min.js') ?>"></script>
  <script>
  $('.my-colorpicker2').colorpicker();
  $('.my-colorpicker2').on('colorpickerChange', function(event) {
  $(this).children('div').children().children().css('color', event.color.toString());
  });
  </script>
  <?php endif ?>
  <?php $this->load->view(admin('script')) ?>
</body>
</html>