<?php
$dataForm      = $this->arrParam['form'];
$inputTK       = Helper::input('text', 'form[username]', 'form[username]', $dataForm['username'], 'form-control');
$inputUserName = Helper::input('text', 'form[fullname]', 'form[fullname]', $dataForm['fullname'], 'form-control');
$inputEmail    = Helper::input('text', 'form[email]', 'form[email]', $dataForm['email'], 'form-control');
$inputPassword = Helper::input('text', 'form[password]', 'form[password]', $dataForm['password'], 'form-control');
$inputHidden   = Helper::input('hidden', 'form[token]', 'form[token]', time(), 'form-control');
$button        = Helper::button1('submit', 'submit', 'form[submit]', 'Tạo tài khoản', 'Tạo tài khoản');


$colTK       = Helper::col('username', 'Tên tài khoản', $inputTK);
$colUserName = Helper::col('fullname', 'Họ và tên', $inputUserName);
$colEmail    = Helper::col('email', 'Email', $inputEmail);
$colPassword = Helper::col('password', 'Mật khẩu', $inputPassword);

$linkAction = URL::createLink('frontend', 'user', 'register');



//!=================================================== END PHP =======================================================
?>
<div class="breadcrumb-section"></div>

<section class="register-page section-b-space">
    <div class="container">
        <div class="row">
            <div class="col-lg-12">
                <h3>Đăng ký tài khoản</h3>
                <?= $this->errors ?? '' ?>
                <div class="theme-card">
                    <form action=" <?php $linkAction ?> " method="post" name="adminform" id="admin-form" class="theme-form">
                        <div class="form-row">
                            <?= $colTK . $colUserName . $colEmail . $colPassword  ?>
                        </div>
                        <?php echo $inputHidden . $button ?>
                    </form>
                </div>
            </div>
        </div>
    </div>
</section>

