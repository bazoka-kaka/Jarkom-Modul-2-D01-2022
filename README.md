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
