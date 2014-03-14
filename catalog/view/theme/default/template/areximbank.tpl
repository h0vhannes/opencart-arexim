<form action="<?php echo str_replace('&', '&amp;', $action); ?>" method="post" id="checkout">
  <?/*<input type="hidden" name="hostID" value="<?php echo $arca_hostid; ?>" />*/?>
  <input type="hidden" name="mid" value="<?php echo $areximbank_merchant; ?>" />
  <?/*<input type="hidden" name="tid" value="<?php echo $arca_tid; ?>" />
  <input type="hidden" name="additionalURL" value="<?php echo $areximbank_additionalurl; ?>" /> */?>
  <input type="hidden" name="orderID" value="<?php echo $areximbank_orderid; ?>" />
  <input type="hidden" name="amount" value="<?php echo $areximbank_amount; ?>" />
  <input type="hidden" name="currency" value="<?php echo $areximbank_currency; ?>" />
  <div class="buttons">
    <div class="right">
      <input type="submit" value="<?php echo $button_confirm; ?>" class="button" />
    </div>
  </div>
  <p><?php echo $areximbank_our_address; ?> <?php echo $address; ?></p>
  <p><?php echo $areximbank_referring_text; ?></p>
</form>
