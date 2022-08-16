<?php

use App\Core\System;
use App\Core\Tools;

require_once 'top.php' ?>
<?php require_once 'header.php' ?>
<?php if (! $this->data['error'] && ! $this->data['warning']) { ?>
    <?php $forecast = $this->data['wheaterData']['forecast']; ?>
    <div class="wheaterForCast">
        <?php for($i = 1; $i < count($forecast); $i++) { ?>
            <?php $day = Tools::findDayFromDate($forecast[$i]['date']) ?>
            <div class="weekday">
                <p class="pText"><?php echo $day ?></p>
                <div class="wheaterStatus">
                    <p style="margin: 0;"><?php echo $forecast[$i]['status'] ?></p>
                    <p style="margin: 0;"><?php echo $forecast[$i]['maxTemp'] ?><span>&#8451;</span></p>
                    <p style="margin: 0;"><?php echo $forecast[$i]['minTemp'] ?><span>&#8451;</span></p>
                    <img src="<?php echo $forecast[$i]['iconStatus']  ?>" class="wheaterIcon">
                </div>            
            </div>
        <?php } ?>
    </div>
<?php } ?>
<?php require_once 'footer.php' ?>