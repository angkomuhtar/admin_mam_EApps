## Running This Project with Docker

This project includes a Docker setup using [FrankenPHP](https://frankenphp.dev/) (version 1.1.3) for running the Laravel application. The Docker configuration is tailored for production use and leverages Composer for dependency management.

### Requirements
- Docker and Docker Compose installed on your system.
- (Optional) A `.env` file for environment variables. An example is provided as `.env.example`.

### Build and Run Instructions
1. **Copy the example environment file if needed:**
   ```sh
   cp .env.example .env
   # Edit .env as needed for your environment
   ```
2. **Build and start the application:**
   ```sh
   docker compose up --build
   ```
   This will build the Docker image and start the `php-app` service.

### Service Details
- **php-app**
  - Runs on FrankenPHP 1.1.3
  - Exposes port **80** (accessible at [http://localhost:80](http://localhost:80))
  - Uses a non-root user for security
  - Composer dependencies are installed in a multi-stage build for optimized images
  - Storage and cache directories are set with correct permissions for Laravel

### Environment Variables
- The application expects environment variables as defined in `.env` or `.env.example`.
- You can uncomment the `env_file` line in `docker-compose.yml` to load your `.env` file automatically.

### Special Configuration
- The Dockerfile copies a `frankenphp-worker.conf` file if present for custom FrankenPHP worker configuration.
- The application is served by FrankenPHP, which is optimized for running PHP applications in production.
- If you add a database or cache service, update `docker-compose.yml` accordingly and use the `depends_on` section as needed.

### Networks
- All services are connected to the `app-network` Docker network for inter-service communication.

---

For more details on Laravel usage and configuration, refer to the sections above or the [Laravel documentation](https://laravel.com/docs).