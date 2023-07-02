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

Next, copy the .env.example file to .env using the following command:
``` bash
cp .env.example .env
```

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
### Basic Overview
#### Main Implementation Files
The following files contain the main code implementation for the project:
```bash
Project Folder(ad-network-tracking-script)
├── app
│   ├── Console
│   │   └── Commands
│   │       └── SaveCampaignDataToDB.php
│   ├── Http
│   │   └── CampaignTrackingController.php
│   ├── Jobs
│   │   └── StoreCampaignTrackingData.php
│   ├── Repositories
│   │   └── FastStorageRepository.php
│   └── Services
│       ├── CampaignTrackingService.php
│       └── IpStackService.php
├── resources
│   ├── views
│   │   └── index.php
│   ├── ...
├── routes
│   └── web.php
├── tests
│   ├── Feature
│   │   ├── CampaignTrackerTest.php
│   │   └── SaveCampaignDataCommandTest.php
│   └── Unit
│       ├── CampaignTrackingServiceTest.php
│       └── IPStackServiceTest.php
├── ...
```
#### Implementation summery 
This summary provides an overview of the implementation process

- The project's root `/` loads the `index.blade.php` file, which contains the main JavaScript tracker implementation.
- By the JavaScript `track` method, an image URL `/track` is loaded, and a request is sent to the server.
- The request is received by the `CampaignTrackingController`'s `tracker()` method and send response.
- The `tracker()` method transforms the request query parameters into campaign data.
- The `StoreCampaignTrackingData` job is dispatched with the campaign data.
- The dispatched job calls the `CampaignTrackingService`'s `storeDataToFS()` method to save the campaign data to fast storage.
- The `IpStackService` interacts with the **IP Stack API**, a third-party service, to retrieve information based on the client's IP address, caching the response for 30 days.
- The `FastStorageRepository` manages data storage and retrieval from Redis, serving as an intermediary between the application and Redis.
- The `SaveCampaignDataToDB` command retrieves all campaign data from fast storage using the CampaignTrackingService and saves it to the corresponding database table.

### Next improvement
- Implement rate limiting or request throttling mechanisms to prevent tracking on every request.
- Migrate the JavaScript tracker class into a separate JavaScript file from index.blade.php.
- Implement data validation and negative checks as necessary.
- Incorporate the SaveCampaignDataToDB command into the scheduler for automation.
- Enhance the FastStorageRepository to support additional types of storage.
- Add more doc block and test cases.





