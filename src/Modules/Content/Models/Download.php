<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/6/15
 * Time: 1:57 PM
 */

namespace InboundBrew\Modules\Content\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class Download extends Eloquent{

    protected $softDelete = true;
    protected $table = 'ib_downloads';
    protected $primaryKey = 'download_id';
    protected $fillable = array('download_url','download_expire','download_limit','download_title');
    protected $dates = array('deleted_at');

    public function getLocalFileLocationAttribute(){
    	return ABSPATH.str_replace(get_site_url(), "", $this->download_url);
    }

}