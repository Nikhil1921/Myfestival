<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-lg-12">
  <div class="card card-danger card-outline">
    <div class="card-header">
      <div class="row">
        <div class="col-6">
          <h5 class="card-title m-0"><?= ucwords($title) ?> List</h5>
        </div>
        <?php if (check_access($name, 'add')): ?>
        <div class="col-6">
          <?= form_open_multipart($url.'/add', '') ?>
          <div class="row">
            <div class="col-8">
              <div class="form-group">
                <div class="input-group">
                  <div class="custom-file">
                    <?= form_input([
                    'type' => "file",
                    'name' => "image",
                    'class' => "custom-file-input",
                    'id' => "image",
                    'accept' => '.png,.jpeg,.jpg,'
                    ]) ?>
                    <?= form_label('Select banner image', 'image', ['class' => 'custom-file-label']) ?>
                  </div>
                </div>
              </div>
            </div>
            <div class="col-4">
              <?= form_button([ 'content' => 'Upload',
              'type'    => 'submit',
              'class'   => 'btn btn-primary btn-block']) ?>
              <?= form_close() ?>
            </div>
          </div>
        </div>
        <?php endif ?>
      </div>
    </div>
    <div class="card-body table-responsive">
      <table class="table table-bordered table-hover datatable">
        <thead>
          <tr>
            <th class="target">Sr. No.</th>
            <th class="target">Banner</th>
            <th class="target">Action</th>
          </tr>
        </thead>
        <tbody>
        </tbody>
      </table>
    </div>
  </div>
</div>