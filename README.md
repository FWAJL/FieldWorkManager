### Welcome to FieldWorkManager.
The project will use basic web forms to collect project information, interactive forms for field personnel to collect data, and mapping tools to identify and organize the work locations.

There will be three levels of users:
- PM or Project managers who can:
 - use all functionalities of the tool within their assigned projects.
- Field worker:
 - view map to find locations
 - fill forms
 - mark a location by giving a label and button click
- Software developer:
 - Test Stage site to validate software updates or debug on real data (read only rights)

### Installation to local machine
PREREQUISITES:
- PHP 5.3+ server
- MySQL 5+

Tools recommended:
- NetBeans 7.3+
- MySQL Workbench 6.0+
- Git

Then, follow the steps below:
- Retrieve the repository doing "git clone https://github.com/FWAJL/FieldWorkAssistantMVC.git"
- With MySQL Workbench, run the script https://github.com/FWAJL/FieldWorkAssistantMVC/blob/master/Installation/baiken_fwm_1_db_script_for_developer.sql
- Add a user like shown on the images in "/Installation" folder. You will find the database details to use here: https://github.com/FWAJL/FieldWorkAssistantMVC/blob/master/Applications/PMTool/Config/appsettings.xml

### Pushing update to repository
Always push the updates on the Development branch and notify me (j.litzler@fieldworkassistant.net) when you do.
Provide the details of the updates and a detailed test plan.
Helpers to push updates:

### Git helpers

Before doing any work, make sure to do:
- git pull

To push updates, use the following in order:

- git status
- git add -A 
- git status 
- git commit -m "some comment" (be as explicit as possible)
- git push (login required) 
- git tag v[Major].[Minor].[MainTask].[SubTask] 
- git push --tags (login required)

### Having issues, questions?
Report here: https://github.com/FWAJL/FieldWorkAssistant/issues
