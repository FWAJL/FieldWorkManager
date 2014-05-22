### Welcome to FieldWorkAssistant.
The site will use basic web forms to collect project information, interactive forms for field personnel to collect data, and mapping tools to identify and organize the work locations.

There will be three levels of users:
- PM or Project managers who can:
 - use all functionalities of the tool within their assigned projects.
- Field worker:
 - view map to find locations
 - fill forms
 - mark a location by giving a label and button click
- Software developer:
 - Test Stage site to validate software updates or debug on real data (read only rights)


### Pushing update to repository
Always push the updates on the DEV branch and notify me (j.litzler@fieldworkassistant.net) when you do.
Provide the details of the updates and a detailed test plan.
Helpers to push updates:

### Git helpers

To push updates, use the following in order:

- git status
- git add -A 
- git status 
- git commit -m "some comment" 
- git push (login required) 
- git tag v[Major].[Minor].[Revision].[Build] 
- git push --tags (login required)

### Having issues, questions?
Report here: https://github.com/FWAJL/FieldWorkAssistant/issues
