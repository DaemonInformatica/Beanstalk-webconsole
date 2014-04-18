Beanstalk-webconsole
====================

Part of a network of programs to bruteforce MD5 hashes using beanstalkd. This is the webconsole

Why did I write this: 
One of my favorite branches of computer science is parallel computing. The art of dividing CPU-resource intensive projects
over multiple / many computers and gathering the results as pieces of the puzzle to put back together. Somebody introduced me
to the simple beanstalk-queue and I figured 'Lets think of an excuse to implement something around this. 

Thus the MD5 bruteforce hash-cracker was born. 

To use this, you need to download 3 projects in total: 
- BeanstalkManagerMD5 
- BeanstalkWorkerDecodeMD5 
- BeanstalkWorkerResult
- webservice database installation and webconsole.  (This project) 


What does the webconsole do? 
This project contains several things: 
1) The installation of the database tables the manager and result processor need to keep track of the progress. 
2) A user interface to add and manager the MD5's. Each user can add new MD5 hashes to process. 
3) An admin interface to manage users and processes. 


Howto install: 
- Drop the project in a webserver's document root. 
- open the file 'incl/const.php' and edit it so it contains correct settings for the database. 
- in a browser open the setup.php file in the root of the project. 
- if all goes well, you get a button 'Make' press it. 
- no errors should occur. 


Howto use: 
- To login as admin: type username 'admin' and password 'mypassword'. (This is configured in the setup.php file). 
- to register as a new user: press register, enter information and hope it works. 

Once logged in: 
The menu contains the option 'My MD5'. To add a new hash: 
- Copy / paste the hash into the textfield and press 'Add'. 

The manager will now automatically pickup the new hash as a hash to crack. 

Existing hashes will have the option to show details. The details screen currently shows the hash, the decoded value if 
one is available, date and time created and the time spent on the project so far. Also a list of ranges pushed to the 
beanstalk so far and their state / status. 