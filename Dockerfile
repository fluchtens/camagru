# Use php image
FROM php:8.3-fpm

# Update and install required packages
RUN apt-get update && \
    apt-get install -y \
        zlib1g-dev \
        libpng-dev \
        libjpeg-dev \
        ssmtp \
        nginx

# Configure and install the GD extension for PHP with JPEG support
RUN docker-php-ext-configure gd --with-jpeg
RUN docker-php-ext-install pdo_mysql gd

# Define arguments for SMTP username and password
ARG SMTP_USERNAME
ARG SMTP_PASSWORD

# Configure ssmtp with the provided SMTP username and password
RUN echo "root=${SMTP_USERNAME}" >> /etc/ssmtp/ssmtp.conf && \
    echo "mailhub=smtp.gmail.com:587" >> /etc/ssmtp/ssmtp.conf && \
    echo "AuthUser=${SMTP_USERNAME}" >> /etc/ssmtp/ssmtp.conf && \
    echo "AuthPass=${SMTP_PASSWORD}" >> /etc/ssmtp/ssmtp.conf && \
    echo "UseTLS=Yes" >> /etc/ssmtp/ssmtp.conf && \
    echo "UseSTARTTLS=Yes" >> /etc/ssmtp/ssmtp.conf && \
    echo "rewriteDomain=gmail.com" >> /etc/ssmtp/ssmtp.conf && \
    echo "FromLineOverride=YES" >> /etc/ssmtp/ssmtp.conf && \
    chmod 640 /etc/ssmtp/ssmtp.conf && \
    chown root:mail /etc/ssmtp/ssmtp.conf

# Disable access logs by redirecting to /dev/null
RUN echo "access.log = /dev/null" >> /usr/local/etc/php-fpm.d/www.conf

# Copy source code to image
COPY ./src /var/www/html

# Create uploads directory
RUN mkdir -p /var/www/html/assets/uploads

# Set up permissions
RUN chown -R www-data:www-data /var/www

# Copy nginx config
COPY nginx.conf /etc/nginx/conf.d/default.conf

# Expose port 80
EXPOSE 80

# Run nginx and php fpm
CMD service nginx start && php-fpm
