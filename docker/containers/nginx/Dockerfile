FROM nginx:latest

EXPOSE 80 443

RUN adduser -D -H -u 1000 -s /bin/bash -G www-data www-data

COPY nginx.conf /etc/nginx/
COPY sites/* /etc/nginx/sites/
COPY ssl/* /etc/nginx/ssl/

WORKDIR /etc/nginx

CMD ["nginx"]
