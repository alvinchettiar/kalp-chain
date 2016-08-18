<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 8/22/15
 * Time: 5:35 PM
 */

?>

<div style="width:100%; margin: 100px auto;">
    <table align="center" cellpadding="10" style="border-collapse: collapse; border-color: grey;" border="1">
        <!--<th colspan="4">Parts Name</th>
        <th>Required Qty.</th>
        <th>Production Qty.</th>
        <th>Starting Date</th>
        <th>Completion Date</th>
        <th>OK Qty.</th>
        <th>Remarks</th>-->

        <tr align="center">
           <td colspan="4" valign="top"><strong>WORK ORDERWISE PRODUCTION RECORD</strong></td>
        </tr>
        <tr>
            <td colspan="2" valign="top">Name of Customer: <?php echo $wo_details[1]['client_name'];?></td>
            <td colspan="1" valign="top">PO No.: <?php echo $po_client[0]['po_no'];?></td>
            <td colspan="1" valign="top">PO Date: <?php echo date('d-m-Y', strtotime($po_client[0]['po_date']));?></td>
        </tr>
        <tr>
            <td colspan="2" rowspan="2" valign="top">Item: <?php echo $item ;?></td>
            <td colspan="1" valign="top">WO No.: <?php echo $wo_details[0]['wo_no'];?></td>
            <td colspan="1" valign="top">WO Date: <?php echo date('d-m-Y', strtotime($wo_details[0]['wo_date']));?></td>
        </tr>
        <tr>
            <td colspan="1" valign="top">Qty.: <?php echo $po_client[0]['qty'];?></td>
            <td colspan="1" valign="top">Due Date: <?php echo date('d-m-Y', strtotime($po_client[0]['delivery_date']));?></td>
        </tr>
        <tr>
            <td colspan="2" valign="top">MOC: <?php echo $po_client[0]['moc'];?></td>
            <td colspan="2" valign="top">Customer need delivery at: <?php echo $po_client[0]['delivery_place'];?></td>
        </tr>

        <tr>
            <td align="center"><strong>Parts Name</strong></td>
            <td align="center"><strong>Required Qty.</strong></td>
            <td align="center"><strong>Production Qty.</strong></td>
            <td align="center"><strong>Balance Qty.</strong></td>
        </tr>
        <?php
        if(!empty($wo_miscellaneous)) {
            foreach ($wo_miscellaneous as $key => $val) {
                ?>
                <tr>
                    <td align="center"><?php echo $val['pn']; ?></td>
                    <td align="center"><?php echo $val['pq']; ?></td>
                    <td align="center"><?php echo $val['pq']; ?></td>
                    <td align="center"><?php echo $val['bq']; ?></td>

                </tr>
            <?php
            }
        }
        ?>
        <tr>
            <td colspan="2" valign="top">Lengths: <?php echo $wo_details[0]['lengths'];?></td>
            <td colspan="1" valign="top">Total Bags: <?php echo $wo_details[0]['total_bags'];?></td>
            <td colspan="1" valign="top">Dispatch Through: <?php echo $wo_details[0]['dispatch_through'];?>
            </td>
        </tr>
        <tr>
            <td colspan="2" valign="top">Weight: <?php echo $wo_details[0]['weight'];?></td>
            <td colspan="1" valign="top">Our DC No. & Date: <br>
                <?php echo $wo_details[0]['dc_no'];?><br>
                <?php echo date('d-m-Y', strtotime($wo_details[0]['dc_date']));?>
            </td>
            <td colspan="1" valign="top">LR NO. & Date:<br>
                <?php echo $wo_details[0]['lr_no'];?><br>
                <?php echo date('d-m-Y', strtotime($wo_details[0]['dc_date']));?>
            </td>
        </tr>
        <tr>
            <td colspan="4" valign="top">Remarks (if any): <?php echo $wo_details[0]['remarks'];?></td>
        </tr>
        <tr class="noBorder">
            <td colspan="4"><?php echo $wo_details[0]['format_no']; ?></td>
        </tr>
      
    </table>
    
</div>

<style>
    tr.noBorder td {
        border-left: 1px solid white !important;
        border-right: 1px solid white !important;
        border-bottom: 1px solid white !important;
        /*border-right-style: hidden !important; 
        border-left-style: hidden !important; 
        border-bottom-style: hidden !important;
        border-color: white;*/
        padding:0px;
        font-weight: 600;
    }
</style>