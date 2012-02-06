<?php echo $header; ?>
<div id="content">

    <div class="breadcrumb">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a
                href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>

    <?php if ($error_warning) { ?>
    <div class="warning"><?php echo $error_warning; ?></div>
    <?php } ?>

    <div class="box">
        <div class="heading">
            <h1>
                <img src="view/image/payment.png" alt="" /> <?php echo $heading_title; ?>
            </h1>

            <div class="buttons">
                <a onclick="$('#form').submit();" class="button">
                    <span><?php echo $button_save; ?></span>
                </a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button">
                    <span><?php echo $button_cancel; ?></span>
                </a>
            </div>
        </div>

        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_test_mode; ?></td>
                        <td><select name="maksa_ee_test_mode">
                            <?php if ($maksa_ee_test_mode) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>

                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_client_id; ?>
                        </td>
                        <td>
                            <input type="text" name="maksa_ee_client_id" value="<?php echo $maksa_ee_client_id; ?>"/>
                            <?php if ($error_client_id) { ?>
                            <span class="error"><?php echo $error_client_id; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_public_key; ?>
                        </td>
                        <td>
                            <textarea style="width:560px; height:100px;" name="maksa_ee_public_key"><?php echo $maksa_ee_public_key; ?></textarea>
                            <?php if ($error_public_key) { ?>
                            <span class="error"><?php echo $error_public_key; ?></span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td>
                            <span class="required">*</span> <?php echo $entry_private_key; ?>
                        </td>
                        <td>
                            <textarea style="width:560px; height:230px;" name="maksa_ee_private_key"><?php echo $maksa_ee_private_key; ?></textarea>
                            <?php if ($error_private_key) { ?>
                            <span class="error"><?php echo $error_private_key; ?></span>
                            <?php } ?>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_completed_status; ?></td>
                        <td>
                            <select name="maksa_ee_completed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $maksa_ee_completed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_failed_status; ?></td>
                        <td>
                            <select name="maksa_ee_failed_status_id">
                                <?php foreach ($order_statuses as $order_status) { ?>
                                <?php if ($order_status['order_status_id'] == $maksa_ee_failed_status_id) { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>" selected="selected"><?php echo $order_status['name']; ?></option>
                                <?php } else { ?>
                                <option value="<?php echo $order_status['order_status_id']; ?>"><?php echo $order_status['name']; ?></option>
                                <?php } ?>
                                <?php } ?>
                            </select>
                        </td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_geo_zone; ?></td>
                        <td><select name="maksa_ee_geo_zone_id">
                            <option value="0"><?php echo $text_all_zones; ?></option>
                            <?php foreach ($geo_zones as $geo_zone) { ?>
                            <?php if ($geo_zone['geo_zone_id'] == $onpay_geo_zone_id) { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>" selected="selected">
                                    <?php echo $geo_zone['name']; ?>
                                </option>
                                <?php } else { ?>
                                <option value="<?php echo $geo_zone['geo_zone_id']; ?>">
                                    <?php echo $geo_zone['name']; ?>
                                </option>
                                <?php } ?>
                            <?php } ?>
                        </select></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td><select name="maksa_ee_status">
                            <?php if ($maksa_ee_status) { ?>
                            <option value="1" selected="selected"><?php echo $text_enabled; ?></option>
                            <option value="0"><?php echo $text_disabled; ?></option>
                            <?php } else { ?>
                            <option value="1"><?php echo $text_enabled; ?></option>
                            <option value="0" selected="selected"><?php echo $text_disabled; ?></option>
                            <?php } ?>
                        </select></td>
                    </tr>

                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td>
                            <input type="text" name="maksa_ee_sort_order" value="<?php echo $maksa_ee_sort_order; ?>" size="1"/>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>