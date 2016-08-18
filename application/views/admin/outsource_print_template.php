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
            <td colspan="4" style="text-align: center;"><strong>OUTSOURCING</strong></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="1"><span style=""><strong>Name Job Worker</strong></span> </td>
            <td align="left" colspan="3" style="width:25em;"><?php echo $vendor[0]['vendor_name'];?></td>

        </tr>

        <tr>
            <td colspan="1"><span style=""><strong>Our Labour Job Challan No </strong></span></td>
            <td colspan="1" style="width:25em;"><?php echo $outsource[0]['labour_job_challan_no'];?></td>
            <td align="right" colspan="2"><strong>Our LIC Date</strong>&nbsp;&nbsp; <?php echo date('d-m-Y', strtotime($outsource[0]['lic_date']));?></td>

        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">
                <table align="center" cellpadding="5" style="width: 100%; border-collapse: collapse;" border="1">
                    <tr>
                        <td align="center" class="title_bold_outsource">Sr. No.</td>
                        <td align="center" class="title_bold_outsource">Work <br>Order</td>
                        <td align="center" class="title_bold_outsource">ITEMS</td>
                        <td align="center" class="title_bold_outsource">Type of<br>Process/<br>Parts</td>
                        <td align="center" class="title_bold_outsource">Dispatched<br> Qty</td>
                        <td align="center" class="title_bold_outsource">Require<br/>Qty</td>
                        <td align="center" class="title_bold_outsource">Job Worker<br>DC No</td>
                        <td align="center" class="title_bold_outsource">Job Worker<br>DC Date</td>
                        <td align="center" class="title_bold_outsource">Received<br>Qty</td>
                        <td align="center" class="title_bold_outsource">Require<br>Dimension</td>
                        <td align="center" class="title_bold_outsource">Recd <br>Dim 1</td>
                        <td align="center" class="title_bold_outsource">Recd <br>Dim 2</td>
                        <td align="center" class="title_bold_outsource">Recd <br>Dim 3</td>
                        <td align="center" class="title_bold_outsource">Recd <br>Dim 4</td>
                        <td align="center" class="title_bold_outsource">Recd <br>Dim 5</td>
                        <td align="center" class="title_bold_outsource">Remarks</td>
                    </tr>
                    <?php
                    
                    $additionals_fields = json_decode($outsource[0]['outsource_details'], true);
                    if(!empty($additionals_fields)) {
                        $count = 1;
                        foreach ($additionals_fields as $key => $val) {

                            ?>
                            <tr>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $key+1; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $wo_data[$val['wo']][0]['wo_no']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['item']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['types_process_parts']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['dispatched_qty']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['require_qty']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['job_worker_dc_no']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['job_worker_dc_date']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['recd_qty']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['require_dim']; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['recd_dim1-'.$count]; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['recd_dim2-'.$count]; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['recd_dim3-'.$count]; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['recd_dim4-'.$count]; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo $val['recd_dim5-'.$count]; ?></td>
                                <td class="table-content-text" align="center" style="word-wrap: break-word; width:0.1em;"><?php echo ($val['remarks'] == '0') ? 'OK' : 'Reject'; ?>
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
                            $count++;
                        }
                    }
                    ?>
                </table>

            </td>
        </tr>


        <tr>
            <td colspan="4">&nbsp;</td>
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
