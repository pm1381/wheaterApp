<?php

use App\Core\System;
use App\Core\Tools;
use App\Model\Arrays;
use App\Model\Form;

require_once 'top.php' ?>
<div class="container" id="hero">
    <div class="row justify-content-center pt-5">
        <div class="col-10 col-md-6 pt-3">
            <div class="card">
                <div class="card-body">
                    <div class="buyForm form-group">
                        <?php $formArray = Tools::checkArray($this->data, 'userResult') ?>
                        <div class="phoneNumber row mb-3">
                            <div class="col-12">
                                <label for="mobileInput" class="label text-right">شماره همراه</label>
                                <input type="tel" class="mobileInput form-control" name="mobileInput" placeholder="09123456789">
                            </div>
                        </div>
                        <button id="phoneCheck" class="btn btn-success buyButton" type="submit">بررسی</button>
                        <div class="qrCode"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
</div>

<?php require_once 'footer.php' ?>