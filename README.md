# Jarkom-Modul-2-D01-2022

### Kelompok D01

<table>
    <tr>
        <th>No</th>
        <th>Nama</th>
        <th>NRP</th>
    </tr>
    <tr>
        <td>1</td>
        <td>Ananda Hadi Saputra </td>
        <td>5025201148</td>
    </tr>
    <tr>
        <td>2</td>
        <td>Nethaneel Patricio Linggar</td>
        <td>5025201180</td>
    </tr>
    <tr>
        <td>3</td>
        <td>Yehezkiel Wiradhika</td>
        <td>5025201086</td>
    </tr>
</table>

## Pembukaan Soal

Twilight dengan nama samaran Loid Forger di bawah organisasi WISE menjalankan operasinya di negara Ostania. Berikut adalah petanya:

<img src="./imgs/ostania-map.png" alt="Ostania Map" />

Deskripsi tiap soal:

<ol>
    <li>
    WISE akan menjadi DNS Master, Berlint menjadi DNS Slave, dan Eden akan digunakan sebagai Web Server. Terdapat 2 Client yaitu SSS, dan Garden. Semua node terhubung pada router Ostania, sehingga dapat mengakses internet.
    </li>
    <li>
        Buatlah website utama dengan akses wise.yyy.com dengan alias www.wise.yyy.com pada folder wise
    </li>
    <li>
        Buatlah subdomain eden.wise.yyy.com dengan alias www.eden.wise.yyy.com yang diatur DNS-nya di WISE dan mengarah ke Eden
    </li>
    <li>
        Buat reverse domain untuk domain utama
    </li>
    <li>
        Buatlah Berlint sebagai DNS Slave untuk domain utama
    </li>
    <li>
        Buat subdomain khusus untuk operation, yaitu operation.wise.yyy.com dengan alias www.operation.wise.yyy.com dengan IP menuju ke Eden dalam folder operation.
    </li>
    <li>
        Buatlah subdomain melalui Berlint dengan akses strix.operation.wise.yyy.com dengan alias www.strix.operation.wise.yyy.com yang mengarah ke Eden
    </li>
    <li>
        Lakukan konfigurasi Webserver dengan webserver www.wise.yyy.com. Buat DocumentRoot pada /var/www/wise.yyy.com
    </li>
    <li>
        buatlah agar www.wise.yyy.com/index.php/home menjadi www.wise.yyy.com/home
    </li>
    <li>
        subdomain www.eden.wise.yyy.com butuh pemyimpanan aset yang memiliki DocumentRoot pada /var/www/eden.wise.yyy.com
    </li>
    <li>
        pada folder /public, hanya dapat melakukan directory listing saja.
    </li>
    <li>
        siapkan error file 404.html pada folder /error untuk mengganti error code pada apache
    </li>
    <li>
        buatlah konfigurasi virtual host untuk dapat mengakses file aset www.eden.wise.yyy.com/public/js menjadi www.eden.wise.yyy.com/js
    </li>
    <li>
        buatlah www.strix.operation.wise.yyy.com hanya bisa diakses dengan port 15000 dan port 15500
    </li>
    <li>
        buatlah autentikasi username Twilight dan password opStrix dan file di /var/www/strix.operation.wise.yyy
    </li>
    <li>
        buatlah sehingga setiap kali mengakses IP Eden akan dialihkan secara otomatis ke www.wise.yyy.com
    </li>
    <li>
        ubahlah request gambar yang memiliki substring "eden" akan diarahkan menuju eden.png
    </li>
</ol>

## Jawaban Soal

### Soal 1

Berikut adalah topologinya:
<img src="./imgs/topologi.png" placeholder="topologi" />
kita lalu melakukan konfigurasi pada setiap node yang ada:
<br>
<strong>Ostania sebagai router:</strong>

```
auto eth0
iface th0 inet dhcp

auto eth1
iface eth1 inet static
    address 10.45.1.1
    netmask 255.255.255.0

auto eth2
iface eth2 inet static
    address 10.45.2.1
    netmask 255.255.255.0
```

<strong>SSS sebagai Client</strong>

