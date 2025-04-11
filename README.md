# getpix

### A simple api using Pix Api, PHP, Swoole, HyperF, Kafka, Zookeeper, Docker and MongoDB

## Generate GRPC files
```bash
protoc --proto_path=proto/ --php_out=grpc/ proto/*.proto
