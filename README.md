# php-punch
A php based tool to aid in clocking in and out automatically.

[![GitHub Release](https://img.shields.io/github/v/release/modnar1226/php-punch?style=flat)]()
[![Github All Releases](https://img.shields.io/github/downloads/modnar1226/php-punch/total.svg?style=flat)]()
[![Travis Ci Build](https://travis-ci.com/modnar1226/php-punch.svg?branch=master)]()

Features:

1. Selenium webdriver based commands.
2. Run as a cron job to clock in or out.
3. Skips holidays.
4. Customizable PTO/ Paid Time Off array to skip defined dates.
5. Randomizes time period (within a range) that you clock in or out.
6. Output can be saved as a back up of the punches, output is delimited by double quotes (") and field separated by commas (,) ie, csv format

Requires:
1. Composer 
2. Chrome Browser
3. Chromdriver - get package here https://chromedriver.chromium.org/downloads get the version that corresponds to your chrome version. extract executable file form download. mv executable to /usr/bin/chromedriver. chown $USER:$USER /user/bin/chromedriver.
4. Php
5. Php-Webdriver via Composer `composer require php-webdriver/webdriver`

Usage:
1. Install requred packages
2. Copy `config.example.php` to `config.php`
3. Set credentials and element ids in config file. (Requires walking through your clock in/out site and recording the proper css ids)
4. Set a cron to run the program at your desired time for clocking in,
`crontab -e`

```sh
# 8:55 am Monday - Friday, script default is a 5 minute range from this time
55 8 * * 1-5 php /path/to/punch.php 'in' >> /path/to/time_sheet.txt
```
5. Set a cron to run the program at your desired time for clocking out,
`crontab -e`

```sh
# 5:00 pm Monday - Friday, script default is a 5 minute range from this time
00 17 * * 1-5 php /path/to/punch.php 'out' >> /path/to/time_sheet.txt
```

5. You can even create a desktop button to run the clock in sequence immediately (tested on ubuntu 18) with a .desktop file. Create a new file and add the following:
```
[Desktop Entry]
Comment=Clock In and log in time sheet
Icon=alarm-clock
Exec=sh -c "php /home/username/php-punch/punch.php 'in' 0 >> /home/username/Desktop/timesheet.txt"
Terminal=false
Type=Application
Name[en_US]=ClockIn
```

6. To clock out create a new desktop file and add the following:
```
[Desktop Entry]
Comment=Clock Out and log in time sheet
Icon=alarm-clock
Exec=sh -c "php /home/username/php-punch/punch.php 'out' 0 >> /home/username/Desktop/timesheet.txt"
Terminal=false
Type=Application
Name[en_US]=ClockOut
``` 