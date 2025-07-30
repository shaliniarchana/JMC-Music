FROM php:8.2-cli

# Copy all project files into the container
COPY . /var/www/html

# Set working directory
WORKDIR /var/www/html

# Start PHP's built-in web server on port 10000
CMD ["php", "-S", "0.0.0.0:10000", "-t", "."]
