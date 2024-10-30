<?php
/* --inbound-brew-free-start-- */
$graph_label = array();

if (isset($dataPoints['graph_data'])) {
    $posts_count = $leads_count = $social_network = array();
    if (isset($dataPoints['graph_data']['posts_count'])) {
        $posts_count = array_keys($dataPoints['graph_data']['posts_count']);
    }
    if (isset($dataPoints['graph_data']['leads_count'])) {
        $leads_count = array_keys($dataPoints['graph_data']['leads_count']);
    }
    if (isset($dataPoints['graph_data']['social_network'])) {
        $social_network = array_keys($dataPoints['graph_data']['social_network']);
    }
}
$graph_label = array_unique(array_merge($posts_count, $leads_count, $social_network));
sort($graph_label);

ksort($dataPoints['graph_data']['posts_count']);
ksort($dataPoints['graph_data']['leads_count']);
ksort($dataPoints['graph_data']['social_network']);
?>
<!--Filter form start -->
<div class = "ib-row ib-dashboard-filter">
    <?php
    echo $Form->create("ib_reports_filter", array('url' => admin_url('admin-ajax.php'), 'class' => "ib_reports-filter-form-free", 'style' => "float:right;"));
    wp_nonce_field('ib_reports_filter_nonce');
    ?>
    <div class="ib_editor-fields">
        <div class="ib_label">Date Filter:</div>
        <div class="ib_fields">
            <?php
            echo $Form->text("ib_start_date", array(
                'div' => false,
                'value' => date("F d, Y", strtotime($graph_label[0])),
                'required' => true,
                'class' => "ib-start-date",
                'style' => "width:125px"));
            echo $Form->text("ib_end_date", array(
                'value' => date("F d, Y", strtotime(end($graph_label))),
                'div' => false,
                'required' => true,
                'class' => "ib-end-date",
                'style' => "width:125px"));
            ?>
            <input type="hidden" name="action" value="ib_reports_filter">
            <button id="widget_submit" class="ib-button">Filter</button>
        </div>
        <div class="clear"></div>
    </div>
    <?php echo $Form->end(); ?>
</div>
<!-- Filter form end -->
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
    <script>
        var config = {
            type: 'line',
            data: {
                labels: [<?php echo "'" . implode("','", $graph_label) . "'"; ?>],
                datasets: [{
                        label: "Post Published",
                        fill: false,
                        backgroundColor: 'rgb(221,75,57)',
                        borderColor: 'rgb(221,75,57)',
                        data: [<?php echo isset($dataPoints['graph_data']['posts_count']) ? implode(",", array_values($dataPoints['graph_data']['posts_count'])) : ''; ?>],
                    },
                    {
                        label: "Leads",
                        backgroundColor: 'rgb(59,89,152)',
                        borderColor: 'rgb(59,89,152)',
                        data: [<?php echo isset($dataPoints['graph_data']['leads_count']) ? implode(",", array_values($dataPoints['graph_data']['leads_count'])) : ''; ?>],
                        fill: false,
                    },
                    {
                        label: "Social Posts",
                        fill: false,
                        backgroundColor: 'rgb(85,172,238)',
                        borderColor: 'rgb(85,172,238)',
                        data: [<?php echo isset($dataPoints['graph_data']['social_network']) ? implode(",", array_values($dataPoints['graph_data']['social_network'])) : ''; ?>]
                    }
                ]
            },
            options: {
                responsive: true,
                title: {
                    display: false,
                    text: 'Web Overview - Inboundbrew'
                },
                tooltips: {
                    mode: 'index',
                    intersect: false,
                },
                hover: {
                    mode: 'nearest',
                    intersect: true
                },
                scales: {
                    xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Date'
                            }
                        }],
                    yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Value'
                            }
                        }]
                }
            }
        };
        window.onload = function () {
            var ctx = document.getElementById("canvas").getContext("2d");
            window.myLine = new Chart(ctx, config);
            window.orignal = config;
        };
        jQuery('.posts-data-icon').on('click', function () {
            window.myLine.config.data.datasets = [{
                    label: "Post Published",
                    fill: false,
                    backgroundColor: 'rgb(221,75,57)',
                    borderColor: 'rgb(221,75,57)',
                    data: [<?php echo isset($dataPoints['graph_data']['posts_count']) ? implode(",", array_values($dataPoints['graph_data']['posts_count'])) : ''; ?>],
                }
            ];
            window.myLine.update();
        });
        jQuery('.leads-data-icon').on('click', function () {
            window.myLine.config.data.datasets = [{
                    label: "Leads",
                    backgroundColor: 'rgb(59,89,152)',
                    borderColor: 'rgb(59,89,152)',
                    data: [<?php echo isset($dataPoints['graph_data']['leads_count']) ? implode(",", array_values($dataPoints['graph_data']['leads_count'])) : ''; ?>],
                    fill: false,
                }
            ];
            window.myLine.update();
        });
        jQuery('.social-data-icon').on('click', function () {
            window.myLine.config.data.datasets = [{
                    label: "Social Posts",
                    fill: false,
                    backgroundColor: 'rgb(85,172,238)',
                    borderColor: 'rgb(85,172,238)',
                    data: [<?php echo isset($dataPoints['graph_data']['social_network']) ? implode(",", array_values($dataPoints['graph_data']['social_network'])) : ''; ?>]
                }
            ];
            window.myLine.update();
        });
        jQuery('.global-data-icon').on('click', function () {
            window.myLine.config.data.datasets = [{
                    label: "Post Published",
                    fill: false,
                    backgroundColor: 'rgb(221,75,57)',
                    borderColor: 'rgb(221,75,57)',
                    data: [<?php echo isset($dataPoints['graph_data']['posts_count']) ? implode(",", array_values($dataPoints['graph_data']['posts_count'])) : ''; ?>],
                },
                {
                    label: "Leads",
                    backgroundColor: 'rgb(59,89,152)',
                    borderColor: 'rgb(59,89,152)',
                    data: [<?php echo isset($dataPoints['graph_data']['leads_count']) ? implode(",", array_values($dataPoints['graph_data']['leads_count'])) : ''; ?>],
                    fill: false,
                },
                {
                    label: "Social Posts",
                    fill: false,
                    backgroundColor: 'rgb(85,172,238)',
                    borderColor: 'rgb(85,172,238)',
                    data: [<?php echo isset($dataPoints['graph_data']['social_network']) ? implode(",", array_values($dataPoints['graph_data']['social_network'])) : ''; ?>]
                }
            ];
            window.myLine.update();
        });
    </script>
</div>
<!--Dashboard charts end-->
<?php
/* --inbound-brew-free-end-- */
?>
