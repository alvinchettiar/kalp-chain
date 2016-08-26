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
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;"><strong>INSPECTION REPORT</strong></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1"><strong>Supplier Name</strong></td>
            <td align="left" style="width:20em;" colspan="3"><?php echo $suppliers[0]['supplier_name'];?></td>
        </tr>

        <tr>
            <td><strong>Supplier DC No.</strong></td>
            <td><?php echo $inspection_report[0]['supplier_dc_no'];?></td>
            <td align="right" style="width:15em;"><strong>DC Date </strong></td>
            <td align="right"><?php echo date('d-m-Y', strtotime($inspection_report[0]['supplier_dc_date']));?></td>

        </tr>

        <tr>
            <td colspan="1"><strong>Transporter Name</strong></td>
            <td colspan="3" style="width:20em;"><?php echo $inspection_report[0]['transporter_name'];?></td>

        </tr>

        <tr>
            <td><strong>LR No.</strong></td>
            <td><?php echo $inspection_report[0]['lr_no'];?></td>
            <td align="right" style="width:15em;"><strong>LR Date </strong></td>
            <td align="right"><?php echo ($inspection_report[0]['lr_date'] <> '0000-00-00') ? date('d-m-Y', strtotime($inspection_report[0]['lr_date'])) : '';?></td>

        </tr>


<!--        <tr>
            <td class="title_bold" width="200">Contact Person</td>
            <td width="20">:</td>
            <td><?php /*echo $suppliers[0]['contact_person'];*/?></td>

        </tr>-->
       <!-- <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold" width="200">Quotation No</td>
            <td width="20">:</td>
            <td><?php /*echo $quotation[0]['quotation_no'];*/?></td>

        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold" width="200">Quotation Date</td>
            <td width="20">:</td>
            <td><?php /*echo $quotation[0]['date'];*/?></td>

        </tr>-->
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4"><strong>Inspection Report</strong></td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">
                <table align="center" cellpadding="5" style="width: 100%; border-collapse: collapse;" border="1">
                    <tr>
                        <td align="center" class="title_bold" style="width:auto;">Sr. No.</td>
                        <td align="center" class="title_bold" style="width: 14em;">Item</td>
                        <td align="center" class="title_bold" style="width:auto;">Ord Qty</td>
                        <td align="center" class="title_bold" style="width:auto;">DC Qty</td>
                        <td align="center" class="title_bold" style="width:auto;">Recd Qty</td>
                        <td align="center" class="title_bold" style="width:auto;">Recd mtrl<br/>Dimension</td>
                        <td align="center" class="title_bold" style="width:auto;">Remarks</td>
                    </tr>
                    <?php
                    $additionals_fields = json_decode($inspection_report[0]['inspection_report_details'], true);
                    if(!empty($additionals_fields)) {
                        foreach ($additionals_fields as $key => $val) {
                            ?>
                            <tr>
                                <td align="center"><?php echo $key+1; ?></td>
                                <td align="left"><?php echo $val['item']; ?></td>
                                <td align="center"><?php echo $val['qty']; ?></td>
                                <td align="center"><?php echo $val['dc_qty']; ?></td>
                                <td align="center"><?php echo $val['recd_qty']; ?></td>
                                <td align="center"><?php echo $val['recd_material_dim']; ?></td>
                                <td align="center"><?php echo ($val['remarks'] == '0') ? 'OK' : 'Reject'; ?>
                                <?php
                                $remarks = $val['remarks'];
                                switch($remarks){
                                    case "0":
                                        break;
                                    case "1":
                                        echo "<br>".$val['remarks_remarks'];
                                        break;
                                }

                                ?>
                                </td>
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
            <td colspan="4" align="left"><strong><?php echo $inspection_report[0]['format_no']; ?></strong></td>
        </tr>

        <!--<tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="right">Created by: Manoj</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>For KALP ENGINEERING</strong><br><br><br>
                Raju Makwana<br>
                Authorised Signatory
            </td>
        </tr>-->
    </table>
</div>
