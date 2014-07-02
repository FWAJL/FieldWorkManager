<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="right-aside">
  <h2></h2>

  <section class="form_sections">
    <fieldset class="project_form">
      <legend><?php echo $resx["project_legend"]; ?></legend>
      <ol class="add-new-p">
        <li>
          <label><?php echo $resx["project_name"]; ?></label>
          <input name="project_name" type="text" />
        </li>
        <li>
          <label><?php echo $resx["project_num"]; ?></label>
          <input name="project_num" type="text" />
        </li>
        <li>
          <label><?php echo $resx["project_desc"]; ?></label>
          <input name="project_desc" type="text" />
        </li>
        <li>
          <label><?php echo $resx["project_active_flag"]; ?></label>
          <input name="project_active_flag" type="checkbox" />
        </li>
        <li>
          <label><?php echo $resx["project_visible_flag"]; ?></label>
          <input name="project_visible_flag" type="checkbox"/>
        </li>
      </ol>
    </fieldset>
    <input type="button" id="btn_add_project" value="<?php echo $resx["project_button_add"]; ?>" />
    <input type="button" id="btn_edit_project" value="<?php echo $resx["project_button_edit"]; ?>" />
    <fieldset class="facility_form">
      <legend><?php echo $resx["facility_legend"]; ?></legend>
      <ol class="add-new-p">
        <li>
          <label><?php echo $resx["facility_name"]; ?></label>
          <input name="facility_name" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_address"]; ?></label>
          <textarea name="facility_address" type="text"></textarea>
        </li>
        <li>
          <label><?php echo $resx["facility_lat"]; ?></label>
          <input name="facility_lat" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_long"]; ?></label>
          <input name="facility_long" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_contact_name"]; ?></label>
          <input name="facility_contact_name" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_contact_phone"]; ?></label>
          <input name="facility_contact_phone" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_contact_email"]; ?></label>
          <input name="facility_contact_email" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_id_number"]; ?></label>
          <input name="facility_id_number" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_sector"]; ?></label>
          <input name="facility_sector" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_sic_code"]; ?></label>
          <input name="facility_sic_code" type="text" />
        </li>
        <li>
          <label><?php echo $resx["facility_boundary"]; ?></label>
          <input name="facility_boundary" type="text" />
        </li>
      </ol>
    </fieldset>
    <fieldset class="company_form">
      <legend><?php echo $resx["company_legend"]; ?></legend>
      <ol class="add-new-p">
        <li>
          <label><?php echo $resx["company_name"]; ?></label>
          <input name="company_name" type="text" />
        </li>
        <li>
          <label><?php echo $resx["company_contact_name"]; ?></label>
          <input name="company_contact_name" type="text" />
        </li>
        <li>
          <label><?php echo $resx["company_contact_phone"]; ?></label>
          <input name="company_contact_phone" type="text" />
        </li>
        <li>
          <label><?php echo $resx["company_contact_email"]; ?></label>
          <input name="company_contact_email" type="text" />
        </li>
        <li>
          <label><?php echo $resx["company_id_number"]; ?></label>
          <input name="company_id_number" type="text" />
        </li>
      </ol>
    </fieldset>
  </section>
</section>	
</div><!-- END CONTENT CONTAINER -->