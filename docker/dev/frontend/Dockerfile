# ==== CONFIGURE =====
# Use a Node 16 base image
FROM node:16-alpine
# Set the working directory to /application inside the container
WORKDIR /application
# Copy app files
COPY ./frontend/package-lock.json ./
COPY ./frontend/package.json ./
COPY ./frontend/src ./src
# Install dependencies (npm ci makes sure the exact versions in the lockfile gets installed)
RUN npm install
# ==== RUN =======
# Set the env to "development"
ENV NODE_ENV development
# Expose the port on which the app will be running (4200 is the default that `serve` uses)
EXPOSE 4200
# Install dependencies on host and start the app
CMD "node_modules" && npm run start:docker || npm ci && npm run start:docker
