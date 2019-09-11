CREATE USER 'user'@'%' IDENTIFIED WITH mysql_native_password BY 'secret';
GRANT ALL ON `bobine`.* TO user@'%';

FLUSH PRIVILEGES;
