# 🔑 Getpix Gateway API

A lightweight and scalable API Gateway built with [HyperF](https://hyperf.io/) and powered by [Swoole](https://www.swoole.co.uk/). It acts as the main entry point for managing Pix keys and integrates seamlessly with dedicated microservices using **gRPC** and **Kafka**.

---

## 🧠 Overview

This API Gateway is part of the **Getpix** ecosystem. It orchestrates communication between services, routing gRPC calls to internal services and dispatching logs asynchronously through Kafka.

---

## 🧱 Tech Stack

- **[HyperF](https://hyperf.io/)** + **Swoole** — High-performance PHP framework for async operations.
- **gRPC** — Efficient binary communication between services.
- **Kafka** + **Zookeeper** — Messaging and event streaming.
- **PHP 8** — Strong-typed modern PHP code.
- **Docker** + **Docker Compose** — Containerized infrastructure.
- **Redis** — In-memory caching and simple data storage.
- **MongoDB** — NoSQL database for logging and analytics.
- **Prometheus** + **Grafana** — Metrics collection and visualization.

---

## 🏗️ Architecture

```txt
[ Client ]
   |
   v
[ Getpix Gateway API ]
   |        \
   |         \-> (Kafka Topic) --> [ getpix_logs ]
   |
   \-> (gRPC) --> [ getpix_pix ]
```

## 📚 Repositories - Getpix ecosystem: 
- [getpix_logs](github.com/luigi-raynel-dev/getpix_logs): Kafka consumer that stores logs into MongoDB.

- [getpix_pix](github.com/luigi-raynel-dev/getpix_pix): Microservice responsible for the full CRUD of user Pix keys via gRPC.

## 🚀 Getting Started
✅ Requirements
- [Docker](https://www.docker.com/)
- [Docker Compose](https://docs.docker.com/compose/install/)

📦 Running the Application
```bash
git clone https://github.com/luigi-raynel-dev/getpix_gateway.git
cd getpix_gateway
```

Start all services with Docker Compose:
```bash
docker compose up -d
```

Install composer packages into container
```bash
docker exec -it getpix_app bash -c "composer install --ignore-platform-req=ext-mongodb"
```

Copy the `.env.example` file into the `html` folder and rename it to `.env`.  
Then, fill in the environment variables according to your configuration.

### 📄 Example `.env` content:

```env
APP_NAME=skeleton
APP_ENV=dev

MONGODB_URI="mongodb://mongo:27017"
MONGODB_DATABASE="getpix"

REDIS_HOST="getpix_redis"
REDIS_AUTH=(null)
REDIS_PORT=6379
REDIS_DB=0

KAFKA_SERVERS="getpix_kafka:9092"
KAFKA_LOGS_TOPIC="getpix.logs"

JWT_SECRET_KEY="secret"
JWT_ACCESS_EXP="60" # Minutes
JWT_REFRESH_EXP="10080" # Minutes

# Microservices
GETPIX_PIX_HOST="getpix_pix"
GETPIX_PIX_PORT="9503"
```

### 📦 Database - MongoDB Setup
The Getpix ecosystem uses MongoDB to store and manage data.

You have two options to get it running
- Option 1: Use MongoDB Atlas (Free)
  - Go to https://www.mongodb.com/cloud/atlas
  - Create a free account and cluster
  - Whitelist your IP and create a database user
  - Replace MONGODB_URI in your .env with your Atlas connection string Example: `MONGODB_URI="mongodb+srv://<user>:<password>@cluster0.mongodb.net/?retryWrites=true&w=majority"`
- Option 2: Run MongoDB with Docker
  - If you prefer to run MongoDB locally with Docker:
  ```bash
  docker run -d \
  --name mongo \
  -p 27017:27017 \
  -e MONGO_INITDB_ROOT_USERNAME=root \
  -e MONGO_INITDB_ROOT_PASSWORD=secret \
  mongo
  ```
  - Replace MONGODB_URI in your .env with connection string Example: `MONGODB_URI="mongodb://root:secret@localhost:27017"`

## ▶️ Running the Application

Before starting the API Gateway, make sure all **required microservices** are up and running.

To test the API, you can use Postman.

### 📥 Steps:
1. Open [Postman](https://www.postman.com/)
2. Click "Import"
3. Select the file [postman_collection.json](https://github.com/luigi-raynel-dev/getpix_gateway/blob/main/postman_collection.json) located at the root of this project
4. Choose the environment variables
5. Start sending requests to test the endpoints

## ✅ Running Integration Tests

This project includes integration tests using **PHPUnit** and `hyperf/testing` to ensure everything is working as expected.

To run the tests, make sure the container `getpix_app` is running and the necessary services are up and runnig.

### ▶️ Run the tests with the following command:

```bash
docker exec -it getpix_app bash -c "composer run test"
```

### 👀 Observability
#### Prometheus and Grafana are configured for monitoring. Once running, access:
- **Prometheus**: http://localhost:9090
- **Grafana**: http://localhost:3000 (Default user/pass: admin / admin)

> Observability is handled via the hyperf/metric component integrated with Prometheus.

## Generate GRPC files
```bash
protoc --proto_path=proto/ --php_out=grpc/ proto/*.proto