```
> apt-get update
> apt-get install dnsutils
```

```
auto eth0
iface eth0 inet static
    address 10.45.1.2
    netmask 255.255.255.0
    gateway 10.45.1.1
```

<strong>Garden sebagai Client</strong>

```
auto eth0
iface eth0 inet static
    address 10.45.1.3
    netmask 255.255.255.0
    gateway 10.45.1.1
```

<strong>WISE sebaggai DNS Master</strong>

```
> apt-get update
> apt-get install bind9 -y
```

```
auto eth0
iface eth0 inet static
    address 10.45.2.2
    netmask 255.255.255.0
    gateway 10.45.2.1
```

<strong>Berlint sebagai DNS Slave</strong>

```
> apt-get update
> apt-get install bind9 -y
> echo "nameserver 192.168.122.1" > /etc/resolv.conf
```

```
auto eth0
iface eth0 inet static
    address 10.45.2.3
    netmask 255.255.255.0
    gateway 10.45.2.1
```

<strong>eden Sebagai Webserver</strong>

```
auto eth0
iface eth0 inet static
    address 10.45.2.4
    netmask 255.255.255.0
    gateway 10.45.2.1
```

setiap node kemudian diaktifkan dengan click tombol start. lalu kitamenjalankan command
<code>iptables -t nat -A POSTROUTING -o eth0 -j WISE -s 10.45.0.0/16</code> pada router Ostania sehingga dapat terkoneksi dengan internet.

### Soal 2

<strong>Server Eden</strong>
melakukan konfigurasi terhadap file <code>/etc/bind/named.conf.local </code>
dengan menambahkan

```
zone "wise.t07.com" {
        type master;
        file "/etc/bind/wise/wise.t07.com";
};
```

membuat dir baru, yakni <code>/etc/bind/wise</code>
<br><br>
menambahkan konfigurasi pada <code>/etc/bind/wise/wise.t07.com</code>

```
$TTL    604800
@       IN      SOA     wise.t07.com. root.wise.t07.com. (
                        2021100401      ; Serial
                        604800          ; Refresh
                        86400           ; Retry
                        2419200         ; Expire
                        604800 )        ; Negative Cache TTL
;
@               IN      NS      wise.t07.com.
@               IN      A       10.45.2.2 ; IP EniesLobby
www             IN      CNAME   wise.t07.com.

```

Melakukan restart service bind9 dengan <code>service bind9 restart</code>

### Soal 3

Melakukan Edit pada file <code>/etc/bind/kaizoku/wise.t07.com</code> menjadi seperti berikut:

```
$TTL    604800
@       IN      SOA     wise.t07.com. root.wise.t07.com. (
                        2021100401      ; Serial
                        604800          ; Refresh
                        86400           ; Retry
                        2419200         ; Expire
                        604800 )        ; Negative Cache TTL
;
@               IN      NS      wise.t07.com.
@               IN      A       10.45.2.2 ; IP EniesLobby
www             IN      CNAME   wise.t07.com.
super           IN      A       10.45.2.4 ; IP skype
www.super       IN      CNAME   super.wise.t07.com.
```

Melakukan restart sevice bind9 dengan <code>service bind9 restart</code>

### Soal 4

Edit file <code>/etc/bind/named.conf.local</code> menjadi sebagai berikut:

```
zone "wise.t07.com" {
        type master;
        file "/etc/bind/wise/wise.t07.com";
};

zone "2.45.10.in-addr.arpa" {
        type master;
        file "/etc/bind/wise/2.45.10.in-addr.arpa";
};
```

dan lakukan konfigurasi pada file <code>/etc/bind/wise/2.45.10.in-addr.arpa</code> seperti berikut ini:

```
$TTL    604800
@       IN      SOA     wise.t07.com. root.wise.t07.com. (
                        2021100401      ; Serial
                        604800          ; Refresh
                        86400         ; Retry
                        2419200         ; Expire
                        604800 )       ; Negative Cache TTL
;
2.45.10.in-addr.arpa.   IN      NS      wise.t07.com.
2                       IN      PTR     wise.t07.com.
```

### Soal 5

