#!/usr/bin/env bash
#
#
# author Mikael Holmbom
#

cat <<EOF >> .profile
export LC_CTYPE=en_US.UTF-8
export LC_ALL=en_US.UTF-8

alias db="sh /vagrant/setup/database.sh"
alias klaatu="php /vagrant/tools/klaatu/klaatu.php"
EOF
