<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="lab_analytes_info"  class="data-form">
<fieldset class="lab_analytes_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="lab_analyte_name" type="text" />
    </li>
    <li style="display: none;">
      <input name="lab_analyte_id" type="text" />
    </li>
  <div id="tabs2">
   <div id="tabs5">
   <h2>Lab Analytes</h2>
       <fieldset class="tech_form">
                                        <table  style="line-height:40px" width="50%">
                                            <tr>
                                                <td>
                                                    <textarea name="multi_analytes" cols="100" rows="7"></textarea></td>
                                            </tr>
                                            <tr>
                                                <td><input type="submit" name="submit"  id="submit" value="Click here to upload analyte list" /></td>
                                            </tr>
                                        </table
                                    <span>
                                        <p>Enter one or more analytes in the box above.</p>
                                        <p>To separate analytes, use &quot;Return&quot; after each analyte.</p>
                                        <p>Like this:</p></span>
                                    VOCs<br />
                                    BTEX<br />
                                    Conductivity
                                </fieldset>  
  </div> 
  </div>
 