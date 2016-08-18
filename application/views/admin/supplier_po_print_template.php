<?php
/**
 * Created by PhpStorm.
 * User: alvin
 * Date: 7/20/15
 * Time: 3:30 PM
 */
?>
<p style="width:1024px; text-align: center; margin: 0px auto; font-size:30px;">
C/12, Jayshree Indl. Estate,
Near H P Gas Godown, Goddev Fatak Rd., Bhayandar(E),
Dist: Thane, Pin: 401105, MAHARASHTRA. INDIA.
</p>
<hr>

<div style="width:100%; margin: 0px auto;">
    <table align="center" cellpadding="5" border="0">
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" style="text-align: center;"><strong>PURCHASE ORDER</strong></td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="2" style=" width: 33em;"><strong><?php echo $suppliers[0]['supplier_name'];?></strong></td>
            <td style=" width: 5em;"><strong>PO # </strong></td>
            <td style=" width: 5em;"><?php echo $supplier_po[0]['po_no'];?></td>

        </tr>
        <tr>
            <td colspan="2" rowspan="2" style=" width: 33em; vertical-align: top;">
                <?php
                $address = explode(',', strip_tags($suppliers[0]['address']));
                foreach($address as $keyadd => $valadd){
                  echo $valadd . " <br>";
                }
