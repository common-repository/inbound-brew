<?php
$tabs = array(
    'General' => "admin.php?page={$post_type}",
    'Social Settings' => "admin.php?page={$post_type}&section=ib_social_settings",
    'Social Share Widget' => "admin.php?page={$post_type}&section=ib_social_share_widget",
    'Leads' => "admin.php?page={$post_type}&section=ib_leads_settings",
    'Email' => "admin.php?page={$post_type}&section=ib_email_settings",
    'Sitemap/Routing' => "admin.php?page={$post_type}&section=ib_advance_settings",
    'License Key' => "admin.php?page={$post_type}&section=ib_license_key"
  );
?>
<div class="tab-wrapper noselect">
    <?php
    if (!isset($active))
        $active = key($tabs);
    foreach ($tabs as $title => $url):
        $selected = ($title == $active) ? "selected" : "";
        echo "<a href=\"{$url}\" id=\"".str_replace (
                                                        ' ', 
                                                        '-', 
                                                        strtolower(
                                                                    preg_replace("/[^A-Za-z0-9\-]/", '', $title)
                                                                )
                                                    )."-settings-tab\" class=\"ib-tab-link {$selected}\">{$title}</a>";
    endforeach;
    ?>
</div>
