#
# Cookbook:: docker_test
# Recipe:: passbolt_install
#
# Copyright:: 2020, The Authors, All Rights Reserved.
#

apt_update

# TODO: Change this command when the repo is available

execute "Install passbolt" do
  command  "DEBIAN_FRONTEND=noninteractive apt-get install -y #{node['parameters']} /tmp/passbolt/passbolt*.deb \
            && service php$(php -r 'echo PHP_VERSION;' | sed 's:\\(7\\.[0-3]\\).*:\\1:')-fpm start #{node.has_key?(:parameters) ? '' : '&& service nginx start'}"
  action   :run
end
