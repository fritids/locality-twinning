<?php
$option = get_option('ac_option');

?>
<div class="wrap">

    <h2>Answer Centre Option</h2>

    <?php if($_GET['option_update'] == 'true'): ?>
    <div id="setting-error-settings_updated" class="updated settings-error">
        <p><strong>Settings saved.</strong></p>
    </div>
    <?php endif ?>

    <form id="" method="post">
        <table class="form-table">
            <tbody>

                <tr valign="top">
                    <th scope="row">
                        <label for="question_auto_publish">Question publish without approval</label>
                    </th>
                    <td>
                        <select name="ac_option[question_auto_publish]" id="question_auto_publish">
                            <option value="0" <?php if($option['question_auto_publish'] == '0') echo 'selected="selected"' ?>>No</option>
                            <option value="1" <?php if($option['question_auto_publish'] == '1') echo 'selected="selected"' ?>>Yes</option>
                        </select>

                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="question_approval_email_from_email">Email - From Email</label>
                    </th>
                    <td>
                        <input name="ac_option[question_approval_email_from_email]" type="text" id="question_approval_email_from_email" value="<?php echo $option['question_approval_email_from_email'] ?>" class="regular-text">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="question_approval_email_from_name">Email - From Name</label>
                    </th>
                    <td>
                        <input name="ac_option[question_approval_email_from_name]" type="text" id="question_approval_email_from_name" value="<?php echo $option['question_approval_email_from_name'] ?>" class="regular-text">
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="question_approval_email_from_subject">Question Approval Email - Subject</label>
                    </th>
                    <td>
                        <input name="ac_option[question_approval_email_from_subject]" type="text" id="question_approval_email_from_subject" value="<?php echo $option['question_approval_email_from_subject'] ?>" class="regular-text">
                    </td>
                </tr>


                <tr valign="top">
                    <th scope="row">
                        <label for="question_approval_email">Question Approval Email - Body</label>
                    </th>
                    <td>
                        <textarea rows="8" cols="80" name="ac_option[question_approval_email]" id="question_approval_email"><?php echo $option['question_approval_email'] ?></textarea>
                        <br />
                        <span class="description">Hint here will be here</span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="answer_approval_email_from_subject">Answer Approval Email - Subject</label>
                    </th>
                    <td>
                        <input name="ac_option[answer_approval_email_from_subject]" type="text" id="answer_approval_email_from_subject" value="<?php echo $option['answer_approval_email_from_subject'] ?>" class="regular-text">
                    </td>
                </tr>


                <tr valign="top">
                    <th scope="row">
                        <label for="answer_approval_email">Answer Approval Email - Body</label>
                    </th>
                    <td>
                        <textarea rows="8" cols="80" name="ac_option[answer_approval_email]" id="answer_approval_email"><?php echo $option['answer_approval_email'] ?></textarea>
                        <br />
                        <span class="description">Hint here will be here</span>
                    </td>
                </tr>

                <tr valign="top">
                    <th scope="row">
                        <label for="blogdescription">Tagline</label>
                    </th>
                    <td>
                        <input name="blogdescription" type="text" id="blogdescription" value="Just another WordPress site" class="regular-text">
                        <span class="description">In a few words, explain what this site is about.</span>
                    </td>
                </tr>

            </tbody>
        </table>
        <p class="submit">
            <input type="submit" name="update-option" id="submit" class="button-primary" value="Save Changes">
        </p>
    </form>

</div>