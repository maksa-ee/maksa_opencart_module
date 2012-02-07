<?php echo $header; ?>
<div id="content" style="text-align:center;">

    <h1><img src="admin/view/image/payment/maksa_ee.png" /> Maksa.ee</h1>
    <br />
    <p id="maksa-loading-status">
        <img src="catalog/view/theme/default/image/maksa_ee_loading.gif" />
        <?php echo $text_please_wait; ?>
    </p>
    <script type="text/javascript">

    var maksaWaiting = true;
    function checkwaitingorder() {
        if (maksaWaiting) {
            $.ajax({
                type: "POST",
                async: true,
                url: "<?php echo $wait_link; ?>",
                data: '',
                success: function (status) {
                    if (status == 'ok') {
                        $("#maksa-result-div").show();
                        $("#maksa-payment-ok").show();
                        $("#maksa-loading-status").hide();
                        maksaWaiting = false;
                        setTimeout(function() {
                            window.location.href = "<?php echo $success_link; ?>";
                        }, 1000);
                    } else if(status == 'not_ok') {
                        $("#maksa-result-div").show();
                        $("#maksa-payment-fail").show();
                        $("#maksa-loading-status").hide();
                        maksaWaiting = false;
                    }
                }
            });

            setTimeout('checkwaitingorder()', 3000);
        }
    }
    checkwaitingorder();
    </script>

    <div style="display:none; text-align:center;" id="maksa-result-div">

        <div id="maksa-payment-ok" style="display:none;">
            <table align="center">
                <tr>
                    <td>
                        <img src="catalog/view/theme/default/image/maksa_ee_success.png" alt="<?php echo $text_payment_success; ?>"/>
                    </td>
                    <td>
                        <span style="color: green; font-size: 26px;"> <?php echo $text_payment_success; ?> </span>
                    </td>
                </tr>
            </table>
        </div>

        <div id="maksa-payment-fail" style="display:none;">
            <table align="center">
                <tr>
                    <td>
                        <img src="catalog/view/theme/default/image/maksa_ee_failure.png" alt="<?php echo $text_payment_failure; ?>"/>
                    </td>
                    <td>
                        <span style="color: red; font-size: 26px;"> <?php echo $text_payment_failure; ?> </span>
                    </td>
                </tr>
            </table>
            <br />
            <a class="button" href="<?php echo $order_link; ?>">
                <span><?php echo $text_go_to_order; ?> &raquo;</span>
            </a>
        </div>
    </div>

</div>
<?php echo $footer; ?>