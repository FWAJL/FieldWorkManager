--------------------------------
### Welcome to FieldWorkManager.
--------------------------------
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

---------------------------------
### Installation to local machine
---------------------------------
PREREQUISITES:
- PHP 5.3+ server
- MySQL 5+

Tools recommended:
- NetBeans 7.3+
- MySQL Workbench 6.0+
- Git

Then, follow the steps below:
- Retrieve the repository doing "git clone https://github.com/FWAJL/FieldWorkAssistantMVC.git"
- With MySQL Workbench, run the script "Installation/baiken_fwm_1_db_script_for_developer.sql"
- Add a user via SQL Workbench like shown on the images in "/Installation" folder. 
- Setup your PHP Document Root so the URL to access the website is something: http://localhost/FieldWorkAssistant/login. 

You will find the database details to use here: "/Applications/PMTool/Config/appsettings.xml". Following the three steps means nothing needs to be changed to run the website.
On the login page, enter the credentials test / test to login.

--------------------------------
### Pushing update to repository
--------------------------------
Always push the updates on the Development branch and notify me (j.litzler@fieldworkassistant.net) when you do.
Provide the details of the updates and a detailed test plan.
Helpers to push updates:

### Git helpers
To setup the application, you need to do:
- git clone https://github.com/FWAJL/FieldWorkAssistantMVC.git
- cd FieldWorkAssistantMVC
- git checkout Development (select the Development branch to work on)
- git branch

On the last command, you should see:

Development (with a * prefixing)
master

By the following command, you should see that you have the latest code:
- git status

Before doing any work, make sure to do:
- git pull

Sometimes, it'll ask to add a commet after merge and will open the VM Editor in Terminal. To exit it, just do:
- :wq then Enter.

To push updates, use the following in order:

- git status
- git add -A 
- git status 
- git commit -m "some comment" (be as explicit as possible)
- git push (login required) 
- git tag v[Major].[Minor].[MainTask].[SubTask] 
- git push --tags (login required)

Setting your branch to exactly match the remote branch can be done in two steps:

- git fetch origin
- git reset --hard origin/master

If you want to save your current branch's state before doing this (just in case), you can do:

- git commit -a -m "Saving my work, just in case"
- git branch my-saved-work

### Having issues, questions?
Report here: https://github.com/FWAJL/FieldWorkAssistant/issues
