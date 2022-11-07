# VExpenses Test

This project was developed as part of VExpenses' selection process, for the position of Fullstack Developer

## Project Details

The project consists of an application based on microservices, where each service has its well-defined responsibility. The services are:
- User service: User interface and authentication service
- Employee service: Responsible for maintaining employee records and allowing loading of many records through CSV file import
- Company service: Responsible for maintaining the register of companies

The services communicate through Http requests and queues, using cache to store the data already obtained between them.

See more details about each service by accessing its corresponding directory.

## Technologies

The technologies used in the project are
- Docker
- Laravel
- Laravel Excel (Library for importing and exporting files)
- MySQL
- RabbitMQ
- Redis