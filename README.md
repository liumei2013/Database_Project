# The project for COMP 354(Database)

# A Simple database for a Web Career Portal 

## Project Description

Your client is a service provider company, they would like you and your team to design and implement an online Web Career Portal for them to facilitate their day to day tasks and provide a better service for their clients. The online Job Portal website enable recruiters to post new job vacancies for any specialization for the hiring process. Also, it enable job seekers to search and apply for any number of jobs. You must create a dashboard that provide the ability to have access for all activities related to the recruiters and job seekers.

The requirements data to be modeled are as follows:

### Employer side dashboard:
- Should have an admin login and a mechanism to verify credentials or forgot password.
- Should be able to maintain new users and update the user table.
- Should be able to post jobs.
- Should be able to maintain current jobs and categories.
- Should be able to maintain status of applied jobs.
- Should be able to see a summary of current application at a glance.
- Two user categories should be provided:
o Prime: Employer can post up to five jobs. A monthly charge of $50 will be applied.
o Gold: Employer can post as many jobs as he/she wants. A monthly charge of $100 will be applied.
- Users have the ability to upgrade or downgrade their category. Charges should be updated based on the new category.
- When users upgrade their category, it should update the user category status.
- Employer dashboard should have a contact us section to help user with contact information/helpline.

### User side dashboard:
- Should be able to sign up as a user.
- Three user categories should be provided:
o Basic: Employee can only view jobs but cannot apply. No charge
o Prime: Employee can view jobs as well as apply for up to five jobs. A monthly charge of $10 will be applied.
o Gold: Employee can view and apply to as many jobs as he/she wants. A monthly charge of $20 will be applied.
- Should be able to choose categories.
- Users have the ability to upgrade or downgrade their category. Charges should be updated based on the new category.
- When users upgrade their category, it should update the user category status.
- Should have a user login and a mechanism to verify credentials or forgot password.
- Should be able to search for jobs.
- Should be able to search by job category.
- Should be able to apply for jobs.
- Should be able to maintain status of applied jobs.
- Should be able to delete user profile.
- Should be able to update user profile details.
- Should be able to withdraw from an applied job.

### Administration side dashboard:
- Should have an admin login and a mechanism to verify credentials or forgot password.
- Should be able to activate or deactivate a user.
- Should be able to see all activities in the system.

All Users must have the ability to provide a method of payment to their account and provide the needed information to be charged. Method of payments could be either credit card charge or authorization from an existing checking account. In each case the proper information for withdrawal must be supplied by the user. The user have the option to choose between an automatic withdrawal or manual withdrawal. An automatic withdrawal is done automatically by the system at the beginning of each month by applying a charge to the user from a preselected method of payment. Users can have many method of payments associated with their accounts and should be able to add/remove or edit their methods of payments. An account that is negative will be frozen until it is settled and a payment is made. The user of a frozen account will be able to login to the system but will not be able to access the services provided by the dashboard until the user account is settled. When a charge is applied to an account, an automatic email is sent to the user informing of the charge and account status. A suffering account will receive a warning message once a week until the account is settled or deactivated. A suffering account for a year will be deactivated automatically by the system.

All users must have access to different reports that will enable them to obtain any needed information whether related to their account, posted jobs, applied jobs, accepted jobs, history, etc.

These are the minimum requirements for your application. More details could be added through more research and investigations from your part. With this information, do the following initial steps in your database design process:

1. Develop an E/R diagram to represent the conceptual database scheme for the above "application".
2. In the diagram, mark the various constraints (keys, functional dependencies, cardinalities of the relationships, etc.) Identify any constraints that are not captured by the E/R diagram.
3. Convert your E/R diagram into a relational database schema. Make refinements to the DB schema if necessary. Identify various integrity constraints such as primary keys, foreign keys, functional dependencies, and referential constraints. Make sure that your database schema is at least in 3NF.

Formulate and evaluate the following SQL queries against an instance of your database in which every relation is populated with 'sufficient'" representative tuples.

i. Create/Delete/Edit/Display an Employer.

ii. Create/Delete/Edit/Display a category by an Employer.

iii. Post a new job by an employer.

iv. Provide a job offer for an employee by an employer.

v. Report of a posted job by an employer (Job title and description, date posted, list of employees applied to the job and status of each application).

vi. Report of posted jobs by an employer during a specific period of time (Job title, date posted, short description of the job up to 50 characters, number of needed employees to the post, number of applied jobs to the post, number of accepted offers).

vii. Create/Delete/Edit/Display an Employee.

viii. Search for a job by an employee.

ix. Apply for a job by an employee.

x. Accept/Deny a job offer by an employee.

xi. Withdraw from an applied job by an employee.

xii. Delete a profile by an employee.

xiii. Report of applied jobs by an employee during a specific period of time (Job title, date applied, short description of the job up to 50 characters, status of the application).

xiv. Add/Delete/Edit a method of payment by a user.

xv. Add/Delete/Edit an automatic payment by a user.

xvi. Make a manual payment by a user.

xvii. Report of all users by the administrator for employers or employees (Name, email, category, status, balance.

xviii. Report of all outstanding balance accounts (User name, email, balance, since when the account is suffering).
