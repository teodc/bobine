FROM nginx:1

WORKDIR /etc/nginx

EXPOSE 80 443

RUN usermod -u 1000 -g www-data www-data

COPY nginx.conf /etc/nginx/
COPY sites/* /etc/nginx/sites/
COPY ssl/* /etc/nginx/ssl/

CMD ["nginx"]
