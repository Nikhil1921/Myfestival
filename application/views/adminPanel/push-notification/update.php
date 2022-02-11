<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-lg-12">
  <div class="card card-danger card-outline">
    <div class="card-header">
      <h5 class="card-title m-0"><?= ucwords($operation).' '.ucwords($title) ?></h5>
    </div>
    <?= form_open_multipart($url.'/update/'.$id, '', ['image' => $data['image']]) ?>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Notification', 'notification') ?>
            <?= form_input([
            'name' => "notification",
            'class' => "form-control",
            'id' => "notification",
            'placeholder' => "Enter notification",
            'value' => (!empty(set_value('notification'))) ? set_value('notification') : $data['notification']
            ]) ?>
            <?= form_error('notification') ?>
          </div>
        </div>
        <div class="col-md-5">
          <div class="form-group">
            <?= form_label('Select Image', 'image') ?>
            <div class="input-group">
              <div class="custom-file">
                <?= form_input([
                'type' => "file",
                'name' => "image",
                'class' => "custom-file-input",
                'id' => "image",
                'accept' => '.png,.jpeg,.jpg,'
                ]) ?>
                <?= form_label('Select image', 'image', ['class' => 'custom-file-label']) ?>
              </div>
            </div>
          </div>
        </div>
        <div class="col-md-1">
          <div class="form-group">
            <?= img(['src' => 'assets/images/push-notification/'.$data['image'], 'height' => '100%', 'width' => '100%','alt' => 'No Image']) ?>
          </div>
        </div>
        <div class="col-md-12">
          <div class="form-group">
            <?= form_label('Details', 'details') ?>
            <?= form_textarea([
            'name' => "details",
            'class' => "form-control",
            'id' => "details",
            'placeholder' => "Enter Details",
            'value' => (!empty(set_value('details'))) ? set_value('details') : $data['details']
            ]) ?>
            <?= form_error('details') ?>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-md-6">
          <?= form_button([ 'content' => 'Save',
          'type'  => 'submit',
          'class' => 'btn btn-outline-primary col-md-4']) ?>
        </div>
        <div class="col-md-6">
          <?= anchor($url, 'Cancel', 'class="btn btn-outline-danger col-md-4"'); ?>
        </div>
      </div>
    </div>
    <?= form_close() ?>
  </div>
</div>