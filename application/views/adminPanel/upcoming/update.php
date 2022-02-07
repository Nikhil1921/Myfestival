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
            <?= form_label('Event Name', 'event') ?>
            <?= form_input([
            'name' => "event",
            'class' => "form-control",
            'id' => "event",
            'placeholder' => "Enter Event Name",
            'value' => (!empty(set_value('event'))) ? set_value('event') : $data['event']
            ]) ?>
            <?= form_error('event') ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Event Date', 'event_date') ?>
            <input type="text" class="form-control" data-inputmask-alias="datetime" data-inputmask-inputformat="dd-mm-yyyy" name="event_date" id="event_date" data-mask="" im-insert="false" value="<?= (!empty(set_value('event_date'))) ? set_value('event_date') : date('d-m-Y', strtotime($data['event_date'])) ?>">
            <?= form_error('event_date') ?>
          </div>
        </div>
        <div class="col-md-6">
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
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Current Image') ?><br>
            <?= img(['src' => 'assets/images/event/'.$data['image'], 'height' => 100, 'width' => 100,'alt' => 'No Image']) ?>
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