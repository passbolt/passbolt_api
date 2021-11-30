# docker build --build-arg FROM=dokken/fedora-34 -t anatomicjc/fedora:34 .
ARG FROM
FROM ${FROM}
RUN dnf install systemd libxcrypt-compat -y \
 && dnf clean all