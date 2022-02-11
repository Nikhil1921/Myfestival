<?php defined('BASEPATH') OR exit('No direct script access allowed'); ?>
<div class="col-lg-12">
  <div class="card card-danger card-outline">
    <div class="card-header">
      <h5 class="card-title m-0"><?= ucwords($operation).' '.ucwords($title) ?></h5>
    </div>
    <?= form_open_multipart($url.'/add') ?>
    <div class="card-body">
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Full Name', 'fullname') ?>
            <?= form_input([
            'name' => "fullname",
            'class' => "form-control",
            'id' => "fullname",
            'placeholder' => "Enter Full Name",
            'value' => set_value('fullname')
            ]) ?>
            <?= form_error('fullname') ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Mobile', 'mobile') ?>
            <?= form_input([
            'name' => "mobile",
            'class' => "form-control",
            'id' => "mobile",
            'maxlength' => 10,
            'placeholder' => "Enter Mobile",
            'value' => set_value('mobile')
            ]) ?>
            <?= form_error('mobile') ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Email', 'email') ?>
            <?= form_input([
            'type' => "email",
            'min' => 0,
            'name' => "email",
            'class' => "form-control",
            'id' => "email",
            'placeholder' => "Enter Email",
            'value' => set_value('email')
            ]) ?>
            <?= form_error('email') ?>
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Password', 'password') ?>
            <?= form_input([
            'type' => "password",
            'min' => 0,
            'name' => "password",
            'class' => "form-control",
            'id' => "password",
            'placeholder' => "Enter Password"
            ]) ?>
            <?= form_error('password') ?>
          </div>
        </div>
        <!-- <div class="col-md-6">
          <div class="form-group">
            <?= form_label('Select Frame', 'frame') ?>
            <div class="input-group">
              <div class="custom-file">
                <?= form_input([
                'type' => "file",
                'name' => "frame",
                'class' => "custom-file-input",
                'id' => "frame",
                'accept' => '.png,.jpeg,.jpg,'
                ]) ?>
                <?= form_label('Select Frame', 'frame', ['class' => 'custom-file-label']) ?>
              </div>
            </div>
          </div>
        </div> -->
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