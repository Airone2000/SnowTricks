How to install
==============

# Using SSH
- Enter your server using your SSH client.
- Browse to where you want the project to be deployed (usually in a custom directory : `mkdir your_custom_dir_name`).
- Within your target directory, run the next command : `git clone https://github.com/ErwanGuillou/SnowTricks.git .` (without forgetting the dot). 
- Once it's done, let's install the whole dependencies running the next command : `composer update`. On a remote server, make sure the allowed memory is at least 1.5G. Otherwise, you'll need to increase it by editing your PHP ini configuration file.
- You'll be asked to reply to some question :

  * database_host : *127.0.0.1*
  * database_port : *null*
  * database_name : *symfony*
  * database_user : *root*
  * database_password : *null*
  * database_path : *'%kernel.project_dir%/var/data/data.sqlite'*
  * mailer_transport : *smtp*
  * mailer_host : *127.0.0.1*
  * mailer_user : *null*
  * mailer_password : *null*
  * secret : *random key* (e.g.:AZERTYTOTO56)

As you may have noticed, we keep the default values for the mere reason that we're using à SQLite database. If you want, you can override this.

- Great ! To finish with, run the next command : `php bin/console snowtricks:start`
- All is done ! Now, just browse up to your application.
