function openWin(location_id, imagename)
{
  myWindow = window.open('uploadfiles/location_images/' + location_id + '/' + imagename, '', 'width=700,height=500,location=0');
  myWindow.focus();
}

function validate_peo()
{
  var flag = true;
  var error = '';
  var nam = document.getElementById('name').value;
  var email = document.getElementById("email").value;
  var filter = /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/;
  var phoneno = /^(?:\(\d{3}\)|\d{3}-)\d{3}-\d{4}$/;
  // var isvalid = emailRegexStr.test(email);
  var cell = document.getElementById("m_phone").value;
  if (nam == '')
  {
    alert("Please enter the name");
    return false;
  }
  if (cell == '' && email == '')
  {
    alert("Warning: either the cell phone number or email is needed for all features to work.");
    return true;
  }
  if (cell)
  {
    if (!cell.match(phoneno))
    {
      alert("Please enter the correct phone number.");
      return false;
    }
  }

  if (email)
  {
    if (!filter.test(email))
    {
      alert('Please enter the correct email address.');
      return false;
    }
  }

  if (flag == false)
  {
    alert(error);
    return flag;
  }
}

/*--------------------------------------------------------------function created by reeta on 16.04.2013----------------*/
function check_addProject()
{
  var countpro = '';
  var conf = '';
  var add_pro = '';
  var empty = '';
  var cid = '';
  var pid = '';
  countpro = document.getElementById('num_pro').value;
  if (countpro == 0)
  {
    // conf=confirm("Is this Company also a Project Name?"); 
    if (conf == false)
    {
      cid = document.getElementById('cid').value;
      window.location.href = 'index.php?add_pro=1&pid=&cid=' + cid + '&empty=1';
      return false;
    }
    if (conf == true)
    {
      cid = document.getElementById('cid').value;
      window.location.href = 'index.php?add_pro=1&pid=&cid=' + cid + '&countpro=1';
      return false;
    }
  }
  if (countpro != 0)
  {
    cid = document.getElementById('cid').value;
    window.location.href = 'index.php?add_pro=1&pid=&cid=' + cid + '&empty=1';
    return false;
  }
}

/*--------------------------------------------------End here.--------------------------------------------------------------------*/
function check_addfacility()
{
  var countfac = '';
  var conf = '';
  var addfac = '';
  var empty = '';
  var cid = '';
  var fid = '';

  countfac = document.getElementById('num_fac').value;
  if (countfac == 0)
  {
    conf = confirm("Is this Company also a Facility?");
    if (conf == false)
    {
      cid = document.getElementById('cid').value;
      window.location.href = 'index.php?add_fac=1&fid=&cid=' + cid + '&empty=1';
      return false;
    }
    if (conf == true)
    {
      cid = document.getElementById('cid').value;
      window.location.href = 'index.php?add_fac=1&fid=&cid=' + cid + '&com_fac=1';
      return false;
    }
  }
  if (countfac != 0)
  {
    cid = document.getElementById('cid').value;
    window.location.href = 'index.php?add_fac=1&fid=&cid=' + cid + '&empty=1';
    return false;
  }
}

function assignment_validation()
{
  var flag = true;
  var error = '';
  var people = document.getElementById('people').value;
  var company = document.getElementById('company').value;
  var facility = document.getElementById('facility').value;
  var permit = document.getElementById('permit').value;

  if (people == '')
  {
    alert("Please select people");
    return false;
  }
  if (company == '')
  {
    alert("Please select company");
    return false;
  }
  if (facility == '')
  {
    alert("Please select facility");
    return false;
  }
  if (permit == '')
  {
    alert("Please select permit");
    return false;
  }

  if (flag == false)
  {
    alert(error);
    return flag;
  }
}

function checkAll() {
  $('input:checkbox.checkall').attr('checked', 'checked');
  return false;
}
function locale_submit(getid)
{
  document.getElementById('text').innerHTML = "<input type='hidden' name='localetypeid' id='localetypeid' value='" + getid + "' />";
  // document.getElementById('localetypeid').value = getid;
  document.localetype.submit();
}
ddaccordion.init({
  headerclass: "expandable", //Shared CSS class name of headers group that are expandable
  contentclass: "categoryitems", //Shared CSS class name of contents group
  revealtype: "click", //Reveal content when user clicks or onmouseover the header? Valid value: "click", "clickgo", or "mouseover"
  mouseoverdelay: 200, //if revealtype="mouseover", set delay in milliseconds before header expands onMouseover
  collapseprev: true, //Collapse previous content (so only one open at any time)? true/false 
  defaultexpanded: [0], //index of content(s) open by default [index1, index2, etc]. [] denotes no content
  onemustopen: false, //Specify whether at least one header should be open always (so never all headers closed)
  animatedefault: true, //Should contents open by default be animated into view?
  persist: true, //persist state of opened contents within browser session?
  toggleclass: ["", "openheader"], //Two CSS classes to be applied to the header when it's collapsed and expanded, respectively ["class1", "class2"]
  togglehtml: ["prefix", "", ""], //Additional HTML added to the header when it's collapsed and expanded, respectively  ["position", "html1", "html2"] (see docs)
  animatespeed: "fast", //speed of animation: integer in milliseconds (ie: 200), or keywords "fast", "normal", or "slow"
  oninit: function(headers, expandedindices) { //custom code to run when headers have initalized
    //do nothing
  },
  onopenclose: function(header, index, state, isuseractivated) { //custom code to run whenever a header is opened or closed
    //do nothing
  }
})