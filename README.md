<p align="center">
    <a href="https://github.com/yiisoft" target="_blank">
        <img src="https://avatars0.githubusercontent.com/u/993323" height="100px">
    </a>
    <h1 align="center">Yii 2 Advanced Project Template</h1>
    <h1 align="center">ACF - RBAC</h1>
    <br>
</p>


### Clone the Repository
```bash
git clone [repository_url]
```
### Install Dependencies
```bash
composer install
```
### Initialize the Application
```bash
./init
```
### Database Configuration
Configure your database connection in common/config/main-local.php.

### Run Migrations
```bash
php yii migrate
php yii migrate --migrationPath=@yii/rbac/migrations/
```
### Backend Access
#### Default Admin User
Username: admin

Password: admin

1. After logging in, you can create roles with custom permissions from Roles menu.
2. Create users and assign specific roles to them from Users menu.

### Frontend Access
1. Normal users can sign up on the frontend.
2. Confirm their email verification through the links sent to their email.

### Mailer Configuration

Make sure to configure the mailer in common/config/main-local.php:

```bash
return [
    // Other configurations...

    'components' => [
        // Other components...

        'mailer' => [
            'class' => \yii\symfonymailer\Mailer::class,
            'viewPath' => '@common/mail',
            'useFileTransport' => false,
            'transport' => [
                'scheme' => 'smtp',
                'host' => 'sandbox.smtp.mailtrap.io', // for example
                'username' => 'your_smtp_username',
                'password' => 'your_smtp_password',
                'port' => 587,
                'encryption' => 'tls',
            ],
        ],
    ],

    // Other configurations...
];

```
Make sure to replace 'your_smtp_host', 'your_smtp_username', and 'your_smtp_password' with your actual SMTP server details.

### Dockerization
To run the project using Docker, execute the following command:
```bash
docker-compose up -d
```
This will start the Yii2 application and its dependencies in Docker containers. Access the application at http://localhost:port, where port is the specified port in your docker-compose.yml file.

### Contact Information
Feel free to reach out:

- Email: abdollahzadeh.hossein@gmail.com