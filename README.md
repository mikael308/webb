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
pre:
- vagrant

edit /etc/hosts, add
```
192.168.33.10	    local.webb.com
```

cd into root
```
vagrant up
```
for synchronizing source files
```
vagrant rsync-auto
```
