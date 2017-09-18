How to install
==============

# Using SSH
- Enter your server using your SSH client.
- Browse to where you want the project to be deployed (usually in a custom directory : `mkdir your_custom_dir_name`).
- Within your target directory, run the next command : `git clone https://github.com/ErwanGuillou/SnowTricks.git .` (without forgetting the dot). 
- Once it's done, let's install the whole dependencies running the next command : `composer update`. On a remote server, make sure the allowed memory is at least 1.5G. Otherwise, you'll need to increase it by editing your PHP ini configuration file.
- You'll be asked to reply to some question :

  * database_host : *127.0.0.1|yours*
  * database_port : *null|yours*
  * database_name : *symfony|yours*
  * database_user : *root|yours*
  * database_password : *null|yours*
  * database_path : *'%kernel.project_dir%/var/data/data.sqlite'|yours*
  * mailer_transport : *smtp|yours*
  * mailer_host : *127.0.0.1|yours*
  * mailer_user : *null|yours*
  * mailer_password : *null|yours*
  * secret : *random key* (e.g.:AZERTYTOTO56)

- Great ! To finish with, run the next command : `php bin/console snowtricks:start`
- All is done ! Now, just browse up to your application.

# Locally
To install this website locally, follow these quick steps :
- Download the project into any directory on your computer.
- Using CLI, browse to the directory that contains the project.
- Run the following : `composer update` and define your own values when asked.
- Then run : `php bin/console snowtricks:start`
- Now you're ready ! Just start the embeded webserver by running : `php bin/console server:start localhost:8000`
- and browse to http://localhost:8000

That's all !
