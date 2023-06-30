## Ad Campaign Tracking Script

> The Ad Campaign Tracking Script project is as a demo tutorial project to demonstrating the efficient collection, caching, and storage of ad campaign data. The javascript part integrates with a publisher's website, tracking user behavior and campaign effectiveness, optimizing data handling using Redis for caching and a relational database for permanent storage.

## We are using here.

- Laravel Framework
- Redis
- MySQL
- PHPUnit
- [IpStack Api service](https://ipstack.com/)

### How to run this project

Start by cloning the repository to your local machine using the following command:

```bash
git clone git@github.com:rana7cse/ad-network-tracking-script.git
```
Once the repository is successfully cloned, navigate to the project directory and execute the following command to install necessary dependencies then run this laravel project with sail:
```bash
composer install
./vendor/bin/sail up
```
You can view it by opening your browser and accessing `localhost:3000`.

> To run the project using Laravel Sail and the container environment, make sure you have Docker installed on your machine. Then, run the following command in the project directory:

The docker-compose services are mapped to the following host ports in `docker-compose.yml`:

- Laravel: Port 3000
- MySQL: Port 3370
- Redis: Port 6379

Following commands to run migration, queue task.

```bash
# Run migration
./vendor/bin/sail artisan migrate

# Run queue worker
./vendor/bin/sail artisan queue:work
```

To save Aggregate data from redis to database table run this command 
```bash
./vendor/bin/sail artisan  campaignData:save
```
### Project internal overview





