<?php
/**
 * Created by PhpStorm.
 * User: sean
 * Date: 11/6/15
 * Time: 2:10 PM
 */

namespace InboundBrew\Modules\Content\Controllers;


use InboundBrew\Modules\Content\Models\Download as DownloadModel;
use InboundBrew\Modules\Core\Models\LeadHistory;
use InboundBrew\Traits\Virtual;

class Download extends Virtual
{
    const VIEW_PATH = 'Content/views/';

    private $show_error = false;
    private $alias;
    private $data = array();

    public function __construct()
    {
        parent::init();
    }

    public function LoadVirtual($args)
    {
        if (!isset($args['slug']))
            return;

        if (!$d = DownloadModel::where('download_alias',$args['alias'])->first()) {
            return;
        } else {
            $this->data['download'] = $d;

            if (isset($d->download_expire) && $d->download_expire && $d->download_expire < date('Y-m-d H:i:s')) {
                $this->show_error = true;
                $this->data['reason'] = _('it has expired');
            }
            if (isset($d->download_limit) && $d->download_limit && $d->download_limit <= 0) {
                $this->show_error = true;
                $this->data['reason'] = _('it has reached the maximum download limit');
            }

            if ($this->show_error) {
                $this->title = isset($args['title'])?$args['title']:'';
                $this->content = $this->load->view(self::VIEW_PATH . 'download-not-valid',$this->data,"blank");
                $this->alias = isset($args['alias'])?$args['alias']:'';

                add_filter('the_posts', array($this,'virtualPage'));
            } else {
                $d->download_limit = $d->download_limit - 1;

                $lh = new LeadHistory();
                $lh->history_event = $d->download_title . ' Downloaded';
                $lh->lead_id = $d->lead_id;
                $lh->history_type = BREW_LEAD_HISTORY_TYPE_CONTENT_DOWNLOADED;
                $lh->history_note = "Downloaded <a href='".$d->download_url."' target='_blank'>".$d->download_title."</a>";
                $lh->save();
                
                $content = file_get_contents($d->local_file_location);
                if (!$content || $content == ''){
                    file_get_contents($d->download_url);
                }
                

                //if (file_exists($d->download_url)) {
                    $path_parts = pathinfo($d->download_url);

                    header('Content-Description: File Transfer');
                    header('Content-Type: application/octet-stream');
                    header('Content-Disposition: attachment; filename="'.$path_parts['basename'].'"');
                    header('Content-Transfer-Encoding: binary');
                    header('Expires: 0');
                    header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
                    header('Pragma: public');
                    
                    ob_start();
                    echo $content;
                    header('Content-Length: ' . ob_get_length());
                    ob_end_flush();
                    
                    $d->save();
                    exit;
                //}
            }
        }

    }
}