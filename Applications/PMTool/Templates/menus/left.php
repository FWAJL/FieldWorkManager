<section class="left-aside">
  <p class="aside-text"><?php echo $resx_menu_left["p_user_name_label"]; ?><?php echo $pm['username']; ?><br/>
    <a href="<?php echo $logout_url; ?>" role="button"><?php echo $resx_menu_left["logout_link_text"]; ?></a>
  </p> 
  <figure class="aside-bg"></figure>
  <section class="left-asidebg">
    <div class="content_left">
      <!-- CONTENT -->
      <div class="project_managers">			
        <div class="arrowlistmenu"> 
          <!------------------------------------------start Project--------------------------------------------------------->
          <p headerindex="0h" class="menuheader expandable arrow ">
            <span class="accordprefix"></span>
            <?php echo $resx_menu_left["header_projects"]; ?>
            <span class="accordsuffix"></span></p>
          <ul style="display: none;" contentindex="0c" class="categoryitems">
            <li><a href="/projects/view/1"><?php echo $resx_menu_left["item_projectX"]; ?></a></li> 
            <li><a href="/projects/add" class="active"><?php echo $resx_menu_left["projects_add"]; ?></a></li>
            <!--<li><a href="/projects/manage"><?php echo $resx_menu_left["projects_manage"]; ?></a></li>-->
          </ul>    
          <!------------------------------------------End -Project--------------------------------------------------------->
          <div class="line">&nbsp;</div>     
<!--          ----------------------------------------start Tasks-------------------------------------------------------
          <p headerindex="1h" class="menuheader expandable arrow "><span class="accordprefix"></span>Tasks<span class="accordsuffix"></span></p>
          <ul style="display: none;" contentindex="1c" class="categoryitems">
            <li>
              <a href="add-new-tasks.html" class="plus">Add New Task</a></li>
            <li><a href="show-hide-tasks.html">Show/Hide Tasks</a></li>
          </ul>    
          ----------------------------------------end -Tasks-------------------------------------------------------						
          <div class="line">&nbsp;</div>                -->
          <!------------------------------------------start Locationss--------------------------------------------------------->
          <p headerindex="2h" class="menuheader expandable arrow openheader">
            <span class="accordprefix"></span>
            <?php echo $resx_menu_left["header_locations"]; ?>
            <span class="accordsuffix"></span></p>
          <ul style="display: block;" contentindex="2c" class="categoryitems">
            <li><a href="/locations"><?php echo $resx_menu_left["item_locX"]; ?></a></li> 
            <li><a href="/locations" class="plus"><?php echo $resx_menu_left["locations_add"]; ?></a></li>
            <!--<li><a href="/locations" class="plus"><?php echo $resx_menu_left["locations_boundary_add"]; ?></a></li>-->
            <!--<li><a href="/locations" class="plus"><?php echo $resx_menu_left["locations_upload_list"]; ?></a></li>-->
            <!--<li><a href="/locations"><?php echo $resx_menu_left["locations_manage"]; ?></a></li>-->
            <!--<li><a href="/locations"><?php echo $resx_menu_left["boundaries_manage"]; ?></a></li>-->
          </ul> 
<!--          ----------------------------------------start Groups-------------------------------------------------------				   		
          <p headerindex="3h" class="menuheader expandable arrow "><span class="accordprefix"></span>&nbsp;&nbsp;Location Groups<span class="accordsuffix"></span></p>
          <ul contentindex="3c" class="categoryitems">
          </ul>               
          <p headerindex="4h" class="menuheader expandable arrow "><span class="accordprefix"></span>&nbsp;&nbsp;Boundary Groups<span class="accordsuffix"></span></p>
          <ul contentindex="4c" class="categoryitems">
            <li><a href="group.html">group</a></li> 
            <li><a href="yu.html">yu</a></li> 
            <li><a href="f.html">f</a></li> 
            <li><a href="jkll.html">jkll</a></li> 
          </ul> 
          ----------------------------------------end -Locationss-------------------------------------------------------						
          <div class="line">&nbsp;</div>
          ----------------------------------------start Analytes-------------------------------------------------------
          <p headerindex="5h" class="menuheader expandable arrow "><span class="accordprefix"></span>Analytes<span class="accordsuffix"></span></p>
          <ul contentindex="5c" class="categoryitems">
            <li><a href="#"></a></li> 
            <li><a href="#"></a></li> 
            <li><a href="add-new-analytes.html" class="plus">Add New Analyte(s)</a></li>
            <li><a href="upload-analyte-list.html" class="plus">Upload Analyte List</a></li>
            <li><a href="show-hide-analyte.html">Show/Hide Analytes</a></li>
          </ul> 
          ----------------------------------------start Groups-------------------------------------------------------				   		
          <p headerindex="6h" class="menuheader expandable arrow "><span class="accordprefix"></span>&nbsp;&nbsp;Analyte Groups<span class="accordsuffix"></span></p>
          <ul contentindex="6c" class="categoryitems">
          </ul>               
          ----------------------------------------end -Analytes-------------------------------------------------------
          <div class="line">&nbsp;</div>						
          <p class="menuheader arrow"><span class="accordprefix"></span>Results</p>-->
        </div>
      </div>		
    </div><br clear="all"/>
  </section>
</section>