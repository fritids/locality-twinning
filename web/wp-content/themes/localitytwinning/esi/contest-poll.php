<?php
require '../../../../wp-load.php';
$theme_option = get_option("wheels_theme_options");
?>
<div data-controller="PollController" class="poll-section clearfix">
    <div class="contest">
        <?php $options = get_option( 'wheels_theme_options' ); ?>
        <?php if( $options['show_contest'] ): ?>
        <img src="<?php esc_attr_e( $options['contest_image_url'] ); ?>" width="484" height="274" alt="Contest prize"/>
        <div class="contest-text">
            <a href="#" data-controller="ModalTriggerController" data-modal="#login-signup">
                <p><?php esc_attr_e( $options['contest_title'] ); ?></p>
            </a>
            <a href="<?php esc_attr_e( $options['contest_more_link'] ); ?>" title="Find out more"><strong>Find out more</strong></a>
        </div>
        <?php endif; ?>
    </div>

    <div class="poll">

        <h3>Wheels Poll</h3>
        <?php echo $theme_option['polldaddy']?>
        <?php /*
        <div class="poll-text">
            <div class="wrap">
                <p>Do you plan to shop for a hybrid vehicle this year?</p>
                <a href="#" class="sponsor">Sponsored</a></div>
        </div>
        <form>
            <fieldset>
                <label>
                    <input data-role="none" name="answer" type="radio" value="0"/>
                    Most likely </label>
                <label>
                    <input data-role="none" name="answer" type="radio" value="1" checked="checked"/>
                    Just looking </label>
                <label>
                    <input data-role="none" name="answer" type="radio" value="2"/>
                    Not this year but later </label>
                <label>
                    <input data-role="none" name="answer" type="radio" value="3"/>
                    Never </label>
            </fieldset>
            <input data-role="none" name="submit" id="poll-submit" type="submit" value="Vote" class="formbtn green"/>
            <a id="view-poll-results" href="#" class="primary">View Results</a>
        </form>
        <div class="poll-results">
            <div class="result-row"><span class="number">20%</span><span>Most likely</span>
                <div class="bar"><span>20%</span></div>
            </div>
            <div class="result-row"><span class="number">10%</span><span>Just looking</span>
                <div class="bar"><span>10%</span></div>
            </div>
            <div class="result-row"><span class="number">30%</span><span>Not this year but later</span>
                <div class="bar"><span>30%</span></div>
            </div>
            <div class="result-row"><span class="number">40%</span><span>Never</span>
                <div class="bar"><span>30%</span></div>
            </div>
            <a id="view-poll-form" href="#" class="primary">Back to Poll</a>
        </div>
        */?>
    </div>
</div>