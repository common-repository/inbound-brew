<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/8/15
 * Time: 8:13 PM
 */
?>
<div class="ib-row">
    <div class="ib-column ib-column-12" style="text-align:center">
        <h1><?php echo _("Download Request");?></h1>
        <p><?php echo _("The item that you requested");?> <b><?php echo $download->download_title; ?></b> <?php echo _("is no longer available for download because");?> <?php echo $reason;?></p>
        <p><?php echo _("If you would like to download");?> <b><?php echo $download->download_title; ?></b> <?php echo _("again, please visit this");?> <b><a href="<?php echo $download->download_refer;?>" title="landing Page Link"><?php echo _("link");?></a></b></p>
    </div>
</div>