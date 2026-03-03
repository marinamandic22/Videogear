# ==== CONFIGURE =====
# Use a Node 16 base image
FROM node:16-alpine as builder
# Set work directory
WORKDIR /application
# Copy app files
COPY ./frontend ./
# Install dependencies (npm ci makes sure the exact versions in the lockfile gets installed)
RUN npm install
# Build the app
RUN npm run build --prod
# Bundle static assets with nginx
FROM nginx:alpine
ENV NODE_ENV production
# Copy built assets from `builder` image
COPY --from=builder /application/dist/frontend /usr/share/nginx/html
# Expose port
EXPOSE 80
# Start nginx
CMD ["nginx", "-g", "daemon off;"]
