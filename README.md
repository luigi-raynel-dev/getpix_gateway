# Getpix Gateway API

### A simple example of an api gateway for managing your pix keys that communicates with microservices using gRPC, HyperF + Swoole PHP. 

### Stacks:
- HyperF + Swoole: Asynchronous PHP framework built on top of Swoole, designed for high-performance applications.
- gRPC: Remote Procedure Call framework by Google
- PHP 8: A Modern Scripting language
- Docker: A suite of development tools
- Kafka + Zookeeper: Queue
- Redis: Storage simple data and caching on memory
- MongoDB: NoSql Database
- Prometheus: Open-source tool for metrics collection and monitoring.
- Grafana: Open-source platform for visualizing and analyzing metrics.

## Generate GRPC files
```bash
protoc --proto_path=proto/ --php_out=grpc/ proto/*.proto
