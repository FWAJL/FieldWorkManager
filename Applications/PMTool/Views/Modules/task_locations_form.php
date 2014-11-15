<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>

<div id="task_locations"  class="data-form">
<fieldset class="task_locations_form">
  <ol class="add-new-item">
       <li style="display: none;">
      <input name="task_id" type="text" />
    </li>
    <li style="display: none;">
      <input name="location_id" type="text" />
    </li>
 <table>
     <tr>
         <td style="width: 300px"><?php echo $resx["selected_task_locations"]; ?></td> 
         <td style="width: 300px"><?php echo $resx["not_selected_task_locations"]; ?></td>  
     </tr>
     
       <tr>
           <td style="width: 300px">
            <input name="#" type="textarea" />   
           </td> 
         <td style="width: 300px">
         <input name="#" type="textarea" />
         </td>   
     </tr>
            <tr>
           <td style="width: 300px">
            <input name="#" type="button" value="Click to use Map" />   
           </td> 
         <td style="width: 300px">
        
         </td>   
     </tr>
 </table>
  </ol>
</fieldset>
    
</div>
 