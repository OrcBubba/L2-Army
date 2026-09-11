
## Installation on Windows Using XAMPP

### Requirements

- Windows Operating System
- XAMPP (which includes Apache, MySQL, PHP)

### Step-by-Step Installation Guide

#### 1. Install XAMPP

Download and install XAMPP from the official [Apache Friends website](https://www.apachefriends.org/index.html). This package includes Apache, MySQL, and PHP.

#### 2. Run XAMPP and Start Services

After installing, open the XAMPP Control Panel and start the Apache and MySQL services.

#### 3. Clone the Repository

Clone the L2jMobius ACM project from GitLab:

```bash
git clone https://gitlab.com/nick_anto/l2jmobius-acm.git
```

#### 4. Configure Apache (via XAMPP)

Configure Apache to serve your project. You'll need to set up a virtual host for this:

1. Open the `httpd-vhosts.conf` file, typically located at `C:\xampp\apache\conf\extra\httpd-vhosts.conf`.

2. Add the following lines at the end of the file:

   ```apache
   <VirtualHost *:80>
       DocumentRoot "C:/xampp/htdocs/l2jmobius-acm/public"
       ServerName yourdomain.com
       <Directory "C:/xampp/htdocs/l2jmobius-acm/public">
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

Replace `C:/xampp/htdocs/l2jmobius-acm/public` with the path to the public directory of your project. `yourdomain.com` can be replaced with a local domain of your choice.

3. If your domain doesn't point to your IP, edit your `hosts` file located at `C:\Windows\System32\drivers\etc\hosts`, and add the following line:

   ```
   127.0.0.1 yourdomain.com
   ```

4. Restart Apache in XAMPP to apply these changes.

#### 5. Create and Configure the .env File

Copy the `C:/xampp/htdocs/l2jmobius-acm/.env.example` file to create a new `.env` file:

```bash
copy C:\xampp\htdocs\l2jmobius-acm\.env.example C:\xampp\htdocs\l2jmobius-acm\.env
```

Edit the `.env` file to add your settings. You can use any text editor for this.

#### 6. Install Composer

1. Download the Composer installer from [getcomposer.org](https://getcomposer.org/download/) and run it.

2. Follow the installation instructions, ensuring you enable the option to install Composer globally.

#### 7. Run Composer Update in the Project

Open a command prompt or PowerShell, navigate to your project directory, and run:

```bash
cd C:\xampp\htdocs\l2jmobius-acm
composer update
```
