# Use a built-in PHP + Apache image
FROM php:8.2-apache

# Install MySQL support for your Aiven database
RUN docker-php-ext-install pdo pdo_mysql

# Copy your website files into the server folder
COPY . /var/www/html/

# Make sure Apache can read your files
RUN chown -R www-data:www-data /var/www/html/

# Tell the server to listen on port 80
EXPOSE 80
