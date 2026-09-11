
## Installation on Debian
### Requirements

- A Debian-based system like Ubuntu
- Nginx web server
- PHP 8.2

### Step-by-Step Installation Guide

#### 1. Install Nginx and PHP 8.2

Before installing, update your package manager:

```bash
sudo apt update
```

Install Nginx:

```bash
sudo apt install nginx
```

For PHP 8.2, you might need to add a repository that contains the PHP 8.2 package:

```bash
sudo add-apt-repository ppa:ondrej/php
sudo apt update
```

Then install PHP 8.2 and its extensions:

```bash
sudo apt install php8.2 php8.2-fpm php8.2-cli php8.2-mysql php8.2-zip php8.2-gd php8.2-mbstring php8.2-curl php8.2-xml php8.2-bcmath
```

#### 2. Clone the Repository

Change to any directory of your choice, like /var/www and clone the L2jMobius ACM project from GitLab:

```bash
cd /var/www
git clone https://gitlab.com/nick_anto/l2jmobius-acm.git
```

#### 3. Configure Nginx

Configure Nginx to serve your project. Open your Nginx server block configuration:

```bash
sudo nano /etc/nginx/sites-available/your_domain
```

Replace `your_domain` with your actual domain name. Add the following location block, ensuring the root directive points to the public directory of your project:

```nginx
server {
    # ... other configurations ...
    server_name your_domain;
    root /var/www/l2jmobius-acm/public;

    location / {
        try_files $uri $uri/ /index.php?$args;
    }

    # ... other configurations ...
}
```

Replace `your_domain` with your actual domain name.

Don't forget to create a symbolic link in the `sites-enabled` directory and restart Nginx to apply the changes:

```bash
sudo ln -s /etc/nginx/sites-available/your_domain /etc/nginx/sites-enabled/
sudo systemctl restart nginx
```

#### 4. Create and Configure the .env File

Copy the `.env.example` file to create a new `.env` file:

```bash
cp /var/www/l2jmobius-acm/.env.example /var/www/l2jmobius-acm/.env
```

Edit the `.env` file to add your settings:

```bash
nano /var/www/l2jmobius-acm/.env
```

Add your specific configurations like database settings, application keys, etc.

#### 5. Install dependances via Composer

Download composer with the following command:

```bash
php -r "copy('https://getcomposer.org/installer', 'composer-setup.php');"

```

Install composer globally by running:

```bash
php composer-setup.php --install-dir=/usr/local/bin --filename=composer
```

Remove the installer:
```bash
php -r "unlink('composer-setup.php');"
```

Verify the installation by checking the Composer version:
```bash
composer --version
```
This will confirm that Composer has been successfully installed on your system.

Run Composer Update in the your project. After installing Composer, navigate to the root directory of your L2jMobius ACM project and run the following command:

```bash
cd /var/www/l2jmobius-acm
composer update
```

---

#### 6. Install gettext

Install gettext using the following commands:

```bash
apt install gettext
apt install php8.2-gettext
```

Enable your languages by installing the cooresponding locales.

```bash
locale-gen el_GR
locale-gen el_GR.utf8
locale-gen ru_RU
locale-gen ru_RU.utf8
```

Restart your php:
```bash
service php8.2-fpm restart
```

Ensure to replace placeholder texts like `/var/www/l2jmobius-acm` with the actual path to your project.


## Common errors

#### 1. Error updating version file.

L2jMobius ACM uses a file named `.version` to check for database updates. You can find the function under `helpers/functions.php`. If you receive this error, it means that nginx doesn't have permission to create this file. Create the file and make it writable.
```bash
cd /var/www/l2jmobius-acm
touch .version
chmod 777 .version
```
