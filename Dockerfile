# Static HTML/CSS/JS site, served by nginx. No PHP, no database —
# the entire site lives in preview/ and is served as-is.
FROM nginx:alpine

COPY preview/ /usr/share/nginx/html/
COPY nginx.conf.template /etc/nginx/templates/default.conf.template

ENV PORT=8080
EXPOSE 8080
