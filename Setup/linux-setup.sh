# @Author: Eka Syahwan
# @Date:   2018-07-02 17:29:30
# @Last Modified by:   Eka Syahwan
# @Last Modified time: 2018-08-12 22:27:58
echo "  _________                  .___.__      ___.  Sendinbox Setup "
echo " /   _____/ ____   ____    __| _/|__| ____\_ |__   _______  ___ "
echo " \_____  \_/ __ \ /    \  / __ | |  |/    \| __ \ /  _ \  \/  / "
echo " /        \  ___/|   |  \/ /_/ | |  |   |  \ \_\ (  <_> >    <  "
echo "/_______  /\___  >___|  /\____ | |__|___|  /___  /\____/__/\_ \ "
echo "        \/     \/     \/      \/         \/    \/            \/ "
echo ""
echo "==[ OS INFO ]=="
lsb_release -a
echo ""
echo "===[NUM] [ OS NAME ]==="
echo ""
echo "[+][ 1 ] Linux Ubuntu"
echo "[+][ 2 ] Linux Centos"
echo ""
printf "[+] Enter Number : ${LIGHTCYAN}"
read em

if [ $em == 1 ]
then
    sudo apt-get purge 'php5*' -y
    sudo add-apt-repository ppa:ondrej/php -y
    sudo apt-get update -y
    sudo apt-get install php7.1 php7.1-common -y
    sudo apt-get install php7.1-curl php7.1-xml php7.1-zip php7.1-gd php7.1-mysql php7.1-mbstring -y
fi

if [ $em == 2 ]
then
    yum -y remove php*
    yum install http://rpms.remirepo.net/enterprise/remi-release-7.rpm
    yum update
    yum install yum-utils
    yum install php php-mcrypt php-cli php-gd php-curl php-mysql php-ldap php-zip php-fileinfo
fi
echo ""
echo ""
echo "You can read https://github.com/radenvodka/Sendinbox-/issues/34 for verification sendinbox"