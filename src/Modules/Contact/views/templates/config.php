<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 12/1/15
 * Time: 4:02 PM
 */
$config = array();
$config[1]['content'] = '<div class="ib-row"><div class="ib-column ib-column-12">{{ib_lp_column_1}}</div></div><div class="ib-row"><div class="ib-column ib-column-12">{{contact_form}}</div></div>';
$config[1]['description'] = 'Default page layout with one large column where content is controlled by a single editor with form below the content';
$config[1]['name'] = 'Single Column';
$config[1]['thumb'] = "thumb-1.png";

$config[2]['content'] = '<div class="ib-row"><div class="ib-column ib-column-6">{{ib_lp_column_1}}</div><div class="ib-column ib-column-6">{{contact_form}}</div></div>';
$config[2]['description'] = 'Two column layout with each column being the same width and form on the right';
$config[2]['name'] = 'Two Column 50/50 Right';
$config[2]['thumb'] = "thumb-2.png";

$config[3]['content'] = '<div class="ib-row"><div class="ib-column ib-column-6">{{contact_form}}</div><div class="ib-column ib-column-6">{{ib_lp_column_1}}</div></div>';
$config[3]['description'] = 'Two column layout with each column being the same width and form on the left';
$config[3]['name'] = 'Two Column 50/50 Left';
$config[3]['thumb'] = "thumb-3.png";

$config[4]['content'] = '<div class="ib-row"><div class="ib-column ib-column-7">{{ib_lp_column_1}}</div><div class="ib-column ib-column-5">{{contact_form}}</div></div>';
$config[4]['description'] = 'Two column layout with the left column being 60% width and the right being 40%. Form on the right.';
$config[4]['name'] = 'Two Column 60/40';
$config[4]['thumb'] = "thumb-4.png";

$config[5]['content'] = '<div class="ib-row"><div class="ib-column ib-column-5">{{contact_form}}</div><div class="ib-column ib-column-7">{{ib_lp_column_1}}</div></div>';
$config[5]['description'] = 'Two column layout with the left column being 40% width and the right being 60%. Form on the left';
$config[5]['name'] = 'Two Column 40/60';
$config[5]['thumb'] = "thumb-5.png";

$config[6]['content'] = '<div class="ib-row"><div class="ib-column ib-column-12">{{ib_lp_column_1}}</div></div><div class="ib-row"><div class="ib-column ib-column-6">{{ib_lp_column_2}}</div><div class="ib-column ib-column-6">{{contact_form}}</div></div>';
$config[6]['description'] = 'Sub-header with two column layout with equal two column layout and form on the right';
$config[6]['name'] = 'Sub-head Two Column 50/50 Right';
$config[6]['thumb'] = "thumb-6.png";
return $config;