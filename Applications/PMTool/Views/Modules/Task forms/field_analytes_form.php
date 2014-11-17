<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="field_analytes_info"  class="data-form">
<fieldset class="field_analytes_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="field_analyte_name_unit" type="text" />
    </li>
    <li style="display: none;">
      <input name="field_analyte_id" type="text" />
    </li>
  <div id="tabs2">
   <h2>Inspection Forms</h2>
   <fieldset class="tech_form">
                                        <table  style="line-height:40px" width="50%">
                                            <tr>
                                                <td>
                                                    <textarea style="width: 800px" name="multi_analytes"rows="7"></textarea>
                                                </td>
                                            </tr>
                                           </table> 
       <input type="submit" name="submit" value="Click here to see survey" />
       <input type="submit" name="submit" value="Click here to select from survey library" /><br />
                                        
                                    
                                        Enter one or more questions in the box above.  To separate questions, use &quot;Return&quot; after each question.  Use a question mark or period at end each question.  Only one sentence per question.  To add answer options separate by "or" as below.  
                                        A sentence without options gives a text box.  If you want multiple possible answers, include "(check all that apply)". If one of the options is "other" it will include a text box.  Reminder: Date, time, technician and location are recorded automatically.</p>
                                        <p>Examples:</p>
                                    Do you like chocolate?  Yes or No<br />
                                    Describe any damage you see.<br />
                                    How many TVs do you have? 1 or 2 or more than 2<br />
                                    Why do you eat fish (check all that apply). I like it or It's healthy or My doctor suggested it or Habit or other. <br />
                                    <p>Will look like this:</p>
                                    Do You Like Chocolate?<br />
                                    <input type="radio" />Yes<br />
                                    <input type="radio" />No<br /><br />
                                    
                                    Describe any damage you see.
                                    <input type="text" />
                                    <br /><br />
                                    
                                    How many TVs do you have?<br />
                                    <input type="radio" />1<br />
                                    <input type="radio" />2<br />
                                    <input type="radio" />more than 2<br /><br />
                                    
                                    Why do you eat fish (check all that apply). <br />
                                    <input type="checkbox" />I like it<br />
                                    <input type="checkbox" />It's healthy<br />
                                    <input type="checkbox" />My doctor suggested it<br />
                                    <input type="checkbox" />Habit<br />
                                    Other <input type="text" /><br />
                                    
                                </fieldset>  
  </div>  
  </ol>
</fieldset>
    
</div>
 