# php-punch
A php based tool to aid in clocking in and out automatically.

Features:

1. Selenium webdriver based commands.
2. Run as a cron job to clock in or out.
3. Skips holidays.
4. Randomizes time period that you clock in or out.

Requires:
1. Composer
2. Chrome Browser
3. Chromdriver
4. Php
5. Php-Webdriver

Usage:
1. Install requred packages
2. Set credentials and element ids in config file. (Requires walking through your clock in/out site and recording the proper css ids)
3. Set a cron to run the program at your desired time for clocking in.
```
php /path/to/punch.php in
```
4. Set a cron to rub the program at your desired time for clocking out
```
php /path/to/punch.php out
```