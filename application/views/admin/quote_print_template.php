<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/20/15
 * Time: 3:30 PM
 */
?>
<style>

    /*.title_bold {
        font-weight: 600;
    }*/
</style>
<hr>


<div style="width:100%; margin: 0px auto;">
    <table align="center" border="0" cellpadding="5">
        <tr>
            <td class="title_bold">Client Name </td>
            <td class="content"><?php echo $quote[1]['client_name'];?></td>
        </tr>
        <!--<tr>
            <td class="title_bold">Enquiry No:</td>
            <td><?php /*echo $quote[0]['enquiry_no'];*/?></td>
            <td class="title_bold">Enquiry Date:</td>
            <td><?php /*echo $quote[0]['enquiry_date'];*/?></td>
        </tr>
        -->
        <tr>
            <td class="title_bold">Quotation No </td>
            <td class="content"><?php echo $quote[0]['quotation_no'];?></td>
            <td class="title_bold" style="text-align: right; width: 19em;">Quotation Date </td>
            <td class="content" style="text-align: right;">
                <?php
                    $explode_quotation_date = explode(' ', $quote[0]['date']);
                    echo date('d-m-Y', strtotime($explode_quotation_date[0]));
                ?>
            </td>
        </tr>

        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
            <td colspan="4"></td>
        </tr>
        <tr>
        <td colspan="4" class="content">Respected Sir,</td>
        </tr>

        <tr>
            <td colspan="4" class="content">Our most competitive rate for your valuable inquiry of <strong><?php echo $quote[0]['enquiry_for'];?></strong> is as below.</td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <!--<td class="title_bold">Pitch</td>
            <td><?php /*echo $quote_miscellaneous[0]['pitch'];*/?> <?php /*echo $quote_miscellaneous[0]['pitch_val'];*/?></td>
            <td><?php /*echo $quote_miscellaneous[0]['pitch_details'];*/?></td>-->
            <td class="title_bold" align="top" valign="top">Item </td>
            <td colspan="3" class="content"><?php echo $enquiry[0]['item'];?></td>

        </tr>
        <tr>
            <td class="title_bold" valign="top">MOC </td>
            <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['moc'];?></td>
        </tr>
        <?php
        if($quote_miscellaneous[0]['link'] && $quote_miscellaneous[0]['link'] != '-') {
        ?>
            <tr>
                <td class="title_bold" valign="top">Link </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['link'];?> X <?php echo $quote_miscellaneous[0]['link2'];?>mm <?php echo ($quote_miscellaneous[0]['link3']) ? ' - ' . $quote_miscellaneous[0]['link3'] : '' ?></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['attachment'] && $quote_miscellaneous[0]['attachment'] != '-') {
        ?>
            <tr>
                <td class="title_bold" valign="top">Attachment </td>
                <td class="content"  colspan="1"><?php echo $quote_miscellaneous[0]['attachment']; ?> mm <?php echo ($quote_miscellaneous[0]['attachment_details']) ? 'thick '. $quote_miscellaneous[0]['attachment_details'] : ''; ?> <?php echo ($quote_miscellaneous[0]['attachment2']) ? ' - ' . $quote_miscellaneous[0]['attachment2'] : '' ?></td>
                <td class="content"></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['roller'] && $quote_miscellaneous[0]['roller'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Roller </td>
                <td class="content"  colspan="2"><?php echo $quote_miscellaneous[0]['roller']; ?> mm Dia
                    <?php
                    if($quote_miscellaneous[0]['roller2'] != "") {
                        ?>
                        X <?php echo $quote_miscellaneous[0]['roller2']; ?> mm long
                    <?php
                    }
                    echo ($quote_miscellaneous[0]['roller_details']) ? ' - ' . $quote_miscellaneous[0]['roller_details'] : '';
                    ?>
                </td>
                <td class="content"></td>
            </tr>

        <?php
        }
        if($quote_miscellaneous[0]['wip'] && $quote_miscellaneous[0]['wip'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">WIP </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['wip']; ?> mm <?php echo ($quote_miscellaneous[0]['wip2']) ? ' - ' . $quote_miscellaneous[0]['wip2'] : '' ?></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['bush'] && $quote_miscellaneous[0]['bush'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Bush </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['bush']; ?> mm Dia
                    <?php
                    if($quote_miscellaneous[0]['bush2'] != "") {
                        ?>
                        X <?php echo $quote_miscellaneous[0]['bush2']; ?> mm
                        long - <?php echo $quote_miscellaneous[0]['bush_val']; ?></td>
                    <?php
                    }
                    echo ($quote_miscellaneous[0]['bush3']) ? ' - ' . $quote_miscellaneous[0]['bush3'] : '';
                    ?>

                <!--            <img src="-->
                <?php //echo base_url().'/extras/img/dia.png';?><!--" width="50" height="50" align="center" />-->
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['pin'] && $quote_miscellaneous[0]['pin'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Pin </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['pin']; ?> mm Dia
                <?php
                if($quote_miscellaneous[0]['pin2'] != "") {
                ?>
                    X <?php echo $quote_miscellaneous[0]['pin2']; ?>
                    mm long - <?php echo $quote_miscellaneous[0]['pin_val']; ?></td>
                <?php
                }
                echo ($quote_miscellaneous[0]['pin3']) ? ' - ' . $quote_miscellaneous[0]['pin3'] : '';
                ?>
            </tr>
        <?php
        }
        ?>

        <?php
            $additional_fields = @json_decode($quote_miscellaneous[0]['additional_fields'], true);
            if(!empty($additional_fields)){

                foreach($additional_fields as $key => $val){
                    ?>
                    <tr>
                        <td class="title_bold"><?php echo $val['title'];?> </td>
                        <td class="content" colspan="3">
                            <?php echo $val['value'];?>
                        </td>
                    </tr>
                    <?php
                }
            }
        ?>

        <?php
        if($quote_miscellaneous[0]['qty'] || $quote_miscellaneous[0]['qty'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Qty </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['qty']; ?> <?php echo $quote_miscellaneous[0]['qty_val']; ?></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['rate'] && $quote_miscellaneous[0]['rate'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Rate </td>
                <td class="content" colspan="3">
                    <?php echo $quote_miscellaneous[0]['rate']; ?> <?php echo $quote_miscellaneous[0]['rate_val']; ?>
                    (Ex-Factory) <br>
                    <?php
                    if(!empty($quote_miscellaneous[0]['rate_additional_fields'])){
                        $rate_additional_fields = @json_decode($quote_miscellaneous[0]['rate_additional_fields'], true);
                        foreach($rate_additional_fields as $key_rate => $val_rate){
                            echo $val_rate['rate_quote_value'] ." ". $quote_miscellaneous[0]['rate_val'] . " (Ex-Factory)<br>";
                        }
                    }
                    ?>
                </td>
            </tr>
        <?php
        }
        ?>

        <?php
        //if($quote_miscellaneous[0]['pf'] && $quote_miscellaneous[0]['pf'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">P/F </td>
                <td class="content" colspan="3"><?php echo @$quote_miscellaneous[0]['pf']; ?> Extra</td>
            </tr>
        <?php
        //}
        ?>

        <?php
        if($quote_miscellaneous[0]['discount'] && $quote_miscellaneous[0]['discount'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Discount </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['discount']; ?> %</td>
            </tr>
        <?php
        }
        ?>

        <?php
        //if($quote_miscellaneous[0]['taxes'] && $quote_miscellaneous[0]['taxes'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Taxes </td>
                <td class="content" colspan="3"><?php echo ($quote_miscellaneous[0]['taxes'] == 'yes') ? '12.5 % Excise &' : ''; ?> 
                     <?php echo $quote_miscellaneous[0]['mvat_cst']; ?>
                    % <?php echo $quote_miscellaneous[0]['mvat_cst_val']; ?> <?php echo ($quote_miscellaneous[0]['mvat_cst_val']=="CST") ? "(against C Form)" : "";?> on actual
                </td>
            </tr>
        <?php
        //}
        ?>

        <?php
        if($quote_miscellaneous[0]['delivery'] && $quote_miscellaneous[0]['delivery'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Delivery </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['delivery']; ?> <?php echo $delivery_type_name[0]['type']; ?></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['weight'] && $quote_miscellaneous[0]['weight'] != '-') {
            ?>
            <tr>
                <td class="title_bold" valign="top">Weight </td>
                <td class="content" colspan="3"><?php echo $quote_miscellaneous[0]['weight']; ?> <?php echo $quote_miscellaneous[0]['weight_val']; ?></td>
            </tr>
        <?php
        }
        ?>

        <?php
        if($quote_miscellaneous[0]['notes']){
            ?>
            <tr>
                <td class="title_bold" valign="top">Remark/Note </td>
                <td class="content" colspan="3"><?php echo nl2br($quote_miscellaneous[0]['notes']);?></td>
            </tr>
        <?php
        }
        ?>
        <tr><td>&nbsp;</td></tr>
        <tr>
            <td colspan="4">We are waiting for your earliest reply.</td>
        </tr>
        <tr>
            <td colspan="4">
                Thanking you,<br>
                Yours faithfully,<br><br>

                For KALP ENGINEERING<br>
                Manoj Makwana<br>
                (+91 9324282212)

            </td>
        </tr>
    </table>



</div>
