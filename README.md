CMS
====

Installation
========
პროექტი დაწერილია PHP 5.3 ვერსიაზე, იყენებეს რელაციურ მონაცემთა ბაზას MySQL.
მისი გაშვება შესაძლებელია ნებისმიერ ვებ-სერვერზე, რომელსაც გააჩნია url-rewrite მოდული.
ქვემოთ განხილულია კონფიგურაცია Nginx და Apache ვებ-სერვერებისთვის.

პროექტის გაშვება
-----

* მოკლონეთ პროექტი:
```bash
git clone git@git.connect.ge:MB/Photo-festival.git
```

* შემდეგ გაუშვით configure ფაილი რომელიც მოყვება პროექტს:
```bash
./configure
```

* შეაიმპორტეთ ბაზა, რომელიც მოთავსებულია პროექტში db ფოლდერში.

* შექმენით ვირტუალური ჰოსტი თქვენი ვებსერვერისთვის

#### ვირტუალ ჰოსტის მაგალითი **apache2** -ისთვის:
```xml
<VirtualHost *:80>
    ServerAdmin info@photofestival.ge
    DocumentRoot /var/www/photofestival/public_html
    ServerName photofestival.local
        <Directory /var/www/photofestival/public_html>
                Options FollowSymLinks
                AllowOverride All
        </Directory>
</VirtualHost>
```

შექმენით მონაცემთა ბაზა და შეაიმპორტეთ ცხრილები, რომელიც მოცემულია შემდეგ ფაილში:
```
db/cms_base.sql
```

* გახსენით ბრაუზერში ლინკი, რომელზეც განათავსეთ ვებ-გვერდი. (მაგ: http://photofestival.local/)
* თქვენ იხილავთ კონფიგურაციის შესავსებ გვერდს. მემგონი აქ ყველაფერს მიხვდებით. მხოლოდ ერთ მინიშნებას მოგცემთ, ROOT URL -ში უნდა ჩაწეროთ ლინკი, რომლითაც იხსნება index.php ანუ მაგ: http://photofestival.local/ ან თუ მოთავსებულია ქვეფოლდერში უნდა ჩაწეროთ ასე: http://localhost/photofestival/public_html/
* CAPTCHA KEY -ებში უნდა ჩაწეროთ google recaptcha ს მიერ დაგენერირებული KEY ები თქვენი ლოკალური დომეინისთვის. 
* დააჭირეთ SAVE ს და თუ ყველაფერი კარგადაა გამოჩნდება პირველი გვერდი ზემოთ პატარა error -ითურთ. Reload და ეს ერორიც გაქრება ))

* თუ რამე პრობლემა შეგექმნათ "დაგუგლეთ"
* თუ გუგლმა ვერ გიშველათ მომწერეთ მეილზე: ucha4964@gmail.com