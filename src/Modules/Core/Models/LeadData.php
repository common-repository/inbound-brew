<?php

/**
 * Created by PhpStorm.
 * User: sean
 * Date: 10/8/15
 * Time: 12:52 PM
 */

namespace InboundBrew\Modules\Core\Models;

use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Database\Eloquent\SoftDeletes;

class LeadData extends Eloquent {

    protected $softDelete = true;
    protected $table = 'ib_lead_data';
    protected $primaryKey = 'data_id';
    protected $fillable = array('lead_id', 'data_term', 'data_value');
    protected $dates = array('deleted_at');

    public function lead() {
        return $this->belongsTo('InboundBrew\Modules\Core\Models\Lead', 'lead_id', 'lead_id');
    }

    public function formField() {
        return $this->belongsTo('InboundBrew\Modules\Core\Models\FormField', 'data_term', 'field_token');
    }

    /**
     * Save custom lead data
     *
     * @param int $lead_id Lead record id
     * @param array $data data to save
     * @return boolean true when done
     *
     * @author Rico Celis
     * @access public
     */
    static function saveLeadData($lead_id, $data, $options = array()) {
        if (!empty($data)) {
            $current_user = wp_get_current_user();
            foreach ($data as $term => $value) {
                $record = self::where("lead_id", $lead_id)->where("data_term", $term);
                $note = $value;
                if (@$record->data_id) { // update
                    $record->data_value = $value;
                } else {
                    $record = new LeadData;
                    $record->lead_id = $lead_id;
                    $record->data_term = $term;
                    if (is_array($value)) {
                        $note = implode(",", $value);
                        $value = implode("\n", $value);
                    }
                    $record->data_value = $value;
                }
                $record->save();
                // history
                if (@$options['add_history']) {
                    if (!empty($note)) {
                        $history = new LeadHistory;
                        if (empty($value))
                            $value = "empty";
                        $history->history_type = BREW_LEAD_HISTORY_TYPE_UPDATED;
                        $history->history_event = sprintf("{{%s}} changed to %s", $term, $note);
                        $history->wp_user_id = $current_user->ID;
                        $history->lead_id = $lead_id;
                        $history->save();
                    }
                }
            }
        }
        return true;
    }

}
