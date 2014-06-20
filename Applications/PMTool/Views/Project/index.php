<?php if (!defined('__EXECUTION_ACCESS_RESTRICTION__')) exit('No direct script access allowed'); ?>
<section class="left-aside">
  <p class="aside-text">User Name: Brian Aiken<br/>
    <a href="<?php echo $logout_url; ?>" class="btn btn-primary btn-lg" role="button"><?php echo $resx["logout_link_text"]; ?></a>
  </p> 
  <figure class="aside-bg"></figure>
  <section class="left-asidebg">
    <div class="content_left">





      <!-- CONTENT -->

      <div class="project_managers">			


        <div class="arrowlistmenu"> 



          <!------------------------------------------start Project--------------------------------------------------------->


          <p headerindex="0h" class="menuheader expandable arrow "><span class="accordprefix"></span>Projects<span class="accordsuffix"></span></p>


          <ul style="display: none;" contentindex="0c" class="categoryitems">

            <li>
              <a href="tempe-doggie-dispensers.html">Tempe Doggie Dispensers</a></li> 

            <li>
              <a href="add-new-project.html" class="active">Add New Project</a></li>

            <li><a href="show-hide-projec.html">Show/Hide Projects</a></li>



          </ul>    

          <!------------------------------------------End -Project--------------------------------------------------------->

          <div class="line">&nbsp;</div>     

          <!------------------------------------------start Tasks--------------------------------------------------------->
          <p headerindex="1h" class="menuheader expandable arrow "><span class="accordprefix"></span>Tasks<span class="accordsuffix"></span></p>
          <ul style="display: none;" contentindex="1c" class="categoryitems">


            <li>
              <a href="add-new-tasks.html" class="plus">Add New Task</a></li>

            <li><a href="show-hide-tasks.html">Show/Hide Tasks</a></li>

          </ul>    


          <!------------------------------------------end -Tasks--------------------------------------------------------->						

          <div class="line">&nbsp;</div>                
          <!------------------------------------------start Locationss--------------------------------------------------------->

          <p headerindex="2h" class="menuheader expandable arrow openheader"><span class="accordprefix"></span>Locations<span class="accordsuffix"></span></p>
          <ul style="display: block;" contentindex="2c" class="categoryitems">

            <li>
              <a href="kerala.html">kerala</a></li> 

            <li><a href="add-new-location.html" class="plus">Add New Location</a></li>
            <li><a href="add-boundary.html" class="plus">Add Boundary</a></li>

            <li><a href="upload-location-list.html" class="plus">Upload Location List</a></li>

            <li><a href="show-hide-location.html">Show/Hide Locations</a></li>
            <li><a href="show-hide-boundaries.html">Show/Hide Boundaries</a></li>

          </ul> 

          <!------------------------------------------start Groups--------------------------------------------------------->				   		
          <p headerindex="3h" class="menuheader expandable arrow "><span class="accordprefix"></span>&nbsp;&nbsp;Location Groups<span class="accordsuffix"></span></p>
          <ul contentindex="3c" class="categoryitems">


          </ul>               
          <p headerindex="4h" class="menuheader expandable arrow "><span class="accordprefix"></span>&nbsp;&nbsp;Boundary Groups<span class="accordsuffix"></span></p>
          <ul contentindex="4c" class="categoryitems">

            <li>
              <a href="group.html">group</a></li> 
            <li>
              <a href="yu.html">yu</a></li> 
            <li>
              <a href="f.html">f</a></li> 
            <li>
              <a href="jkll.html">jkll</a></li> 

          </ul> 

          <!------------------------------------------end -Locationss--------------------------------------------------------->						

          <div class="line">&nbsp;</div>

          <!------------------------------------------start Analytes--------------------------------------------------------->

          <p headerindex="5h" class="menuheader expandable arrow "><span class="accordprefix"></span>Analytes<span class="accordsuffix"></span></p>
          <ul contentindex="5c" class="categoryitems">

            <li>
              <a href="#"></a></li> 
            <li>
              <a href="#"></a></li> 

            <li><a href="add-new-analytes.html" class="plus">Add New Analyte(s)</a></li>

            <li><a href="upload-analyte-list.html" class="plus">Upload Analyte List</a></li>



            <li><a href="show-hide-analyte.html">Show/Hide Analytes</a></li>

          </ul> 

          <!------------------------------------------start Groups--------------------------------------------------------->				   		
          <p headerindex="6h" class="menuheader expandable arrow "><span class="accordprefix"></span>&nbsp;&nbsp;Analyte Groups<span class="accordsuffix"></span></p>
          <ul contentindex="6c" class="categoryitems">

          </ul>               
          <!------------------------------------------end -Analytes--------------------------------------------------------->
          <div class="line">&nbsp;</div>						
          <p class="menuheader arrow"><span class="accordprefix"></span>Results</p>
        </div>
      </div>		
    </div><br clear="all"/>
  </section>
</section>
<section class="right-aside"><h2>Add new project</h2>

  <p>&nbsp;</p>
  <table border="0" align="center" cellpadding="0" cellspacing="0" class="add-new-p">
    <tr>
      <td align="left" valign="top" style="padding:3px">
        <p><span>Project Name:</span></p></td>
      <td width="59%" align="left" valign="top"><input type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Project Description:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>

    <tr>
      <td align="left" valign="top">&nbsp;</td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td colspan="2" align="left" valign="top"><p><span><strong>Company and Faciility Information below.</strong></span></p></td>
    </tr>
    <tr>
      <td align="left" valign="top">&nbsp;</td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility Name:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility Address:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility Latitude:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility Longitude:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility - Contact Name:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility - Phone (Mobile):</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility Email:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility ID Number:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility - Sector:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Facility - SIC Code:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Company Name:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Company - Contanct Name:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Company - Phone (Mobile):</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Company Email:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Company Address:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top" style="padding:3px"><p><span>Company ID Number:</span></p></td>
      <td align="left" valign="top"><input name="" type="text" size="15"></td>
    </tr>
    <tr>
      <td align="left" valign="top">&nbsp;</td>
      <td align="left" valign="top">&nbsp;</td>
    </tr>


    <td width="41%" align="left" valign="top">&nbsp;</td>
    <td align="left" valign="top"><input type="submit" name="button" id="button" value="Add project"></td>
    </tr>
  </table>
</section>	
