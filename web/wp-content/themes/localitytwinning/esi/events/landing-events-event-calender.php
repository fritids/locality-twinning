<?php

require $_SERVER['DOCUMENT_ROOT'] . '/wp-load.php';

global $wpdb;

$eventsRefTable = $wpdb->prefix . WHEELS_EVENTS_REF_TABLE;
$postTable = $wpdb->posts;
$today = date("Y-m-d");

$eventsData = $wpdb->get_results("SELECT DISTINCT DATE_FORMAT(events_start_date,'%Y-%m') as event_start_month , MONTHNAME(events_start_date) as event_start_monthname, YEAR(events_start_date) as event_start_year,
                                                 DATE_FORMAT(events_end_date,'%Y-%m') as event_end_month , MONTHNAME(events_end_date) as event_end_monthname, YEAR(events_end_date) as event_end_year,
                                                 TIMESTAMPDIFF(MONTH, events_start_date, events_end_date) as monthDiff
                                                 FROM $eventsRefTable
                                                 INNER JOIN $postTable ON $eventsRefTable.post_id = $postTable.ID
                                                 WHERE post_status = 'publish'
                                                 ORDER BY events_start_date ASC");

foreach($eventsData AS  $data):
    $newdata[$data->event_start_month] = $data->event_start_monthname ." ". $data->event_start_year;

    $monthDiff = (int)$data->monthDiff - 1;
    if( $monthDiff > 1 ){
        $start = strtotime( $data->event_start_month . '-01' );
        for($i = 0; $i <= $monthDiff; $i++ ){
            $tempMonthYear = strtotime('+1 month', $start);
            $newdata[date('Y-m', $tempMonthYear)] = date('F Y', $tempMonthYear);
            $start = $tempMonthYear;
        }
    }
    $newdata[$data->event_end_month] = $data->event_end_monthname ." ". $data->event_end_year;

endforeach;
unset($newdata['0000-00']);
ksort($newdata);

?>
<div class="event-calendar">
    <div class="heading"><h3>Event Calendar</h3>

        <form>
            <fieldset>
                <div class="date-container">
                    <select data-role="none"
                            name="date-selector" data-controller="ComboboxController"
                            data-readonly="true" class="date-selector ui-dark">
                        <?php  foreach ($newdata as $yearMonth => $event) : ?>
                        <option class="event-date" value="<?php echo $yearMonth;?>" <?php if($yearMonth == date("Y-m")) echo 'selected="selected"' ?> ><?php echo $event; ?></option>
                        <?php endforeach ?>
                    </select>
                </div>
            </fieldset>
        </form>
    </div>

    <div class="events-calender"></div>


</div>