<strong>Service Berlint</strong>
<br>
lakukan konfigurasi pada file <code>/etc/bind/named.conf.local</code> sebagai berikut untuk melakukan konfigurasi DNS Slave yang mengarah ke berlint:

```
zone "wise.t07.com" {
        type master;
        notify yes;
        also-notify {10.45.2.3;};  //Masukan IP berlint tanpa tanda petik
        allow-transfer {10.45.2.3;}; // Masukan IP berlint tanpa tanda petik
        file "/etc/bind/wise/wise.t07.com";
};

zone "2.45.10.in-addr.arpa" {
        type master;
        file "/etc/bind/wise/2.45.10.in-addr.arpa";
};
```

Melakukan restart sevice bind9 dengan <code>service bind9 restart</code>

### Soal 6

**Server berlint**  
Edit file `/etc/bind/named.conf.options` dan comment `dnssec-validation auto;` dan tambahkan baris berikut pada `/etc/bind/named.conf.options`

```
allow-query{any;};
```

kemudian edit file `/etc/bind/named.conf.local` untuk delegasi `operation.wise.t07.com`

```
zone "wise.t07.com" {
    type slave;
    masters { 10.45.2.2; }; // Masukan IP EniesLobby tanpa tanda petik
    file "/var/lib/bind/wise.t07.com";
};

zone "operation.wise.t07.com"{
        type master;
        file "/etc/bind/sunnygo/operation.wise.t07.com";
};
```

buat sebuah direktori `mkdir /etc/bind/sunnygo` dan Lakukan konfigurasi pada file `/etc/bind/sunnygo/operation.wise.t07.com`

```
$TTL    604800
@       IN      SOA     operation.wise.t07.com. root.operation.wise.t07.com. (
                        2021100401      ; Serial
                        604800         ; Refresh
                        86400         ; Retry
                        2419200         ; Expire
                        604800 )       ; Negative Cache TTL
;
@               IN      NS      operation.wise.t07.com.
@               IN      A       10.45.2.4       ;ip eden
www             IN      CNAME   operation.wise.t07.com.
```

Melakukan restart sevice bind9 dengan `service bind9 restart`

### Soal 7

**Server berlint**  
konfigurasi file `/etc/bind/sunnygo/operation.wise.t07.com` dengan

```
$TTL    604800
@       IN      SOA     operation.wise.t07.com. root.operation.wise.t07.com. (
                        2021100401      ; Serial
                        604800         ; Refresh
                        86400         ; Retry
                        2419200         ; Expire
                        604800 )       ; Negative Cache TTL
;
@               IN      NS      operation.wise.t07.com.
@               IN      A       10.45.2.4       ;ip eden
www             IN      CNAME   operation.wise.t07.com.
general         IN      A       10.45.2.4       ;IP eden
www.general     IN      CNAME   operation.wise.t07.com.
```

Melakukan restart sevice bind9 dengan `service bind9 restart`

### Soal 8

**Client sss**  
Melakukan `apt-get update` dan menginstall lynx dengan cara

```
apt-get update
apt-get install lynx -y
```

**Server eden**  
Melakukan instalasi Apache, php, openssl untuk melakukan download ke website https dengan cara

```
apt-get install apache2 -y
service apache2 start
apt-get install php -y
apt-get install libapache2-mod-php7.0 -y
service apache2
apt-get install ca-certificates openssl -y
```

konfigurasi file `/etc/apache2/sites-available/wise.t07.com.conf`. DcumentRoot diletakkan di /var/www/wise.t07.com. Jangan lupa untuk menambah servername dan serveralias

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/wise.t07.com
        ServerName wise.t07.com
        ServerAlias www.wise.t07.com

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Lalu lakukan membaut sebuah direkroti root untuk server wise.t07.com dan melakukan copy file content

```
mkdir /var/www/wise.t07.com
cp -r /root/Praktikum-Modul-2-Jarkom/wise/. /var/www/wise.t07.com
service apache2 restart
```

### Soal 9

**Server eden**  
konfigurasi file `/var/www/wise.t07.com/.htaccess` dengan

