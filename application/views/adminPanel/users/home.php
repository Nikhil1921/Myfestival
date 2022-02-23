<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-lg-12">
  <div class="card card-danger card-outline">
    <div class="card-header">
      <div class="row">
        <div class="col-sm-6">
          <h5 class="card-title m-0"><?= ucwords($title) ?> List</h5>
        </div>
        <?php if (check_access($name, 'import')): ?>
        <div class="col-sm-2">
        <?= form_open_multipart($url . '/import') ?>
          <div class="form-group">
            <?= form_label('Select File To Import', 'import', ['class' => 'btn btn-block btn-outline-success btn-sm']) ?>
                  <?= form_input([
                  'style' => "display: none;",
                  'type' => "file",
                  'id' => "import",
                  'name' => "import",
                  'onchange' => 'this.form.submit()',
                  'accept' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel, .csv'
                  ]) ?>
          </div>
        <?= form_close() ?>
        </div>
        <?php endif ?>
        <?php if (check_access($name, 'import')): ?>
        <div class="col-sm-2">
          <?= anchor('import-users.xlsx', 'Download demo', 'class="btn btn-block btn-outline-primary btn-sm"'); ?>
        </div>
        <?php endif ?>
        <?php if (check_access($name, 'add')): ?>
        <div class="col-sm-2">
          <?= anchor($url.'/add', 'Add', 'class="btn btn-block btn-outline-success btn-sm"'); ?>
        </div>
        <?php endif ?>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-striped table-hover datatable">
        <thead>
          <tr>
            <th class="target">Sr. No.</th>
            <th>Name</th>
            <th>Mobile</th>
            <th>Email</th>
            <?php if (check_access($name, 'upload')): ?>
              <th class="target">Status</th>
            <?php endif ?>
            <?php if (check_access($name, 'upload')): ?>
              <th class="target">Status Change</th>
            <?php endif ?>
            <th class="target">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>