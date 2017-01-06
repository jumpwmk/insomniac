# README #

This is the best online judge website in the world. haha.

### Installation ###

1. Clone this repository.
2. Open **local** directory by using command line.
3. Set up **.env** file by duplicate **.env.example** and put your info.
4. Set up **mysql.php** in judge directory by duplicate **mysql.php.example** and put your info.
5. Type ``composer install`` in command line.
6. Back to local directory to set permission. Type ``chmod -R 777 storage vendor`` and ``chmod 777 ../judge ../judge/codes ../judge/testcases ../judge/checkcodes ../judge/docs ../img/user``. Something, you must be superuser to set permission of directories.
7. Type ``php artisan migrate --seed`` in command line.
8. Open your phpMyAdmin and edit **root** field in **configs** table to your CodeCube directory.
9. username **admin**, password **12345678** for login.