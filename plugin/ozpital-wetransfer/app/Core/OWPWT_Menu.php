<?php

namespace Ozpital\WPWeTransfer\Core;

use Ozpital\WPWeTransfer\Core\OWPWT_Plugin as Plugin;
use Ozpital\WPWeTransfer\Core\OWPWT_Option as Option;

/**
 * Menu
 */
class OWPWT_Menu {
    /**
     * Regiser Admin Menu
     */
    public static function register() {
        add_action('admin_menu', function() {
            add_options_page(
                Plugin::getName(),
                Plugin::getName(),
                'manage_options',
                Plugin::getSlug(),
                function() {
                    return self::html();
                }
            );
        }, 99);
    }

    /**
     * Admin page HTML
     */
    public static function html() {
        ?>
        <div class="wrap">
            <h1><?php echo Plugin::getName(); ?></h1>

            <hr>

            <form method="post" action="options.php">
                <?php settings_fields(Plugin::getSlug() . '-options'); ?>
                <?php do_settings_sections(Plugin::getSlug() . '-options'); ?>
                <table class="form-table">
                    <?php foreach (Option::getAll() as $option) { ?>
                        <?php if ($option['name'] === 'api-key') { ?>
                        <?php $api_key_defined = !empty(getenv('WETRANSFER_API_KEY')) || defined('WETRANSFER_API_KEY') ? true : false; ?>
                        <tr valign="top">
                            <th scope="row">WeTransfer API Key</th>
                            <td>
                                <?php if ($api_key_defined) { ?>
                                    <p><strong>The `WETRANSFER_API_KEY` is currently being defined elsewhere in your code.</strong></p>
                                <?php } else { ?>
                                    <input type="text" name="<?php echo $option['name']; ?>" value="<?php echo esc_attr(get_option($option['name'])); ?>" class="regular-text" />
                                    <p class="description">Alternatively; WETRANSFER_API_KEY can be defined in your config or set as an environment variable.</p>
                                    <p class="description">Need an API key? Visit: <a href="https://developers.wetransfer.com/" target="_blank">https://developers.wetransfer.com/</a></p>
                                <?php } ?>
                            </td>
                        </tr>
                        <?php continue; ?>
                        <?php } ?>

                        <?php if ($option['name'] === 'success-script') { ?>
                        <tr>
                            <th scope="row">On Success Event</th>
                            <td>
                                <fieldset>
                                    <legend class="screen-reader-text">ozpital-wpwetransfer-success event listener</legend>
                                    <p>Javascript in this textarea will fire on successful transfer</p>
                                    <p><textarea name="<?php echo $option['name']; ?>" rows="10" cols="50" class="large-text code"><?php echo !empty(esc_attr(get_option($option['name']))) ? esc_attr(get_option($option['name'])) : ''; ?></textarea></p>
                                    <p class="description">Alternatively; you can listen for the `ozpital-wpwetransfer-success` event in your own script. eg:<br>
<pre><code>document.addEventListener('ozpital-wpwetransfer-success', function(event) {
    console.log(event);
});</code></pre>
                                    </p>
                                    <hr>
                                    <h3>Contact Form 7</h3>
                                    <p>A common request for this plugin is to populate a Contact Form 7 field with the url generated by a successful transfer. Clicking <a href="javascript:populateSuccessEvent();">here</a> will append some code to the above success event that you can tweak to populate your desired form field.</p>
                                    <script>
                                        populateSuccessEvent = function() {
                                            var successEventEditor = document.querySelector('textarea[name="success-script"]');

                                            var successEventPopulateFormScript = "// Get the CF7 field that we want to populate with a WeTransfer URL" + '\n';
                                            successEventPopulateFormScript += "var urlInputField = document.getElementById('wetransfer_url');" + '\n\n';
                                            successEventPopulateFormScript += "// Check that the CF7 field exists on the current page" + '\n';
                                            successEventPopulateFormScript += "if (urlInputField) {" + '\n';
                                            successEventPopulateFormScript += "    // Populate the CF& field with the URL" + '\n';
                                            successEventPopulateFormScript += "    urlInputField.value = event.detail.url;" + '\n';
                                            successEventPopulateFormScript += "}" + '\n';

                                            if (successEventEditor.value.length > 0) {
                                                successEventEditor.value = successEventEditor.value + '\n\n' + successEventPopulateFormScript;
                                            } else {
                                                successEventEditor.value = successEventPopulateFormScript;
                                            }
                                        }
                                    </script>
                                </fieldset>
                            </td>
                        </tr>
                        <?php continue; ?>
                        <?php } ?>

                    <?php } ?>
                </table>

                <?php submit_button(); ?>
            </form>
        </div>
        <?php
    }
}
