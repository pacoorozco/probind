FROM internetsystemsconsortium/bind9:9.16

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG USER=probinder
ARG PASSWORD=docker

# Run ssh server on the DNS server.
RUN apt-get update && apt-get install -y openssh-server

# Creates the user that ProBIND will use to access to the DNS server.
RUN useradd --home-dir /home/${USER} --create-home ${USER} && \
    echo "${USER}:${PASSWORD}" | chpasswd
COPY --chown=${USER}:${USER} ./docker/dns/authorized_keys /home/${USER}/.ssh/authorized_keys

# Configures SSH to enable SFTP service.
COPY ./docker/dns/sshd/*.conf /etc/ssh/sshd_config.d/
RUN sed -i "s/__DOCKER_USER__/${USER}/" /etc/ssh/sshd_config.d/sftp.conf

# Copies the entrypoint to start the docker.
COPY ./docker/dns/entrypoint.sh /sbin/entrypoint.sh
RUN chmod 755 /sbin/entrypoint.sh

EXPOSE 53/udp 53/tcp 22/tcp

CMD ["/sbin/entrypoint.sh"]
