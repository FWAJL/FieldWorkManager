<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="field_matrix"  class="data-form">
<fieldset class="field_sample_matrix_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="task_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="field_analyte_id" type="text" />
    </li>
        <li style="display: none;">
      <input name="location_id" type="text" />
    </li>
    <div id="tabs3a">    
     <fieldset>
                                            <legend>Example Field Sample Matrix Table</legend>
                                            <form action="" method="post">

                                                <table class="matrix_table">
                                                    <tr>
                                                        
                                                        <td style="text-align: center" class="tab_head" colspan="4" ><h2>Analytes</h2></td> 
                                                    </tr>

                                                    <tr>
                                                        <td class="tab_head">Locations</td>

                                                        <td style="text-align: center; width: 100px" class="tab_data">Dissolved Oxygen

                                                        </td>


                                                        <td style="text-align: center; width: 100px" class="tab_data">pH

                                                        </td>


                                                        <td style="text-align: center; width: 100px" class="tab_data">Temperature
                                                        </td>


                                                    </tr>


                                                    <tr>
                                                        <td class="tab_data">Well 1 
                                                        </td>



                                                        <td align="center">
                                                            <input type="checkbox" class="cbox_la" name="cbox_la[0]" value="1"
                                                                   >
                                                            <input name="sample_pair[0]" type="hidden" value="54,36"/>

                                                            <input name="nlyt[0]" type="hidden" value="36"/>
                                                            <input name="local[0]" type="hidden" value="54"/>
                                                            <input name="id[0]" type="hidden" value="0"/>
                                                        </td>



                                                        <td align="center">
                                                            <input type="checkbox" class="cbox_la" name="cbox_la[1]" value="1"
                                                                   >
                                                            <input name="sample_pair[1]" type="hidden" value="54,37"/>

                                                            <input name="nlyt[1]" type="hidden" value="37"/>
                                                            <input name="local[1]" type="hidden" value="54"/>
                                                            <input name="id[1]" type="hidden" value="1"/>
                                                        </td>



                                                        <td align="center">
                                                            <input type="checkbox" class="cbox_la" name="cbox_la[2]" value="1"
                                                                   >
                                                            <input name="sample_pair[2]" type="hidden" value="54,38"/>

                                                            <input name="nlyt[2]" type="hidden" value="38"/>
                                                            <input name="local[2]" type="hidden" value="54"/>
                                                            <input name="id[2]" type="hidden" value="2"/>
                                                        </td>


                                                    </tr>

                                                    <tr>
                                                        <td class="tab_data">Well 2
                                                        </td>



                                                        <td align="center">
                                                            <input type="checkbox" class="cbox_la" name="cbox_la[3]" value="1"
                                                                   >
                                                            <input name="sample_pair[3]" type="hidden" value="53,36"/>

                                                            <input name="nlyt[3]" type="hidden" value="36"/>
                                                            <input name="local[3]" type="hidden" value="53"/>
                                                            <input name="id[3]" type="hidden" value="3"/>
                                                        </td>



                                                        <td align="center">
                                                            <input type="checkbox" class="cbox_la" name="cbox_la[4]" value="1"
                                                                   >
                                                            <input name="sample_pair[4]" type="hidden" value="53,37"/>

                                                            <input name="nlyt[4]" type="hidden" value="37"/>
                                                            <input name="local[4]" type="hidden" value="53"/>
                                                            <input name="id[4]" type="hidden" value="4"/>
                                                        </td>



                                                        <td align="center">
                                                            <input type="checkbox" class="cbox_la" name="cbox_la[5]" value="1"
                                                                   >
                                                            <input name="sample_pair[5]" type="hidden" value="53,38"/>

                                                            <input name="nlyt[5]" type="hidden" value="38"/>
                                                            <input name="local[5]" type="hidden" value="53"/>
                                                            <input name="id[5]" type="hidden" value="5"/>
                                                        </td>


                                                    </tr>

                                                </table>
                                                <input type="checkbox" value="All" name="allbox" id="allbox" class="allbox"/>
                                                Check/Uncheck All
                                                <input name="save_anal_loc" type="submit" value="Save" />
                                            </form>

                                        </fieldset>
   </div>
    
</div>
 