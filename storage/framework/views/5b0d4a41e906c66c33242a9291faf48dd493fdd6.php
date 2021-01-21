<?php if(!empty($agencybankingApprovedData)) { foreach ($agencybankingApprovedData as $key => $value) { ?>
<tr>
  <td><?php echo $value->SettlementDate; ?></td>
  <td><?php echo $value->txn_data_type; ?></td>
  <td><?php echo $value->External_name." ".$value->External_sortcode." ".$value->External_bankacc; ?></td>
  <td><?php echo number_format($value->CashAmt_value,2,'.',''); ?></td>
</tr>
<?php } } ?>
<?php if(!empty($fp_outData)) { foreach ($fp_outData as $key => $value) { ?>
<tr >
  <td><?php echo $value->file_date; ?></td>
  <td>Faster Payment Out</td>
  <td><?php echo $value->OrigCustomerSortCode." ".$value->OrigCustomerAccountNumber; ?></td>
  <td><?php echo number_format($value->Amount,2,'.',''); ?></td>
</tr>
<?php } } ?>
<?php if(!empty($advData)) { foreach ($advData as $key => $value) { ?>
<tr >
  <td><?php echo $value->Processing_Date; ?></td>
  <td>Direct Debits</td>
  <td><?php echo $value->SUN." ".$value->Sun_Name." ".$value->Trans_Code; ?></td>
  <td><?php echo number_format($value->amount,2,'.',''); ?></td>
</tr>
<?php } } ?>