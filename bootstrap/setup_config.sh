#
#
#
# author Mikael Holmbom
#

cat <<EOF >> .profile
export LC_CTYPE=en_US.UTF-8
export LC_ALL=en_US.UTF-8

alias db="sh /vagrant/bootstrap/database.sh"
EOF
