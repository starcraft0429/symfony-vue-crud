include .env

.PHONY: up down webapp api consume vagrant

# Start the Docker Compose stack.
up:
	docker-compose up -d

# Stop the Docker Compose stack.
down:
	docker-compose down

# Run bash in the webapp service.
webapp:
	docker-compose exec webapp bash

# Run bash in the api service.
api:
	docker-compose exec api bash

# Consume messages from the queue.
consume:
	docker-compose exec api php bin/console messenger:consume async -vv

# Create the Vagrantfile from the template Vagrantfile.template.
vagrant:
	./scripts/create-vagrantfile-from-template.sh \
	$(VAGRANT_BOX) \
	$(VAGRANT_PROJECT_NAME) \
	$(VAGRANT_MEMORY) \
	$(VAGRANT_CPUS) \
	$(VAGRANT_DOCKER_COMPOSE_VERSION)
