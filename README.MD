# VExpenses Test

This project was developed as part of VExpenses' selection process, for the position of Fullstack Developer

## Project Details

The project consists of an application based on microservices, where each service has its well-defined responsibility. The services are:
- User service: User interface and authentication service
- Employee service: Responsible for maintaining employee records and allowing loading of many records through CSV file import
- Company service: Responsible for maintaining the register of companies

The services communicate through Http requests and queues, using cache to store the data already obtained between them.

See more details about each service by accessing its corresponding directory.

## How to execute the project
### Requirements

- Git
- Docker
### Steps
1. Clone repository to local machine
    `git clone git@github.com:lucasv-bs/vexpenses-test.git`
2. Application Configuration
    - Acess the cloned project folder on your local machine
    - For each service (company-app, employee-app and user-app), do:
    - Review the docker-compose.yml file and update the values according to your environment, for example the App, MySQL and Redis access ports and other parameters that you consider necessary to change
    - Copy and paste the .env.example file in the service root folder. Then rename the duplicated file to ".env"
    - Set the database access values by filling all the `DB_*` variables according to the parameters defined in docker-compose.yml
    - Set the Redis access values by filling all the `REDIS_*` variables according to the parameters defined in docker-compose.yml
    - Set the AWS S3 access values by filling all the `AWS_*` variables.
    - Set the RabbitMQ access values by filling all the `RABBITMQ_*` variables. (PS: RabbitMQ is only used in user-app and employee-app services)
3. Execute the containers of each service
    - From the root folder of the service, run the command: `docker-compose up -d`
4. Execute migrations
    - Access each container with the command
        - `docker exec -it 'container-name' /bin/bash`
    - Then run database migrations
        - `php artisan migrate`
5. Build assets of the user-app service. In the user-app service container, run the following command
    - `npm install`
6. Access the user-app interface via `http://localhost:port`. The port is the same as defined in the docker-compose.yml file

## Technologies

The technologies used in the project are
- AWS S3
- Docker
- Laravel
- Laravel Excel (Library for importing and exporting files)
- MySQL
- RabbitMQ
- Redis