```
a2enmod rewrite
service apache2 restart
echo "
RewriteEngine On
RewriteCond %{REQUEST_FILENAME} !-f
RewriteCond %{REQUEST_FILENAME} !-d
RewriteRule (.*) /index.php/\$1 [L]
```

Inti dari konfigurasi tersebut adalah kita melakukan cek apakah request tersebut adalah ke file atau bukan dan ke direktori atau bukan jika hal tersebut terpenuhi aka kita membuat rule untuk melakukan direct ke /index.php/home. $1 merupakan parameter yang diinputkan di url
konfigurasi file `/etc/apache2/sites-available/wise.t07.com.conf` dengan

```
<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/wise.t07.com
        ServerName wise.t07.com
        ServerAlias www.wise.t07.com

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        <Directory /var/www/wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
</VirtualHost>
```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 10

**Server eden**  
konfigurasi file `/etc/apache2/sites-available/super.wise.t07.com.conf` dengan

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/super.wise.t07.com
        ServerName super.wise.t07.com
        ServerAlias www.super.wise.t07.com

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        <Directory /var/www/wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
</VirtualHost>
```

Lalu aktifkan virtualhost dengan a2ensite, membuat direktori untuk documentroot di /var/www/super.wise.t07.com dan jangan lupa untuk melakukan copy content ke documentroot dengan cara

```
a2ensite super.wise.t07.com
mkdir /var/www/super.wise.t07.com
cp -r /root/Praktikum-Modul-2-Jarkom/super.wise/. /var/www/super.wise.t07.com
service apache2 restart
```

konfigurasi file `/var/www/super.wise.t07.com/index.php` dengan `echo "<?php echo 'yes nomor 10' ?>"`

## soal 11

**Server eden**  
konfigurasi file `/etc/apache2/sites-available/super.wise.t07.com.conf` menamahkan Options +Indexes ke direktori yang ingin di directory list dengan

```

<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/super.wise.t07.com
        ServerName super.wise.t07.com
        ServerAlias www.super.wise.t07.com

        <Directory /var/www/super.wise.t07.com/public>
                Options +Indexes
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        <Directory /var/www/wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
</VirtualHost>

```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 12

**Server eden**  
konfigurasi file `/etc/apache2/sites-available/super.wise.t07.com.conf` menambahkan konfigurasi ErrorDocumentuntuk setiap error yang ada yang diarahkan ke file /error/404.html dengan

```

<VirtualHost *:80>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/super.wise.t07.com
        ServerName super.wise.t07.com
        ServerAlias www.super.wise.t07.com

        ErrorDocument 404 /error/404.html
        ErrorDocument 500 /error/404.html
        ErrorDocument 502 /error/404.html
        ErrorDocument 503 /error/404.html
        ErrorDocument 504 /error/404.html

        <Directory /var/www/super.wise.t07.com/public>
                Options +Indexes
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        <Directory /var/www/wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
</VirtualHost>

```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 13

**Server eden**  
konfigurasi file `/etc/apache2/sites-available/super.wise.t07.com.conf` menambahkan konfigurasi Alias dengan

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/super.wise.t07.com
        ServerName super.wise.t07.com
        ServerAlias www.super.wise.t07.com

        ErrorDocument 404 /error/404.html
        ErrorDocument 500 /error/404.html
        ErrorDocument 502 /error/404.html
        ErrorDocument 503 /error/404.html
        ErrorDocument 504 /error/404.html

        <Directory /var/www/super.wise.t07.com/public>
                Options +Indexes
        </Directory>

        Alias \"/js\" \"/var/www/super.wise.t07.com/public/js\"


        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        <Directory /var/www/wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
</VirtualHost>
```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 14

Dan Luffy meminta untuk web `www.general.operation.wise.yyy.com` hanya bisa diakses dengan port 15000 dan port 15500

### Jawaban Soal 14

**Server eden**  
konfigurasi file `/etc/apache2/sites-available/general.operation.wise.t07.com.conf` disini menambahkan CirtualHost baru yang berada pada port 15000 dan 15500 dengan

