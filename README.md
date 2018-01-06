# Readme
simple forum to handle 
* subjects, threads and users with different roles
* searchfunction for authorized users
for demonstration see doc/presentation.ogv
## spec
- php7.0
- apache2
- postgresql
- vagrant

## setup
 prerequisites:
- vagrant

edit /etc/hosts<br>
add:
```
192.168.33.10	    local.webb.com
```

cd into root
```
vagrant up
```
### synchronizing
to synch box with source:
```
vagrant rsync-auto
```
