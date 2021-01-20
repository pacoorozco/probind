FROM internetsystemsconsortium/bind9:9.16

LABEL maintainer="paco@pacoorozco.info"

# Arguments defined in docker-compose.yml
ARG USER=probinder
ARG PASSWORD=docker

# Run ssh server on the DNS server.
RUN apt-get update && apt-get install -y openssh-server

COPY ./docker/dns/sshd-listen.conf /etc/ssh/sshd_config.d/listen.conf
COPY ./docker/dns/entrypoint.sh /sbin/entrypoint.sh
RUN chmod 755 /sbin/entrypoint.sh

# Creates the user that ProBIND will use to access to the DNS server.
RUN useradd --home-dir /home/${USER} --create-home --groups bind ${USER} && \
    echo "${USER}:${PASSWORD}" | chpasswd

# Copy ssh key to access from ProBIND.
COPY ./docker/dns/authorized_keys /home/${USER}/.ssh/authorized_keys
RUN chown --recursive ${USER}:${USER} /home/${USER}/.ssh

EXPOSE 53/udp 53/tcp 22/tcp

CMD ["/sbin/entrypoint.sh"]




