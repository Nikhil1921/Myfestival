<div class="row">
  <div class="col-md-4">
    <div class="card card-danger card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <?= img(['src' => 'assets/images/favicon.png', 'alt' => '', 'class' => 'profile-user-img img-fluid img-circle']) ?>
        </div>
        <h3 class="profile-username text-center">Update Profile</h3>
        <p class="text-muted text-center"></p>
        <?= form_open(admin('profile')) ?>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Profile Name</b>
            <?= form_input([
            'type' => 'text',
            'name' => 'name',
            'class' => 'form-control',
            'placeholder' => 'Enter Profile Name',
            'value' => $this->session->name
            ]) ?>
            <?= form_error('name') ?>
          </li>
          <li class="list-group-item">
            <b>Mobile No.</b>
            <?= form_input([
            'type' => 'text',
            'name' => 'mobile',
            'class' => 'form-control',
            'placeholder' => 'Enter Mobile No.',
            'value' => $this->session->mobile,
            'maxlength' => '10'
            ]) ?>
            <?= form_error('mobile') ?>
          </li>
          <li class="list-group-item">
            <b>Email Address</b>
            <?= form_input([
            'type' => 'email',
            'name' => 'email',
            'class' => 'form-control',
            'placeholder' => 'Enter Email Address',
            'value' => $this->session->email
            ]) ?>
            <?= form_error('email') ?>
          </li>
        </ul>
        <?= form_button([ 'content' => '<b>Update Profile</b>',
        'type'  => 'submit',
        'class' => 'btn btn-outline-primary btn-block']) ?>
        <?= form_close() ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-danger card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <?= img(['src' => 'assets/images/favicon.png', 'alt' => '', 'class' => 'profile-user-img img-fluid img-circle']) ?>
        </div>
        <h3 class="profile-username text-center">Change Password</h3>
        <p class="text-muted text-center"></p>
        <?= form_open(admin('changePassword')) ?>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Password</b>
            <?= form_input([
            'type' => 'password',
            'name' => 'password',
            'class' => 'form-control',
            'placeholder' => 'Enter Password'
            ]) ?>
            <?= form_error('password') ?>
          </li>
        </ul>
        <?= form_button([ 'content' => '<b>Change Password</b>',
        'type'  => 'submit',
        'class' => 'btn btn-outline-primary btn-block']) ?>
        <?= form_close() ?>
      </div>
    </div>
  </div>
  <div class="col-md-4">
    <div class="card card-danger card-outline">
      <div class="card-body box-profile">
        <div class="text-center">
          <?= img(['src' => 'assets/images/favicon.png', 'alt' => '', 'class' => 'profile-user-img img-fluid img-circle']) ?>
        </div>
        <h3 class="profile-username text-center">Contact Us No</h3>
        <p class="text-muted text-center"></p>
        <?= form_open(admin('contactUsNo')) ?>
        <ul class="list-group list-group-unbordered mb-3">
          <li class="list-group-item">
            <b>Contact No.</b>
            <?= form_input([
            'type' => 'text',
            'name' => 'conf_val',
            'class' => 'form-control',
            'placeholder' => 'Enter Contact No.',
            'value' => isset($data['conf_val']) ? $data['conf_val'] : '',
            'maxlength' => '10'
            ]) ?>
            <?= form_error('conf_val') ?>
          </li>
        </ul>
        <?= form_button([ 'content' => '<b>Contact Us No</b>',
        'type'  => 'submit',
        'class' => 'btn btn-outline-primary btn-block']) ?>
        <?= form_close() ?>
      </div>
    </div>
  </div>
</div>