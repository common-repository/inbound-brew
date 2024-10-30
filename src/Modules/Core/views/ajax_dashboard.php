<?php
/* --inbound-brew-free-start-- */
?>
<div class="ib-row ib-data-contain">
    <!--Dashboard chart start-->
    <div class="ib-row ib-dashboard-chart">
        <div class="filter-with-ib-data">
            <ul>
                <li class="global-data-icon active" data-active="global-data" ><span class="fa fa-globe"></span></li>
                <li class="posts-data-icon" data-active="posts-data" title="Posts"><span class="fa fa-thumb-tack"></span></li>
                <li class="leads-data-icon" data-active="leads-data" title="Leads"><span class="fa fa-users"></span></li>
                <li class="social-data-icon" data-active="social-data" title="Social Posts"><span class="fa fa-share"></span></li>
            </ul>
        </div>
        <div class="dashboard-reports">
            <canvas id="canvas"></canvas>
        </div>
        <br>
        <br>
    </div>
    <!--Dashboard chart end-->
    <!-- IB data start-->
    <div class="ib-row social-data google-data">
        <div class="data-block-left">
            <div class="clear"></div>
            <div class="block-container">
                <h3>Posts Published</h3>
                <hr>
                <div class="data-left">
                    <?php echo date("F d, Y", strtotime($graph_label[0])) . " To " . date("F d, Y", strtotime(end($graph_label))); ?>
                </div>
                <div class="data-right">
                    <?php echo array_sum(array_values($dataPoints['graph_data']['posts_count'])); ?>
                </div>
            </div>
            <div class="clear"></div>
            <div class="block-container">
                <h3>Leads Captured</h3>
                <hr>
                <div class="data-left">
                    <?php echo date("F d, Y", strtotime($graph_label[0])) . " To " . date("F d, Y", strtotime(end($graph_label))); ?>
                </div>
                <div class="data-right">
                    <?php echo array_sum(array_values($dataPoints['graph_data']['leads_count'])); ?>
                </div>
            </div>
        </div>
        <div class="data-block-right">
            <div class="clear"></div>
            <div class="block-container">
                <h3>Social Posts</h3>
                <hr>
                <div class="data-left">
                    <?php echo date("F d, Y", strtotime($graph_label[0])) . " To " . date("F d, Y", strtotime(end($graph_label))); ?>
                </div>
                <div class="data-right">
                    <?php echo array_sum(array_values($dataPoints['graph_data']['social_network'])); ?>
                </div>
            </div>
        </div>
    </div>
    <!---IB data end -->
</div>
<!--Dashboard charts end-->
<?php
/* --inbound-brew-free-end-- */