```
<VirtualHost *:15000>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/general.operation.wise.t07.com
        ServerName general.operation.wise.t07.com
        ServerAlias www.general.operation.wise.t07.com


        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
<VirtualHost *:15500>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/general.operation.wise.t07.com
        ServerName general.operation.wise.t07.com
        ServerAlias www.general.operation.wise.t07.com


        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Lalu lakukan

```
a2ensite general.operation.wise.t07.com
service apache2 restart
mkdir /var/www/general.operation.wise.t07.com
cp -r /root/Praktikum-Modul-2-Jarkom/general.operation.wise/. /var/www/general.operation.wise.t07.com/
```

konfigurasi file `/var/www/general.operation.wise.t07.com/index.php` dengan

```
<?php
    echo 'selamat 14';
?>
```

konfigurasi file `/etc/apache2/ports.conf` menambahkan Listen 15000 dan 15500 dengan

```
# If you just change the port or add more ports here, you will likely also
# have to change the VirtualHost statement in
# /etc/apache2/sites-enabled/000-default.conf

Listen 80
Listen 15000
Listen 15500
<IfModule ssl_module>
        Listen 443
</IfModule>

<IfModule mod_gnutls.c>
        Listen 443
</IfModule>
```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 15

**Server eden**  
Jalankan Command `htpasswd -c -b /etc/apache2/.htpasswd loid`  
konfigurasi file `/etc/apache2/sites-available/general.operation.wise.t07.com.conf` dengan

```
<VirtualHost *:15000>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/general.operation.wise.t07.com
        ServerName general.operation.wise.t07.com
        ServerAlias www.general.operation.wise.t07.com

        <Directory \"/var/www/general.operation.wise.t07.com\">
                AuthType Basic
                AuthName \"Restricted Content\"
                AuthUserFile /etc/apache2/.htpasswd
                Require valid-user
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
<VirtualHost *:15500>
        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/general.operation.wise.t07.com
        ServerName general.operation.wise.t07.com
        ServerAlias www.general.operation.wise.t07.com

        <Directory \"/var/www/general.operation.wise.t07.com\">
                AuthType Basic
                AuthName \"Restricted Content\"
                AuthUserFile /etc/apache2/.htpasswd
                Require valid-user
        </Directory>

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined
</VirtualHost>
```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 16

**Server eden**  
konfigurasi file `/etc/apache2/sites-available/000-default.conf` dengan

```
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/html

        RewriteEngine On
        RewriteCond %{HTTP_HOST} !^wise.t07.com$
        RewriteRule /.* http://wise.t07.com/ [R]

        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

</VirtualHost>
```

Melakukan restart service apache2 dengan `service apache2 restart`

## soal 17

**Server eden**  
konfigurasi file `/var/www/super.wise.t07.com/.htaccess` dengan

```
echo "
RewriteEngine On
RewriteCond %{REQUEST_URI} ^/public/images/(.*)wise(.*)
RewriteCond %{REQUEST_URI} !/public/images/wise.png
RewriteRule /.* http://super.wise.t07.com/public/images/wise.png [L]
"
```

konfigurasi file `/etc/apache2/sites-available/super.wise.t07.com.conf` dengan

```
echo "
<VirtualHost *:80>

        ServerAdmin webmaster@localhost
        DocumentRoot /var/www/super.wise.t07.com
        ServerName super.wise.t07.com
        ServerAlias www.super.wise.t07.com

        ErrorDocument 404 /error/404.html
        ErrorDocument 500 /error/404.html
        ErrorDocument 502 /error/404.html
        ErrorDocument 503 /error/404.html
        ErrorDocument 504 /error/404.html

        <Directory /var/www/super.wise.t07.com/public>
                Options +Indexes
        </Directory>

        Alias \"/js\" \"/var/www/super.wise.t07.com/public/js\"

        <Directory /var/www/super.wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
        ErrorLog \${APACHE_LOG_DIR}/error.log
        CustomLog \${APACHE_LOG_DIR}/access.log combined

        <Directory /var/www/wise.t07.com>
                Options +FollowSymLinks -Multiviews
                AllowOverride All
        </Directory>
</VirtualHost>
"
```

Melakukan restart service apache2 dengan `service apache2 restart`
