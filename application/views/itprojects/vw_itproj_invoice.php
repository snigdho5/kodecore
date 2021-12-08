<!DOCTYPE html>
<html>
   <head>
      <meta charset="utf-8" />
      <title>invoice</title>
      <meta name="viewport" content="width=device-width, initial-scale=1.0" />
      <link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300;0,400;0,500;0,600;0,700;0,800;1,500&display=swap" rel="stylesheet">
      <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
   </head>
   <body style="font-family: 'Open Sans', sans-serif;">

   <p><button onclick="window.print();return false" style="border-style: groove;"><i class="fa fa-print" aria-hidden="true"></i> Print</button></p>

   <table style="margin: 0 auto; border-collapse: collapse;" cellpadding="2" cellspacing="0">
   <tbody>
      <tr>
         <td
            style="font-size:24px; color: #000; text-align: center; font-weight: 600; text-transform: capitalize; margin: 0;">
            Proforma Tax Invoice
         </td>
      </tr>
      <tr>
         <td
            style="font-size:11px; color: #000; text-align: center; font-weight: 400; text-transform: capitalize; margin: 0;">
            Proforma Invoice
         </td>
      </tr>
   </tbody>
</table>
<table style="margin: 0 auto; border-collapse: collapse;" border="1" cellpadding="2" cellspacing="0">
   <tbody>
      <tr>
         <td colspan="2" rowspan="4">
            <p><img src="<?php echo $logo; ?>" style="width: 100px;"></p>
         </td>
         <td colspan="3" rowspan="4" >
               
               <table>
               <tr>
               <td style="font-size:13px; color: #000; text-align: left; font-weight: 600; text-transform: capitalize;">KODECORE</td>
               </tr>
               <tr>
               <td style="font-size:11px; color: #000; text-align: left;  font-weight: 500; text-transform: capitalize;">Astra Tower, ANO 402, 4th Floor, <br>
               Plot No- IIC/1, Action Area- II-C <br>
               Akankha More, Newtown, Rajarhat <br>
               Kolkata</td>
               </tr>
               </table>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">GSTIN/UIN: 19FJKPK1540P1ZM</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">State Name : West Bengal, Code : 19</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">E-Mail : account@kodecore.com</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Kodecore.com</p>
         </td>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Consignee (Ship to)</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">KCIN/PIN/001</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dated</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">22-Sep-21</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Delivery Note</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Mode/Terms of Payment</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Reference No. & Date.</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Other References</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Buyer's Order No.</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dated</p>
         </td>
      </tr>
      <tr>
         <td colspan="5" rowspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Consignee (Ship to)</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">XYZ Pvt.Ltd</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">State Name : West Bengal, Code : 19</p>
         </td>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dispatch Doc No</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Delivery Note Date</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Dispatched through</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Destination</p>
         </td>
      </tr>
      <tr>
         <td colspan="5" rowspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Buyer (Bill to)</p>
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? $payoutData->first_name . ' ' . $payoutData->last_name : '' ?></p>
            
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Abc</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">NBCV</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">GSTIN/UIN : XXXXXXXXXXXXX</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">
            </p>
         </td>
      </tr>
      <tr>
         <td colspan="9" style="vertical-align: top;">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Terms of Delivery</p>
         </td>
      </tr>
      <tr>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Sl No</p>
         </td>
         <td colspan="4">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Description of Services</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">HSN/SAC</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Quantity</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Rate</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">per</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Amount</p>
         </td>
      </tr>
      <tr>
         <td style="vertical-align: top">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">01</p>
         </td>
         <td colspan="4">
            <p
               style="font-size:13px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? $payoutData->proj_title : '' ?></p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">(-) Royalty (<?php echo (!empty($payoutData)) ? $payoutData->royalty : '' ?>%)</p>
           
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">CGST</p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">SGST</p>
         </td>
         <td colspan="2" style="vertical-align: top">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9985</p>
            <p></p>
         </td>
         <td colspan="2" style="vertical-align: top">
            <p>&nbsp;</p>
         </td>
         <td colspan="3" style="vertical-align: top">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst/2) : '' ?></p>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst/2) : '' ?></p>
         </td>
         <td style="vertical-align: top">
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p>&nbsp;</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">%</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">%</p>
         </td>
         <td style="vertical-align: top">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? $payoutData->amount : '' ?></p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? $payoutData->royalty_rate : '' ?></p>
            
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">&nbsp;</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate/2) : '' ?>
            </p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate/2) : '' ?></p>
         </td>
      </tr>
      <tr>
         <td>
            <p>&nbsp;</p>
         </td>
         <td colspan="4">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Total</p>
         </td>
         <td colspan="2">
            <p>&nbsp;</p>
         </td>
         <td colspan="2">
            <p>&nbsp;</p>
         </td>
         <td colspan="3">
            <p>&nbsp;</p>
         </td>
         <td>
            <p>&nbsp;</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">₹ <?php echo (!empty($payoutData)) ? $payoutData->subtotal_amt : '' ?></p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Amount Chargeable (in words) E. & O.E</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">INR <?php echo (!empty($payoutData)) ? convert_number($payoutData->subtotal_amt) : '' ?> Only</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">E. & O.E</p>
         </td>
      </tr>
      <tr>
         <td colspan="1" rowspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">HSN/SAC</p>
         </td>
         <td rowspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Taxable Value</p>
         </td>
         <td colspan="4">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Central Tax</p>
         </td>
         <td colspan="5">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">State Tax</p>
         </td>
         <td rowspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Total Tax Amount</p>
         </td>
      </tr>
      <tr>
         <td colspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">rate</p>
         </td>
         <td colspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">AM</p>
         </td>
         <td colspan="2">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">RT</p>
         </td>
         <td colspan="3">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">AM</p>
         </td>
      </tr>
      <tr>
         <td >
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">9985</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->subtotal_amt) : '' ?></p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst/2) : '' ?>%</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate/2) : '' ?></p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst/2) : '' ?>%</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate/2) : '' ?></p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? $payoutData->gst_rate : '' ?></p>
         </td>
      </tr>
      <tr>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Total</p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->subtotal_amt) : '' ?></p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">&nbsp;</p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate/2) : '' ?></p>
         </td>
         <td colspan="2">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">&nbsp;</p>
         </td>
         <td colspan="3">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate/2) : '' ?></p>
         </td>
         <td>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"><?php echo (!empty($payoutData)) ? ($payoutData->gst_rate) : '' ?></p>
         </td>
      </tr>
      <tr>
         <td colspan="14">
            <p
               style="font-size:12px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Tax Amount (in words) : <b>INR <?php echo (!empty($payoutData)) ? convert_number($payoutData->gst_rate) : '' ?> Only</b></p>
         </td>
      </tr>
      <tr>
         <td colspan="6"></td>
         <td colspan="8">
            <p style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Company's Bank Details</p>
         </td>
      </tr>
      <tr>
         <td colspan="6"></td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">A/c Holder's Name: <b>TRUVISORY KODECORE (OPC) PVT.Ltd</b></p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 500; text-transform: capitalize;"> Bank Name:</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;"> Bank NameBank Name</p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 500; text-transform: capitalize;"> A/c No:</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">X Y Z B A N K </p>
         </td>
      </tr>
      <tr>
         <td colspan="6">
            <p
               style="font-size:11px; color: #000; text-align: right; margin: 5px 0px; font-weight: 500; text-transform: capitalize;">Branch & IFS Code:</p>
         </td>
         <td colspan="8">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">X Y Z B r a n c h</p>
         </td>
      </tr>
      <tr>
         <td colspan="7">
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">Declaration</p>
            <p
               style="font-size:11px; color: #000; text-align: left; margin: 5px 0px; font-weight: 400; text-transform: capitalize;">We declare that this invoice shows the actual price of the goods described and that all particulars
               are true and correct.</p>
         </td>
         <td colspan="7" style="text-align: right;">
            <p style="font-size:11px; color: #000; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">for KODECORE</p>
            <p style="margin: 5px 0px; "><img src="<?php echo $sign; ?>" style=" "></p>
            <p style="font-size:11px; color: #000; margin: 5px 0px; font-weight: 600; text-transform: capitalize;">Authorised Signatory</p>
         </td>
      </tr>
   </tbody>
</table>
<table style="margin: 0 auto; border-collapse: collapse;" cellpadding="2" cellspacing="0">
   <tbody>
      <tr>
         <td
            style="font-size:13px; color: #000; text-align: center; font-weight: 600; text-transform: capitalize; margin: 0;">Subject To Kolkata <br>Jurisdiction</td>
      </tr>
      <tr>
         <td
            style="font-size:11px; color: #000; text-align: center; font-weight: 400; text-transform: capitalize; margin: 0;">This Is A Kodecore Original Invoice </td>
      </tr>
   </tbody>
</table>
</body>
</html>