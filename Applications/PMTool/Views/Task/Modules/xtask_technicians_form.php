<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_technicians"  class="data-form">
<fieldset class="task_technicians_form">
  <ol class="add-new-item">
    <li style="display: none;">
      <input name="task_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="technician_id" type="text" />
    </li>
        <li style="display: none;">
      <input name="lead_tech" type="text" />
    </li>
 <table>
     <tr>
         <td style="width: 300px"><?php echo $resx["selected_task_technicians"]; ?></td> 
         <td style="width: 300px"><?php echo $resx["not_selected_task_technicians"]; ?></td>   
     </tr>
     
       <tr>
           <td style="width: 300px">
            <input name="#" type="textarea" />   
           </td> 
         <td style="width: 300px">
         <input name="#" type="textarea" />
         </td>   
     </tr>
     
 </table>
  </ol>
</fieldset>
    
</div>
 