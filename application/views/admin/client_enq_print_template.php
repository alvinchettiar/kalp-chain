<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/20/15
 * Time: 3:30 PM
 */
?>

<hr>

<div style="width:100%; margin: 0px auto;">
    <table align="center" cellpadding="5" border="0">
        <tr>
            <td colspan="4" align="center"><strong>ENQUIRY</strong></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="2" style="width: 29em;"><strong><?php echo $supplier_details[0]['supplier_name'];?></strong></td>
            <td><strong>Enquiry No</strong></td>
            <td align="right" style="width: 10em;"><?php echo $supplier_enq[0]['enquiry_no'];?></td>
        </tr>

        <tr>
            <td colspan="2" >
                <?php
                $address = explode(',', strip_tags($supplier_details[0]['address']));
                foreach($address as $keyadd => $valadd){
                    echo $valadd . " <br>";
                }
                //                echo ;
                ?>
                <?php echo 'Pincode - ' . $supplier_details[0]['pincode'];?><br>
                <?php echo $supplier_details[0]['district'];?>

            </td>
            <td valign="top"><strong>Enquiry Date</strong></td>
            <td align="left" valign="top"><?php echo date('d-m-Y', strtotime($supplier_enq[0]['date']));?></td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">Please send your competitive rate with terms & condition for following items.</td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">
            <table align="center" cellpadding="5" border="2" FRAME="box" style="width: 100%; border-collapse: collapse;">
                <tr>
                <td align="center" class="title_bold" style="width: auto;">Sr. No.</td>
                <td align="center" class="title_bold" style="width: 30em;">Items</td>
                <td align="center" class="title_bold"style="width: auto;">Qty</td>
                <td align="center" class="title_bold"style="width: auto;">Unit</td>
                </tr>
                <?php
                $additionals_fields = json_decode($supplier_enq[0]['supplier_enquiry_details'], true);
                if(!empty($additionals_fields)) {
                    foreach ($additionals_fields as $key => $val) {
                        ?>
                        <tr>
                            <td align="center"><?php echo $key+1; ?></td>
                            <td align="left"><?php echo $val['item']; ?></td>
                            <td align="center"><?php echo $val['qty']; ?></td>
                            <td align="center"><?php echo $val['unit']; ?></td>

                        </tr>
                    <?php
                    }
                }
                ?>
            </table>

            <!--<td align="center"><strong>Sr. No.</strong></td>
            <td align="center"><strong>Items</strong></td>
            <td align="center"><strong>Qty</strong></td>
            <td align="center"><strong>Unit</strong></td>-->
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">
            <table border="1" style="border-collapse: collapse; width: 100%;" frame="box" cellpadding="5">
                <tr>
                <td>NOTE: Please mention terms & condition (i.e. taxes, payment, delivery & etc.)</td>
                </tr>
            </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="right">Created by: Manoj</td>
        </tr>

        <!--<tr>
            <td class="title_bold">Material: </td>
            <td width="20">:</td>
            <td><?php /*echo $types['materials']['type'];*/?>  <?php /*echo $supplier_enq[0]['material'];*/?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Size</td>
            <td width="20">:</td>
            <td>For ROUND: <?php /*echo $supplier_enq[0]['size_round'];*/?>  <?php /*echo $supplier_enq[0]['size_round_type'];*/?>,
            <br>
                For Sheet/Flat: Thickness <?php /*echo $supplier_enq[0]['size_thickness'];*/?> in <?php /*echo $supplier_enq[0]['size_thickness_type'];*/?>,
                <br>
                Width <?php /*echo $supplier_enq[0]['size_width'];*/?> in <?php /*echo $supplier_enq[0]['size_width_type'];*/?>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Qty</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_enq[0]['qty'];*/?> <?php /*echo $types['qty']['type'];*/?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Delivery Period</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_enq[0]['delivery_period'];*/?> mm</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Taxes</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_enq[0]['taxes'];*/?> %</td>
        </tr>

        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr><td colspan="3">&nbsp;</td></tr>
        <tr>
            <td colspan="3">Your earliest reply is appreciated.</td>
        </tr>-->
        <tr>
            <td colspan="4">

                <strong>For KALP ENGINEERING</strong><br><br><br>
                Raju Makwana<br>
                Authorised Signatory

            </td>
        </tr>
    </table>



</div>
