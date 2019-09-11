FROM redis:latest

EXPOSE 6379

COPY redis.conf /etc/redis/

WORKDIR /etc/redis

CMD ["redis-server"]
