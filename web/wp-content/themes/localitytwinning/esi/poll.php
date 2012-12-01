<?php
require '../../../../wp-load.php';
$theme_option = get_option("wheels_theme_options");
?>
<div data-controller="PollController" class="poll-section clearfix">
    <div class="poll">
        <h3>Wheels Poll</h3>
        <?php echo $theme_option['polldaddy']?>
        <?php /*
        <div class="poll-text">
            <div class="wrap">
                <p>Do you think US automakers make the worst vehicles?</p>
                <a href="#" class="sponsor">Sponsored</a></div>
        </div>
        <form>
            <fieldset>
                <label>
                    <input data-role="none" name="answer" type="radio" value="0"/>
                    Yes </label>
                <label>
                    <input data-role="none" name="answer" type="radio" value="1" checked="checked"/>
                    No </label>
            </fieldset>
            <input data-role="none" name="submit" id="poll-submit" type="submit" value="Vote" class="formbtn green"/>
            <a id="view-poll-results" href="#" class="primary">View Results</a>
        </form>
        <div class="poll-results">
            <div class="result-row"><span class="number">30%</span><span>Yes</span>

                <div class="bar"><span style="width:30%;">30%</span></div>
            </div>
            <div class="result-row"><span class="number">70%</span><span>No</span>

                <div class="bar"><span style="width:70%;">70%</span></div>
            </div>
            <a id="view-poll-form" href="#" class="primary">Back to Poll</a>
        </div>
        */ ?>
    </div>

</div>