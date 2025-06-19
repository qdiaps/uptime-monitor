# uptime monitor
<a id="readme-top"></a>

<p align="center">
    <img src="https://img.shields.io/github/languages/top/qdiaps/uptime-monitor?style=for-the-badge">
    <img src="https://img.shields.io/github/languages/count/qdiaps/uptime-monitor?style=for-the-badge">
    <img src="https://img.shields.io/github/license/qdiaps/uptime-monitor?style=for-the-badge">
    <img src="https://img.shields.io/github/last-commit/qdiaps/uptime-monitor?style=for-the-badge">
</p>

> [!CAUTION]
> At the moment, uptime monitor is under active development, many things may not work, and this version is not recommended for use (all at your own risk)

**Uptime Monitor** is a microservice system for monitoring service availability (HTTP(S), Ping, etc) with a web interface and event-driven architecture.
Main functionality:
- **Real-time monitoring** (polls services on a schedule)
- **Web interface** (Laravel + Vue.js) for managing checks
- **Asynchronous processing** via Kafka (load sharing)
- **Kafdrop** for debugging Kafka events

---

## Table of Contents
- [Dependencies & Libraries](#dependencies--libraries)
  - [Core Infrastructure](#core-infrastructure)
  - [Web App](#web-app)
  - [Monitor Management](#monitor-management)
- [Getting Started](#getting-started)
- [Architecture](#architecture)
- [Makefile Commands](#makefile-commands)

## Dependencies & Libraries
### Core Infrastructure
| Technology                                                                                 | Purpose                                     | Explanation                                                                                                                                                                               |
|--------------------------------------------------------------------------------------------|---------------------------------------------|-------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| **[Docker](https://www.docker.com/) & [Docker Compose](https://docs.docker.com/compose/)** | Unified deployment across all microservices | Used to run the entire project in isolated containers, ensuring consistent environment across deployments. Docker Compose simplifies multi-service management (web, API, databases, etc.) |
| **[Makefile](https://www.gnu.org/software/make/manual/)**                                  | Automation of common tasks                  | Provides shortcuts for project setup, testing, and deployment (e.g., `make start`, `make init`). Simplifies development workflow by abstracting complex commands.                         |
| **[Redis](https://redis.io/)**                                                             | Cache/Message broker                        | Caching, temporary data (sessions).                                                                                                                                                       |
| **[PostgreSQL](https://www.postgresql.org/)**                                              | Primary database                            | A basic repository for structured data.                                                                                                                                                   |
| **[Kafka](https://hub.docker.com/r/bitnami/kafka)**                                        | Event streaming                             | A centralized message broker for asynchronous service communication.                                                                                                                      |
| **[Kafdrop](https://hub.docker.com/r/obsidiandynamics/kafdrop)**                           | Kafka UI                                    | A web interface for monitoring Kafka topics.                                                                                                                                              |

### Web App
| Technology                                                         | Purpose            | Explanation                                                                                       |
|--------------------------------------------------------------------|--------------------|---------------------------------------------------------------------------------------------------|
| **[PHP](https://www.php.net/)**                                    | Backend runtime    | The main language for the server side. Used with Laravel for query processing and business logic. |
| **[Laravel](https://laravel.com/)**                                | Web framework      | MVC framework for routing, ORM (Eloquent), authentication and integration with Kafka/Redis.       |
| **[Supervisord](https://supervisord.org/)**                        | Process management | Controls Laravel background processes (queues, wokers) to ensure fault tolerance.                 |
| **[npm](https://www.npmjs.com/)**                                  | Package manager    | Install and manage JavaScript dependencies (Vue.js, Vite, plugins).                               |
| **[Vite](https://vite.dev/)**                                      | Frontend tooling   | Build the frontend with hot reloading (HMR) and asset optimization.                               |
| **[Vue.js](https://vuejs.org/)**                                   | Frontend framework | Reactive components for the interface. Integration with Laravel via Inertia.js or API.            |
| **[laravel-kafka](https://github.com/mateusjunges/laravel-kafka)** | Kafka integration  | Send/consume events between microservices via Kafka.                                              |
| **[predis](https://github.com/predis/predis)**                     | Redis client       | Working with Redis for Laravel caching, sessions and queues.                                      |

### Monitor Management
| Technology                                                         | Purpose            | Explanation                                                                                       |
|--------------------------------------------------------------------|--------------------|---------------------------------------------------------------------------------------------------|
| **[PHP](https://www.php.net/)**                                    | Backend runtime    | The main language for the server side. Used with Laravel for query processing and business logic. |
| **[Laravel](https://laravel.com/)**                                | Web framework      | MVC framework for routing, ORM (Eloquent), authentication and integration with Kafka/Redis.       |
| **[Supervisord](https://supervisord.org/)**                        | Process management | Controls Laravel background processes (queues, wokers) to ensure fault tolerance.                 |
| **[laravel-kafka](https://github.com/mateusjunges/laravel-kafka)** | Kafka integration  | Send/consume events between microservices via Kafka.                                              |
| **[predis](https://github.com/predis/predis)**                     | Redis client       | Working with Redis for Laravel caching, sessions and queues.                                      |

## Getting Started
1. Clone the repository
```bash
git clone https://github.com/qdiaps/uptime-monitor.git
cd uptime-monitor
```
2. **Initialize the project**. Run the automatic configuration (image build, dependency installation, database migrations):
```bash
make init 
```
3. **Access services**. Upon successful completion:
- **Web Application** → http://localhost  Main interface on Laravel + Vue.js
- **Kafdrop** (Kafka UI) → http://localhost:9000  Real-time monitoring of Kafka's topicals

## Architecture
The system is built on a microservice architecture with separation of data and functionality. Main components:

| Service                | Role                                | Dependency                                                      |
|------------------------|-------------------------------------|-----------------------------------------------------------------|
| **web-app**            | Main interface (Laravel + Vue.js)   | `pgsql.web-app`, `redis.web-app`, `kafka`                       |
| **monitor-management** | CRUD operations with monitors       | `pgsql.monitor-management`, `redis.monitor-management`, `kafka` |
| **kafdrop**            | Web interface for Kafka monitoring  | `kafka`                                                         |
| **pgsql**              | Relational databases                | none                                                            |
| **redis**              | Cache and queue broker              | none                                                            |
| **kafka**              | Broker of messages between services | none                                                            |

## Makefile Commands
| Command       | Description                                                                                                                                                                  |
|---------------|------------------------------------------------------------------------------------------------------------------------------------------------------------------------------|
| `make init`   | Complete project initialization: 1. Building Docker images. 2. Creating Kafka-topics. 3. Dependencies installation (Composer, npm). 4. Key generation and database migration |
| `make reinit` | Full reset and rebuild                                                                                                                                                       |
| `make start`  | Starting all services                                                                                                                                                        |
| `make stop`   | Stopping services (without deletion)                                                                                                                                         |
| `make down`   | Stopping and removing containers                                                                                                                                             |
| `make clean`  | Complete cleanup (containers, images, volumes, networks)                                                                                                                     |

---

<div align="center">
  <br>
  <a href="#readme-top">↑ Back to Top ↑</a>
  <br>
</div>