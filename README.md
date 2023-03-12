# task_back
Php non framework CRUD backend
## Start
1) Find test ```test.sql``` script in ```db``` directory and run it on ypur mysql server
2) Change database variables in ```/config/Database.php ``` as needed
3) Start php server by running command ```php -S localhost:8000``` in the main app directory
## Credentials
admin - ```admin@admin.admin``` and ```aminpassword```  
user - ```andrew.rennit@gmail.com``` and ```123456```
## Additional info
This in a non framework php CRUD api. 
Implemented: 
* Task, Company, Person and Collaborators models
* CRUD operations for each model in database
* Admin login by hardcode credentials
* User login by email and pass from database
Not implemented:
* Necessary data validation
* Overall errors catching
* Admin and user controllers access check
* Tests
