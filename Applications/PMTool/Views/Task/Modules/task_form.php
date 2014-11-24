<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_info"  class="data-form">
<fieldset class="task_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="task_id" type="text" />
    </li>
        <li style="display: none;">
      <input name="project_id" type="text" />
    </li>
    <br />
    <li>
      <label><?php echo $resx["task_name"]; ?></label>
      <input name="task_name" type="text" />
    </li>
     <li>
      <label><?php echo $resx["task_deadline"]; ?></label>
      <input type="text"  name="task_deadline" id="datepicker"/>
    </li>
    <li>
      <label><?php echo $resx["task_instructions"]; ?></label>
      <textarea name="task_instructions" type="text"></textarea>
    </li>
 
      <label><?php echo $resx["triggers"]; ?></label>
      <table>
          <tr>
              <td class="checkbox"><input id="freq_list_box" type="checkbox" /><?php echo $resx["task_trigger_cal"]; ?></td>
              <td><select class="coc_list" id="freq_list">
                  <option value="" selected='selected'>Select</option>
                  <option value="Daily" >Daily</option>
                  <option value="Weekly">Weekly</option>
                  <option value="Two Weeks" >Two Weeks</option>
                  <option value="Monthly" >Monthly</option>
                  <option value="Two Months" >Two Months</option>
                  <option value="Quarterly" >Quarterly</option>
                  <option value="semi-Annual" >semi-Annual</option>
                  <option value="Annual" >Annual</option>
                </select>  
              </td>
            <tr>
             <td class="checkbox"><input name ="task_trigger_pm" id="manual_box" type="checkbox"><?php echo $resx["task_trigger_pm"]; ?></td>
             <td class="note"><div id="manual_note"><?php echo $resx["trigger_pm_note"]; ?></div></td>
            </tr>
            <tr>
              <td class="checkbox"><input id="external_box" type="checkbox"><?php echo $resx["task_trigger_ext"]; ?></td>
              <td id="external_note"><?php echo $resx["trigger_ext_note"]; ?></td>
            </tr>
                                                </table>

                                            <li>
                                            <label>Data Collection</label>
                                            <table>
                                                <tr>
                                                    <td class="checkbox"><input id="insp_note_box" type="checkbox" />Inspection</td>
                                                    <td id="insp_note">
                                                    </td>
                                                </tr> 
                                                  <tr>
                                                    <td class="checkbox"><input id="field_data_box" type="checkbox" />Collect Field Data</td>
                                                    <td></td>
                                                </tr> 
                                                
                                                <tr>
                                                    <td class="checkbox"><input id='lab_sample_box' type="checkbox" />Collect Sample for Lab Analysis</td>
                                                    <td></td>
                                                </tr>
                                            </table> 
                                        </li>
    
    
        <li>
                                            <label><?php echo $resx["resource_needs"]; ?></label>
<!--                                          This table is an example - must be generated dynamically.-->
                                            <table>                                  
                                                <tr>
                                                   <td><input id="well_box" type="checkbox">Drillers</td>
                                                    <td style="width: 50px" id='tab7'>
                                                        <select class="coc_list">
                                                            <option value="" selected='selected'>Select</option>
                                                            <option value="Daily" >Joe's Drilling</option>
                                                            <option value="Weekly">Best Drilling</option>
                                                            <option value="Two Weeks" >Deep Geotech</option>
                                                        </select>  
                                                    </td>
                                                </tr>
                                                
                                                  <tr>
                                                    <td><input id="waste_box" type="checkbox" />Waste Disposal</td>
                                                    <td style="width: 50px" id='tab8'>
                                                        <select class="coc_list">
                                                            <option value="" selected='selected'>Select</option>
                                                            <option value="Daily" >No Waste Disposal Companies.  Update Resources (left).</option>
                                                        </select>  
                                                    </td>
                                                  </tr>
                                                <tr>
                                                    <td><input id="rental_box" type="checkbox" />Rent Equipment</td>
                                                       <td style="width: 50px" id='tab9'>
                                                           <select class="coc_list">
                                                            <option value="" selected='selected'>Select</option>
                                                            <option value="Daily" > Super Rental</option>
                                                            <option value="Weekly">Enviro-rental</option>
                                                            <option value="Two Weeks" >Rental Option 3</option>
                                                           </select>  </td>
                                                </tr> 
                                                <tr>
                                                    <td><input id="field_test_box" type="checkbox" />Field Test Kits</td>
                                                    <td style="width: 50px" id='tab10'>
                                                        <select class="coc_list">
                                                            <option value="" selected='selected'>Select</option>
                                                            <option value="Daily" >HACH</option>
                                                            <option value="Weekly">LaMotte</option>
                                                            <option value="Two Weeks" >Chemetrics</option>
                                                        </select>  </td>
                                                </tr>   
                                                  <tr>
                                                    <td><input id="field_test_box" type="checkbox" />Other</td>
                                                    <td style="width: 50px" id='tab10'>
                                                    </td>
                                                </tr>   
                                            </table> 
                                        </li>
  </ol>
</fieldset>
    
</div>
 