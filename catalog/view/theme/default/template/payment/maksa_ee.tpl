<form action="<?php echo $action ?>" method="post" id="payment">
  <input type="hidden" name="signedRequest" value="<?php echo $signedRequest; ?>" />
</form>
<div class="buttons">
    <div class="right">
      <a onclick="$('#payment').submit();" class="button"><span><?php echo $button_confirm; ?></span></a>
    </div>
</div>