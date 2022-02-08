<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-lg-12">
  <div class="card card-danger card-outline">
    <div class="card-header">
      <h5 class="card-title m-0"><?= ucwords($operation).' '.ucwords($title) ?></h5>
    </div>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Name') ?>
            <?= form_input([
            'class' => "form-control",
            'readonly' => "readonly",
            'value' => $data['name']
            ]) ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Mobile') ?>
            <?= form_input([
            'class' => "form-control",
            'readonly' => "readonly",
            'value' => $data['mobile']
            ]) ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Email') ?>
            <?= form_input([
            'class' => "form-control",
            'readonly' => "readonly",
            'value' => $data['email']
            ]) ?>
          </div>
        </div>
      </div>
    </div>
    <div class="card-footer">
      <div class="row">
        <div class="col-md-6">
          <?= anchor($url, 'Go Back', 'class="btn btn-outline-danger col-md-4"'); ?>
        </div>
      </div>
    </div>
  </div>
</div>