//                echo ;
                ?>

            </td>
            <td style="width: 5em;"><strong>PO Date</strong></td>
            <td style="width: 5em;"><?php echo date('d-m-Y', strtotime($supplier_po[0]['po_date']));?></td>
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
        <td colspan="4">Dear Sir,</td>
        </tr>

        <tr>
            <td colspan="4">Please send following items/material as early as possible.</td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">
                <table align="center" cellpadding="5" border="1" FRAME="box" style="width: 100%; border-collapse: collapse; border: 0.1em solid black;">
                    <tr>
                        <td align="center" class="title_bold" style="width: 4em;">Sr. No.</td>
                        <td align="center" class="title_bold" style="width: 20em;">Items</td>
                        <td align="center" class="title_bold" style="width: 4em;">Qty</td>
                        <td align="center" class="title_bold" style="width: 4em;">Unit</td>
                        <td align="center" class="title_bold" style="width: 4em;">Rate<br/>(In Rs.)</td>
                    </tr>
                    <?php
                    $additionals_fields = json_decode($supplier_po[0]['supplier_po_details'], true);
                    if(!empty($additionals_fields)) {
                        foreach ($additionals_fields as $key => $val) {
                            ?>
                            <tr>
                                <td align="center"><?php echo $key+1; ?></td>
                                <td align="left"><?php echo $val['item']; ?></td>
                                <td align="center"><?php echo $val['qty']; ?></td>
                                <td align="center"><?php echo $val['unit']; ?></td>
                                <td align="center"><?php echo $val['rate']; ?></td>

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

        <!--<tr>
            <td colspan="4">
        <table align="center" cellpadding="5" style=" border-collapse: collapse;" border="1">
        <tr>
            <th>Material</th>
            <th>Size</th>
            <th>Qty</th>
            <th>Rate</th>
            <th>Taxes</th>
            <th>Delivery Period</th>
            <th>Payment Terms</th>
            <th>Transport through</th>
        </tr>

        <tr>
            <td align="center"><?php /*echo $types['materials']['type'];*/?>  <?php /*echo $supplier_po[0]['material'];*/?></td>
            <td align="center">For ROUND: <?php /*echo $supplier_po[0]['size_round'];*/?>  <?php /*echo $supplier_po[0]['size_round_type'];*/?>,
                <br>
                For Sheet/Flat: Thickness <?php /*echo $supplier_po[0]['size_thickness'];*/?> in <?php /*echo $supplier_po[0]['size_thickness_type'];*/?>,
                <br>
                Width <?php /*echo $supplier_po[0]['size_width'];*/?> in <?php /*echo $supplier_po[0]['size_width_type'];*/?></td>
            <td align="center"><?php /*echo $supplier_po[0]['qty'];*/?> in <?php /*echo $types['qty']['type'];*/?></td>
            <td align="center"><?php /*echo $supplier_po[0]['rate'];*/?> in <?php /*echo $types['rate']['type'];*/?></td>
            <td align="center"><?php /*echo $supplier_po[0]['taxes'];*/?> %</td>
            <td align="center"><?php /*echo $supplier_po[0]['delivery_period'];*/?> in <?php /*echo $types['delivery']['type'];*/?></td>
            <td align="center"><?php /*echo $supplier_po[0]['payment_terms'];*/?></td>
            <td align="center"><?php /*echo $supplier_po[0]['transport_through'];*/?></td>

        </tr>
        </table>
            </td>
        </tr>-->
       <!-- <tr>
            <td class="title_bold">Material: </td>
            <td width="20">:</td>
            <td><?php /*echo $types['materials']['type'];*/?>  <?php /*echo $supplier_po[0]['material'];*/?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Size</td>
            <td width="20">:</td>
            <td>For ROUND: <?php /*echo $supplier_po[0]['size_round'];*/?>  <?php /*echo $supplier_po[0]['size_round_type'];*/?>,
            <br>
                For Sheet/Flat: Thickness <?php /*echo $supplier_po[0]['size_thickness'];*/?> in <?php /*echo $supplier_po[0]['size_thickness_type'];*/?>,
                <br>
                Width <?php /*echo $supplier_po[0]['size_width'];*/?> in <?php /*echo $supplier_po[0]['size_width_type'];*/?>
            </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Qty</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_po[0]['qty'];*/?> in <?php /*echo $types['qty']['type'];*/?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Rate</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_po[0]['rate'];*/?> in <?php /*echo $types['rate']['type'];*/?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Taxes</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_po[0]['taxes'];*/?> %</td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Delivery Period</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_po[0]['delivery_period'];*/?> in <?php /*echo $types['delivery']['type'];*/?></td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Payment Terms</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_po[0]['payment_terms'];*/?> </td>
        </tr>
        <tr>
            <td colspan="3">&nbsp;</td>
        </tr>
        <tr>
            <td class="title_bold">Transport through<br>(Transporter Name)</td>
            <td width="20">:</td>
            <td><?php /*echo $supplier_po[0]['transport_through'];*/?></td>
        </tr>-->
        <!--<tr>
            <td colspan="3">&nbsp;</td>
        </tr>-->
        <!--
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr><td colspan="3">&nbsp;</td></tr>
                <tr>
                    <td colspan="3">Your earliest reply is appreciated.</td>
                </tr>
                <tr>
                    <td colspan="3">
                        With Regards,<br><br>

                        For KALP ENGINEERING<br>
                        Raju Makwana<br>
                        (Purchase Dept)

                    </td>
                </tr>-->
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">
                <table align="center" cellpadding="5" FRAME="border" style="width:100%; border: 0.1em solid black;">
                    <tr>
                        <td style="padding-left:10px;"><strong>MVAT TIN</strong> </td>
                        <td style="width:32em;">27050985877V</td>
                        <td><strong>ECC No</strong> </td>
                        <td style="width: 10em;">AANFK0443LEM001</td>
                    </tr>
                    <tr>
                        <td style="padding-left:10px;"><strong>C.S.T TIN</strong> </td>
                        <td style="width:32em;">27050985877C</td>
                        <td><strong>PAN</strong> </td>
                        <td style="width: 10em;">AANFL0443L</td>
                    </tr>
                </table>
            </td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>

        <tr>
            <td colspan="4">

                <table align="center" cellpadding="5" FRAME="border" style="width:100%; word-wrap: break-word; border: 0.1em solid black;">
                    <tr>
                        <td colspan="2" style="padding-left:10px;">TERMS & CONDITIONS:</td>
                    </tr>
                    <tr>
                        <td align="right" style="vertical-align: text-top; width: 0.5em;">1.</td>
                        <td align="left" style="word-wrap: break-word;">Please mention our PO No & Date in your delivery challan & invoice.</td>
                    </tr>
                    <tr>
                        <td align="right" style="vertical-align: text-top; width: 0.5em;">2.</td>
                        <td align="left" style="word-wrap: break-word;">Please submit one Invoice for each delivery challan.</td>
                    </tr>
                    <tr>
                        <td align="right" style="vertical-align: text-top; width: 0.5em;">3.</td>
                        <td align="left" style="word-wrap: break-word;">Apply taxes whatever applicable.</td>
                    </tr>
                    <tr>
                        <td align="right" style="vertical-align: text-top; width: 0.5em;">4.</td>
                        <td align="left" style="word-wrap: break-word;">Excise Gate Pass should accompany with delivery challan whenever apply.</td>
                    </tr>
                    <?php
                    $terms = json_decode($supplier_po[0]['terms_n_cond'], true);
                    if(!empty($terms)) {
                        foreach ($terms as $key => $val) {
                            ?>
                            <tr>
                                <td align="right" style="vertical-align: text-top; width: 0.5em;"><?php echo $key+5; ?>.</td>
                                <td align="left" style="word-wrap: break-word;"><?php echo rtrim($val['terms']); ?></td>

                            </tr>
                        <?php
                        }
                    }
                    ?>
                </table>
            </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="right">Created by: <?php echo rtrim($created_by);?> </td>
        </tr>
        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4">
                <strong>For KALP ENGINEERING</strong><br><br><br>
                Raju Makwana<br>
                Partner
            </td>
        </tr>

        <tr>
            <td colspan="4">&nbsp;</td>
        </tr>
        <tr>
            <td colspan="4" align="left"><strong><?php echo $supplier_po[0]['format_no']; ?></strong></td>
        </tr>
        <tr>
            <td colspan="4" align="center" style="text-transform: uppercase;"><hr />*This is computer generated PO, it does not require signature*</td>
        </tr>
    </table>
</div>
