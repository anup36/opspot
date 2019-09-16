FROM nginx:1.13-alpine

RUN rm /etc/nginx/conf.d/default.conf
COPY nginx.conf /etc/nginx/nginx.conf
COPY ./opspot.conf /etc/nginx/conf.d/opspot.conf
