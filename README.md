*Quickee* is a single page website that lists posts from a directory of markdown-enabled files. It serves as a way to work on posts locally and manage them with git.

Some of the permissions will need to be modifed:

*chown -R ssh_user:www repository/*

*chmod -R g+s repository/*

* created a new .ssh folder in /var/www/vhosts/gaidi.ca and chowned it
* copied the keys from the root home folder to this new one
* shell_exec from php now uses this folder for the keys